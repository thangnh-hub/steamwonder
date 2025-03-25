<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Consts;
use App\Models\HvExamTopic;
use App\Models\HvExamQuestions;
use App\Models\HvExamAnswers;
use App\Models\HvExamOption;
use Carbon\Carbon;
use Exception;

class HvExamTopicImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];
    protected $arr_lervel;
    protected $arr_group;

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
        $params_area['status'] = Consts::STATUS['active'];
        $this->arr_lervel = [1, 2, 3, 4, 5, 6];
        $this->arr_group = ['1', '1a', '1b', '2', '3', '4', '5'];
    }
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                if ($this->rowCount == 1) {
                    continue;
                }

                $domain = 'https://daotao.dwn.com.vn/';

                // kiểm tra hình ảnh
                $img = null;
                if ($row[7] !== null && $row[7] !== '') {

                    $url_img = $domain . $row[6] . $row[7];
                    $img = '<img alt="" src="' . $url_img . '" />';
                }

                // kiểm tra nội dung
                $content_before = $content_after = null;
                if ($row[5] !== null && $row[5] !== '') {
                    $content_before = '<p>' . $row[5] . '</p>';;
                }
                if ($row[8] !== null && $row[8] !== '') {
                    $content_after = '<p>' . $row[8] . '</p>';;
                }

                // Check số câu hỏi
                if ($row[13] == null && $row[13] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập số câu hỏi!');
                    continue;
                }
                // kiểm tra loại câu hỏi
                if ($row[10] !== null && $row[10] !== '') {
                    if (!in_array(trim($row[10]), Consts::TYPE_EXAM)) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Loại câu hỏi ' . $row[10] . ' không tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập loại câu hỏi!');
                    continue;
                }

                // Kiểm tra đường dẫn Audio
                $url_audio = null;
                if (!empty($row[9])) {
                    $arr_url = array_slice(explode('/', $row[6]), 0, 3);
                    $folder_audio = implode('/', $arr_url);
                    $url_audio = sprintf('%s%s/Audio/%s/%s', $domain, $folder_audio, trim($row[2]), str_replace(' ', '%20', $row[9]));
                }
                $user = Auth::guard('admin')->user();

                // Thêm vào bảng topic
                $params_topic['id_level'] = (int)$row[0];
                $params_topic['is_type'] = trim($row[2]);
                $params_topic['type_question'] = trim($row[10]);
                $params_topic['organization'] = trim($row[1]);
                $params_topic['skill_test'] = trim($row[3]);
                $params_topic['content'] = $content_before . $img . $content_after;
                $params_topic['audio'] = $url_audio;
                $params_topic['tag'] = trim($row[11]);
                $params_topic['tag_name'] = trim($row[12]);
                $params_topic['admin_created_id'] = $user->id;
                $topics = HvExamTopic::create($params_topic);

                if ($topics) {
                    $params_question['id_topic'] = $topics->id;
                    $params_question['is_type'] = trim($row[10]);
                    $params_question['admin_created_id'] = $user->id;
                    for ($i = 0; $i < (int)$row[13]; $i++) {
                        $stt = 13 + (4 * $i);
                        // Nếu là câu hỏi đầu tiên và loại 'nhap_dap_an_dang_bang'
                        if ($i == 0 && trim($row[10]) === 'nhap_dap_an_dang_bang') {
                            $json_topic = [
                                'demo_question' => $row[$stt + 1],
                                'demo_answer' => $row[$stt + 4],
                            ];
                            $topics->update(['json_params' => $json_topic]);
                            continue; // Bỏ qua bước tạo câu hỏi và chuyển sang câu tiếp theo
                        }
                        $params_question['question'] = $row[$stt + 1];
                        $params_question['point'] = $row[$stt + 2];
                        $questions = HvExamQuestions::create($params_question);
                        // Tạo đáp án theo từng case
                        switch (trim($row[10])) {
                            case 'nhap_dap_an_dang_bang':
                                HvExamAnswers::create([
                                    'id_question' => $questions->id,
                                    'answer' => $row[$stt + 4],
                                    'correct_answer' => 1,
                                ]);
                                break;
                            case 'chon_dap_an':

                                $list_answers = explode('&&', $row[$stt + 3]);
                                foreach ($list_answers as $answer) {
                                    HvExamAnswers::create([
                                        'id_question' => $questions->id,
                                        'answer' => trim($answer),
                                        'correct_answer' => (strtolower(trim($answer)[0]) == strtolower(trim($row[$stt + 4])[0])) ? 1 : 0,
                                    ]);
                                }
                                break;

                            default: //  Nhập đáp án, dạng bảng
                                HvExamAnswers::create([
                                    'id_question' => $questions->id,
                                    'answer' => $row[$stt + 4],
                                    'correct_answer' => 1,
                                ]);
                                break;
                        }
                    }
                }
                $this->rowInsert++;
                continue;
            }
            DB::commit();
            $this->hasError = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
            $this->errorMessage = "Lỗi tại vị trí " . $this->rowCount . ": " . $e->getMessage();
        }
    }

    public function getRowCount()
    {
        $data_count = [
            'total_row' => $this->rowCount,
            'update_row' => $this->rowUpdate,
            'insert_row' => $this->rowInsert,
            'error_row' => $this->rowError,
            'error_mess' => $this->arrErrorMessage,
        ];
        return $data_count;
    }
}
