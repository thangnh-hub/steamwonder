<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Area;
use App\Models\Field;
use App\Models\StatusStudent;
use App\Models\StaffAdmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Http\Services\HistoryService;
use App\Http\Services\DataPermissionService;
use App\Consts;
use Carbon\Carbon;
use Exception;

class UpdateCbtsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    protected $list_id_user = [];
    protected $user = null;
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        $this->params = $params;
        $params_area['status'] = Consts::STATUS['active'];
        $this->user = Auth::guard('admin')->user()->id;
        $this->list_id_user = DataPermissionService::getPermissionUsersAndSelfAll($this->user);
    }
    public function collection(Collection $rows)
    {
        try {
            $list_idsRow = $rows->pluck(0)->toArray();
            $filteredIdsRow = array_filter($list_idsRow, function ($value) {
                return trim($value) !== '';
            });

            foreach ($rows as $key=> $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                // Bỏ qua hàng tiêu đề
                if ($this->rowCount == 1) {
                    continue;
                }
                // Kiểm tra học viên
                if ($row[0] == null || $row[0] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã học viên!');
                    continue;
                }
                // Kiểm tra mã CBTS
                if ($row[5] == null || $row[5] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã CBTS!');
                    continue;
                }
                // Lấy thông tin CBTS
                $staff_admission = StaffAdmission::where('admin_code', trim($row[5]))->whereIn('id',$this->list_id_user)->first();
                if(empty($staff_admission)){
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy thông tin CBTS cấp dưới!');
                    continue;
                }

                // Lấy thông tin học viên được quản lý
                $student = Student::where('admin_code', trim($row[0]))->whereIn('admission_id',$this->list_id_user)->first();
                if(empty($student)){
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy thông tin học viên!');
                    continue;
                }

                $student->admission_id = $staff_admission->id;
                $student->save();
                $this->rowUpdate++;
            }
            $this->hasError = false;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->hasError = true;
            $this->errorMessage = $e->getMessage();
        }
    }

    private function isHeaderRow(array $row)
    {
        // Các giá trị tiêu đề mong đợi
        $expectedHeaders = ['Mã HV', 'Họ và tên', 'CCCD','Giới tính', 'Mã KV', 'Mã CBTS'];

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
