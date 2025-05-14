<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use Carbon\Carbon;
use Exception;
use App\Models\Receipt;

class StudentBalanceReceiptImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    protected $idsStudent = [];
    protected $isArea = [];
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

                if ($row[0] == null || $row[0] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã học sinh!');
                    continue;
                }

                // Kiểm tra học sinh
                $student = Student::where('student_code', trim($row[0]))->first();
                if (empty($student)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy học sinh!');
                    continue;
                }

                // Kiểm tra TBP của học sinh
                $reseipt = Receipt::where('student_id', $student->id)->first();
                if (empty($reseipt)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, $student->id);
                    continue;
                }


                // Cập nhật prev_balance
                $reseipt->prev_balance = trim($row[1]);
                // Key chạy từ 1
                $json = [];
                for ($i = 0; $i < 4; $i++) {
                    $stt = 1 + (2 * $i);
                    if ($row[$stt + 1] != '' ||   $row[$stt + 2] != '') {
                        $json['explanation'][$i]['value'] = $row[$stt + 1];
                        $json['explanation'][$i]['content'] = $row[$stt + 2];
                    }
                }
                $reseipt->json_params = $json;
                $reseipt->save();
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
