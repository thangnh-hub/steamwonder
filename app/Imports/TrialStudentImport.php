<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Area;
use App\Models\StaffAdmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use App\Models\Course;
use Carbon\Carbon;
use Exception;

class TrialStudentImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    private $rowUpdate = 0;
    private $rowError = 0;
    private $rowCount = 0;
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
                // lấy thông tin học viên
                $code_new = trim($row[1]);
                $code_old = trim($row[0]);
                $student = Student::where('admins.admin_code', $code_old)->orWhereJsonContains('admins.json_params->trial_code', $code_old)->first();

                if ($student) {

                    // check mã học viên là duy nhất
                    $check_code = Student::where('admins.admin_code', $code_new)->where('admins.id', '!=', $student->id)->count();
                    if ($check_code > 0) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage,  'Vị trí ' . $key . ': Mã học viên ' . $code_new . ' đã tồn tại');
                        continue;
                    }
                    Student::where('id', $student->id)
                        ->update([
                            "admin_code" => $code_new,
                            "json_params->trial_code" => $code_old
                        ]);

                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage,  'Vị trí ' . $key . ': Không tìm thấy học viên ' . $code_old);
                    continue;
                }
                $this->rowUpdate++;

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
            'update_row' => $this->rowUpdate,
            'error_row' => $this->rowError,
            'error_mess' => $this->arrErrorMessage,
        ];
        return $data_count;
    }
}
