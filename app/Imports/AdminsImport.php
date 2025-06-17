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
use App\Models\Role;
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
                if (!empty(trim($row[2]))) {
                    $deleteAdmin = Admin::where('admin_code', trim($row[2]))->first();
                    if ($deleteAdmin) {
                        $deleteAdmin->delete();
                        $this->rowError++;
                        $this->arrErrorMessage[] = "Lỗi tại vị trí {$this->rowCount}: Đã xóa nhân viên " . trim($row[2]);
                        continue;
                    }
                }
                // Kiểm tra quyền
                $id_role = trim($row[5]);
                if (!empty($id_role)) {
                    $role = Role::find($id_role);
                }
                if (empty($role)) {
                    $role = Role::create([
                        'name' => trim($row[4]),
                        'status' => Consts::STATUS['active'],
                        'admin_created_id' => $admin->id,
                    ]);
                }
                $id_role = $role->id;

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
                    "position" =>  null,
                    "address" =>  null,
                    "brief" =>  $row[9] ?? null,
                    'admin_type' => trim($row[10]),
                    "content" =>  null,
                    "area_id" =>  null,
                    "role_extend" =>  null,
                ];

                // Kiểm tra admin_code
                $admin_code = trim($row[1] ?? '');
                if (!empty($admin_code)) {
                    $existingAdmin = Admin::where('admin_code', $admin_code)->first();
                    if ($existingAdmin) {
                        // Cập nhật thông tin admin
                        $existingAdmin->update([
                            'name' => trim($row[0]),
                            'area_id' => trim($row[7]),
                            'email' => $row[8] != '' ? $row[8] : $existingAdmin->email,
                            'role' => $id_role,
                            'admin_type' => trim($row[10]),
                            'status' => Consts::STATUS['active'],
                            'json_params' => $json,
                            'admin_updated_id' => $admin->id,
                        ]);
                        $this->rowUpdate++;
                        continue;
                    } else {
                        // Tạo mới admin
                        $user = Admin::create([
                            'admin_code' => $admin_code_auto,
                            'name' => trim($row[0]),
                            'email' => $row[8] != '' ? $row[8] : $admin_code_auto . '@gmail.com',
                            'password' => Consts::USER_PW_DEFAULT,
                            'admin_type' => trim($row[10]),
                            'area_id' => trim($row[7]),
                            'role' => $id_role,
                            'status' => Consts::STATUS['active'],
                            'json_params' => $json,
                            'admin_updated_id' => $admin->id,
                        ]);
                        $this->rowInsert++;
                        continue;
                    }
                } else {
                    $user = Admin::create([
                        'admin_code' => $admin_code_auto,
                        'name' => trim($row[0]),
                        'area_id' => trim($row[7]),
                        'email' => $row[8] != '' ? $row[8] : $admin_code_auto . '@gmail.com',
                        'password' => Consts::USER_PW_DEFAULT,
                        'role' => $id_role,
                        'status' => Consts::STATUS['active'],
                        'json_params' => $json,
                        'admin_updated_id' => $admin->id,
                    ]);
                    $this->rowInsert++;
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
