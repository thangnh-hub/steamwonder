<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\AccountingDebt;
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

class AccountingDebtImport implements ToCollection
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
                // Check ngày thanh toán
                if ($row[18] !== null && $row[18] !== '') {
                    $excelDateCount = $row[18];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $time_payment = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $time_paymentString = trim($row[18]);
                            $time_payment = Carbon::createFromFormat('d/m/Y', $time_paymentString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập ngày thanh toán!');
                    continue;
                }

                // Kiểm tra học viên
                if ($row[0] == null || $row[0] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã học viên!');
                    continue;
                }

                // Lấy thông tin học viên
                $student = Student::where('admin_code', trim($row[0]))->first();
                if (empty($student)) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Học viên không tồn tại!');
                    continue;
                }
                // Loại GD

                if ($row[16] == null || $row[16] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập loại tài chính ');
                    continue;
                }
                $exists = array_search(strtolower($row[16]), Consts::TYPE_REVENUE);
                if ($exists == 'false') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Loại tài chính không hợp lệ!');
                    continue;
                }
                $type_revenue = strtolower($row[16]);
                // Check xem HV đã tồn tại loạn này chưa
                $check = AccountingDebt::where('student_id', $student->id)->where('type_revenue', $type_revenue)->count();
                if ($check > 0) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Đã tồn tại loại giao dịch này!');
                    continue;
                }

                $params['student_id'] = $student->id;
                $params['type_revenue'] = $type_revenue;
                $params['amount_paid'] = $row[17];
                $params['time_payment'] = $time_payment;
                $params['json_params']['note'] = $row[19];
                $params['admin_created_id'] = Auth::guard('admin')->user()->id;
                AccountingDebt::create($params);
                $this->rowInsert++;

                // Cập nhật trạng thái của user
                // if ($row[20] != null || $row[20] != '') {
                //     $status_accounting_debt = 0;
                //     if(mb_strtolower($row[20], 'UTF-8') == 'đã thanh toán tc'){
                //         $status_accounting_debt = 1;
                //     }
                //     $jsonParams = (array) $student->json_params ?? [];
                //     $jsonParams['status_accounting_debt'] = $status_accounting_debt;
                //     $student->json_params = $jsonParams;
                //     $student->save();
                // }
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
