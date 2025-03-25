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
use App\Http\Services\HistoryService;
use App\Consts;
use App\Models\Course;
use Carbon\Carbon;
use Exception;

class Cbts_StudentImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $hasDuplicateError = false;
    protected $params = [];
    protected $idsStudent = [];
    protected $isAdmission = [];
    protected $isArea;
    protected $iscourse;
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    private $rowError = 0;
    private $admission_id = 0;
    public $hasError = false;
    public $errorMessage;
    public $arrErrorMessage = [];

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
        // $this->idsStudent = Student::get()->pluck('admin_code');
        $params_area['status'] = Consts::STATUS['active'];
        $this->isArea =  Area::getsqlArea()->get();
        $this->iscourse =  Course::getSqlCourse()->get();
        $this->admission_id = Auth::guard('admin')->user()->id;
        // $params['parent_ids'] = StaffAdmission::getAllStaffAdmissionChildrenAndSelf($parent_id);
        $this->isAdmission = StaffAdmission::getsqlStaffAdmission($params)->get();
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
                // lấy id cán bộ tuyển sinh theo mã
                $admission_id = $this->admission_id;
                $area_id = strtoupper(trim($row[1]));
                if ($row[1] !== null && $row[1] !== '') {
                    $area = $this->isArea->first(function ($item, $key) use ($area_id) {
                        return $item->code == $area_id;
                    });
                    if (isset($area->id)) {
                        $area_id =  $area->id;
                    } else {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã khu vực ' . $row[1] . ' không tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập khu vực cho học viên');
                    continue;
                }
                //Bắt buộc có giới tính
                if ($row[8] == null || $row[8] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập giới tính học viên!');
                    continue;
                }
                $row[8] = strtolower(trim($row[8]));
                $gender = $row[8] == 'nam' ? 'male' : ($row[8] == 'nữ' ? 'female' : 'other');
                // lấy id khóa học theo tên
                $course_id = '';
                if ($row[6] !== null && $row[6] !== '') {
                    $course_id = strtoupper(trim($row[6]));
                    $course = $this->iscourse->first(function ($item, $key) use ($course_id) {
                        return strtoupper(trim($item->name))  == $course_id;
                    });
                    if (isset($course->id)) {
                        $course_id =  $course->id;
                    } else {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã khóa học ' . $row[6] . ' không tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập khóa học cho học viên');
                    continue;
                }
                // Find the last admin code
                $lastAdmin = Student::orderBy('id', 'desc')->first();
                $lastAdminCode = $lastAdmin->id ? $lastAdmin->id : 0;
                // Extract the numeric part and increment it
                $numericPart = (int)$lastAdminCode;
                // Calculate the number of digits required for the numeric part
                $numDigits = max(4, strlen((string)$numericPart));
                // Add one to the numeric part
                $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
                $admin_code_auto = 'HT' . $newNumericPart;

                // lấy email ngẫu nhiên
                $uniqueTimestamp = microtime(true);
                $email = $uniqueTimestamp . '@tuhoctiengduc.vn';

                // Xử lý nếu không có Mã SV
                $row[0] = trim($row[0]);
                $rowCode = $row[0];
                if ($rowCode !== null && $rowCode !== '') {
                    $admin_code = $rowCode;
                } else {
                    $admin_code = $admin_code_auto;
                }
                // check mã học viên là duy nhất
                $check_code = Student::where('admins.admin_code', $admin_code)->count();
                if ($check_code > 0) {
                    $this->rowError++;
                    array_push($this->arrErrorMessage,  'Vị trí ' . $key . ': Mã học viên ' . $admin_code . ' đã tồn tại');
                    continue;
                }
                // check CCCD là duy nhất
                if ($row[3] !== null && $row[3] !== '') {
                    $check_cccd = Student::whereJsonContains('admins.json_params->cccd', $row[3])->count();
                    if ($check_cccd > 0) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage,  'Vị trí ' . $key . ': CCCD ' . $row[3] . ' đã tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage,  'Vị trí ' . $key . ': Cần nhập CCCD cho học viên');
                    continue;
                }

                if ($row[4] !== null && $row[4] !== '') {
                    $excelDateCount = $row[4];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $date_range = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    } else {
                        try {
                            $date_rangeString = trim($row[4]);
                            $date_range = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $date_range = null;
                }

                $json = [
                    "address" =>  '',
                    "form_training" =>  '',
                    "dad_name" =>  '',
                    "dad_phone" =>  '',
                    "mami_name" =>  '',
                    "mami_phone" =>  '',
                    "cccd" => $row[3] ?? '',
                    "date_range" =>  $date_range,
                    "issued_by" => $row[7] ?? '',
                    "contract_type" => '',
                    "contract_status" => '',
                    "contract_performance_status" => '',
                    "dormitory" => $row[9] ?? '',
                ];
                // Khi tạo thì trạng thái học viên là nhập học (status_study = 1)
                $student = Student::create([
                    'admin_code' => $admin_code,
                    'admission_id' => $admission_id,
                    'area_id' => $area_id,
                    'email' => $email,
                    'password' => Consts::USER_PW_DEFAULT,
                    'name' => trim($row[2]),
                    'role' => 8,
                    'course_id' => (int)$course_id ?? "",
                    'state' => Consts::STUDENT_STATUS['try learning'],
                    'status_study' => 1,
                    'phone' => '',
                    'gender' => $gender,
                    'admin_type' => Consts::ADMIN_TYPE['student'],
                    'json_params' => $json,
                ]);
                // Thêm lịch sử trạng thái cho học viên (Mới đầu mặc định là nhập học)
                $history = HistoryService::addHistoryStatusStudy($student->id, '', 1);
                $this->rowInsert++;
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
        $expectedHeaders = ['Mã SV (Để trống nếu không có)', 'Mã KV * (Bắt buộc)', 'Họ và tên * (Bắt buộc)', 'CCCD * (Bắt buộc - không trùng)', 'Ngày cấp', 'Cấp bởi', 'Khóa học * (Bắt buộc)', 'Chỗ ở(nếu có)'];

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
