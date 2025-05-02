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
use App\Models\Admin;
use App\Models\Department;
use Carbon\Carbon;
use Exception;

class AdminsImport implements ToCollection
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
        $this->params = $params;
    }
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();
            foreach ($rows as $key => $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                if ($this->rowCount == 1) {
                    continue;
                }

                // kiểm tra phòng ban
                if ($row[9] !== null && $row[9] !== '') {
                    preg_match_all('/\b\w/u', trim($row[9]), $matches);
                    $code = implode('', $matches[0]);
                    $department = Department::where('name', trim($row[9]))
                        ->where('code', $code)
                        ->where('area_id', trim($row[6]))
                        ->first();
                    // chưa có thì tạo mới
                    if (empty($department)) {
                        $department = Department::create([
                            'name' => trim($row[9]),
                            'code' => strtoupper($code),
                            'area_id' => trim($row[6]),
                            'admin_created_id' => $admin->id,
                        ]);
                    }
                }

                // Find the last admin code
                $lastAdmin = Admin::orderBy('id', 'desc')->first();
                $lastAdminCode = $lastAdmin->id ? $lastAdmin->id : 0;
                // Extract the numeric part and increment it
                $numericPart = (int)$lastAdminCode;
                // Calculate the number of digits required for the numeric part
                $numDigits = max(4, strlen((string)$numericPart));
                // Add one to the numeric part
                $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
                $admin_code_auto = 'NV' . $newNumericPart;

                $json = [
                    "position" =>  trim($row[3]),
                    "address" =>  null,
                    "brief" =>  trim($row[11]),
                    "content" =>  null,
                    "area_id" =>  null,
                    "role_extend" =>  null,
                ];
                $user = Admin::create([
                    'admin_code' => $admin_code_auto,
                    'admin_type' => trim($row[4]),
                    'department_id' => $department->id ?? null,
                    'area_id' => trim($row[6]),
                    'password' => Consts::USER_PW_DEFAULT,
                    'name' => trim($row[1]),
                    'email' => $admin_code_auto.'@gmail.com',
                    'role' => trim($row[5]),
                    'status' => Consts::STATUS['active'],
                    'gender' => Consts::GENDER['other'],
                    'json_params' => $json,
                    'admin_created_id' => $admin->id,
                ]);
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
