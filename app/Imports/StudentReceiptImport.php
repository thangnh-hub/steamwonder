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
use App\Models\StudentService;
use App\Models\Reseipt;
use Carbon\Carbon;
use Exception;
use App\Http\Services\ReceiptService;
use App\Models\Receipt;

class StudentReceiptImport implements ToCollection
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
        $receiptService = new ReceiptService;
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

                // Kiểm tra học sinh, và xem đã có trong lớp hay chưa
                $student = Student::where('student_code', trim($row[0]))->first();
                if (empty($student)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy học sinh!');
                    continue;
                }
                // Xử lý nếu k nhập kỳ bắt đầu hoặc kỳ bắt đầu k hợp lệ
                if ($row[1] !== null && $row[1] !== '') {
                    $excelDateCount = $row[1];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $enrolled_at = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    } else {
                        try {
                            $enrolled_atString = trim($row[1]);
                            $enrolled_at = Carbon::createFromFormat('d/m/Y', $enrolled_atString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->rowError++;
                            array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Sai định dạng kỳ bắt đầu!');
                            continue;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập thời gian hết hạn!');
                    continue;
                }


                $reseipt = Receipt::where('student_id', $student->id)->first();
                if (!empty($reseipt)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Học sinh đã có TBP !');
                    continue;
                }

                $data['student_services'] = $student->studentServices()
                    ->where('status', 'active')
                    ->get();

                $data['include_current_month'] = false;
                $data['enrolled_at'] = $enrolled_at;
                $calcuReceiptrenew = $receiptService->renewReceiptForStudent($student, $data);
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
