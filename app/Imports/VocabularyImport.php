<?php

namespace App\Imports;

use App\Models\Vocabulary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Exception;

class VocabularyImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
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
                // Lấy thông tin từ vựng
                if ($row[2] !== null && $row[2] !== '') {
                    $params['keyword'] = trim($row[2]);
                    $vocabulary = Vocabulary::getSqlVocabulary($params)->first();
                    if ($vocabulary) {
                        // đã có thì cập nhật
                        Vocabulary::where('id', $vocabulary->id)
                            ->update([
                                "prefix" => $row[3] ?? '',
                                "transcription" => $row[4] ?? '',
                                "meaning" => $row[5] ?? '',
                                "json_params->explanation->de" => $row[6] ?? '',
                                "json_params->explanation->vi" => $row[7] ?? '',
                                "json_params->sample" => $row[8] ?? '',
                            ]);
                        $this->rowUpdate++;
                        continue;
                    } else {
                        // chưa có thì thêm mới
                        $params_create['name'] = $row[2];
                        $params_create['image'] = url('themes/admin/img/no_image.jpg');
                        $params_create['prefix'] = $row[3] ?? '';
                        $params_create['transcription'] = $row[4] ?? '';
                        $params_create['meaning'] = $row[5] ?? '';
                        $params_create['json_params']['explanation']['de'] = $row[6] ?? '';
                        $params_create['json_params']['explanation']['vi'] = $row[7] ?? '';
                        $params_create['json_params']['sample'] = $row[8] ?? '';
                        Vocabulary::create($params_create);
                        $this->rowInsert++;
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập từ vựng');
                    continue;
                }
            }
            DB::commit();
            $this->hasError = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
            $this->errorMessage = "Lỗi tại vị trí " . $this->rowCount . ": " . $e->getMessage();
        }
    }

    private function isHeaderRow(array $row)
    {
        // Các giá trị tiêu đề mong đợi
        $expectedHeaders = ['TSS', 'Hình ảnh', 'Từ vựng', 'Tiền tố', 'Phiên âm', 'Nghĩa tiếng việt', 'Giải thích bằng tiếng Đức', 'Giải thích bằng tiếng Việt', 'Mẫu câu đi kèm'];

        $rowKeys = array_map('strtolower', array_map('trim', $row));
        $expectedHeaders = array_map('strtolower', array_map('trim', $expectedHeaders));

        // Kiểm tra xem các giá trị của hàng có khớp với các giá trị tiêu đề mong đợi hay không
        return count(array_diff($expectedHeaders, $rowKeys)) === 0;
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
