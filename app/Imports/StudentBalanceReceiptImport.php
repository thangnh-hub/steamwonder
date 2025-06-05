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

                // Kiểm tra TBP loại renew của học sinh
                $receipt = Receipt::where('student_id', $student->id)->where('type_receipt', 'renew')->first();
                if (empty($receipt)) {
                    // Kiểm tra TBP loại yearly của học sinh
                    $receipt = Receipt::where('student_id', $student->id)->where('type_receipt', 'yearly')->first();
                    if (empty($receipt)) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': ' . $student->id . ' Chưa có TBP!');
                        continue;
                    }
                }
                // Cập nhật TBP
                $data = [
                    'value' => trim($row[2]),
                    'content' => trim($row[3]),
                ];
                $json = [];
                // Cập nhật json_params, Có r thì push vào mảng chưa có thì thêm
                if (isset($receipt->json_params->explanation)) {
                    // Push vào mảng json_params
                    $json = json_decode(json_encode($receipt->json_params), true);
                    array_push($json['explanation'], $data);
                } else {
                    $json['explanation'][] = $data;
                }
                $receipt->prev_balance += trim($row[2]);
                $receipt->total_final = $receipt->total_amount - $receipt->total_discount - $receipt->prev_balance;
                $receipt->total_due = $receipt->total_final;
                $receipt->json_params = $json;
                $receipt->save();
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
