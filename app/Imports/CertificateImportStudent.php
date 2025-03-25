<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Area;
use App\Models\StaffAdmission;
use App\Models\UserClass;
use App\Models\tbClass;
use App\Models\Certificate;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;
use App\Models\Course;
use App\Models\Dormitory;
use App\Models\Dormitory_user;
use Carbon\Carbon;
use Exception;

class CertificateImportStudent implements ToCollection
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
        $params_area['status'] = Consts::STATUS['active'];
        $this->isArea =  Area::getsqlArea()->get();
    }
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        $admin = Auth::guard('admin')->user();
        try {
            foreach ($rows as $key => $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                if ($this->rowCount == 1) {
                    continue;
                }
                // format lại ngày báo điểm nghe
                if ($row[9] !== null && $row[9] !== '') {
                    $excelDateCount = $row[9];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $day_score_listen = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[9]);
                            $day_score_listen = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                        }
                    }
                } else {
                    $day_score_listen = null;
                }

                // format lại ngày báo điểm nói
                if ($row[11] !== null && $row[11] !== '') {
                    $excelDateCount = $row[11];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $day_score_speak = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[11]);
                            $day_score_speak = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                        }
                    }
                } else {
                    $day_score_speak = null;
                }

                // format lại ngày báo điểm đọc
                if ($row[13] !== null && $row[13] !== '') {
                    $excelDateCount = $row[13];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $day_score_read = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[13]);
                            $day_score_read = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                        }
                    }
                } else {
                    $day_score_read = null;
                }

                // format lại ngày báo điểm viết
                if ($row[15] !== null && $row[15] !== '') {
                    $excelDateCount = $row[15];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $day_score_write = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[15]);
                            $day_score_write = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                        }
                    }
                } else {
                    $day_score_write = null;
                }

                // Kiểm tra học viên
                $params['json_params']['student_name'] = '';
                if ($row[1] != null || $row[1] != '') {
                    // Lấy thông tin học viên
                    $student = Student::where('admin_code', trim($row[1]))->first();
                    if (empty($student)) {
                        $params['json_params']['student_name'] = $row[2] ?? '';
                        $params['json_params']['admin_code'] = $row[1] ?? '';
                    }
                } else {
                    $params['json_params']['student_name'] = $row[2] ?? '';
                    $params['json_params']['admin_code'] = $row[1] ?? '';
                }


                // check Permission
                // $arr_id_student = DataPermissionService::getPermissionStudents($admin->id);
                // if (!in_array($student->id, $arr_id_student)) {
                //     $this->rowError++;
                //     array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Bạn không có quyền quản lý học viên này!');
                //     continue;
                // }

                // Kiểm tra lớp
                $params['json_params']['class_name'] = '';
                if ($row[3] != null || $row[3] != '') {
                    // Lấy thông tin lớp
                    $params_class['keyword'] = $row[3];
                    $class = tbClass::getSqlClass($params_class)->first();
                    if (empty($class)) {
                        // $this->rowError++;
                        // array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Lớp học không tồn tại!');
                        // continue;
                        $params['json_params']['class_name'] = $row[3] ?? '';
                    }
                    // Check học viên có trong lớp đó không
                    // $param_user_class['user_id'] = $student->id;
                    // $param_user_class['class_id'] = $class->id;
                    // $user_class = UserClass::getSqlUserClass($param_user_class)->first();
                    // if (empty($user_class)) {
                    //     $this->rowError++;
                    //     array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Học viên không nằm trong lớp ' . $row[3]);
                    //     continue;
                    // }
                } else {
                    $params['json_params']['class_name'] = $row[3] ?? '';
                }

                // kiểm tra giáo viên
                $params['json_params']['teacher_name'] = '';
                if ($row[16] != null && $row[16] != '') {
                    // Lấy thông tin GV
                    $params_gv['keyword'] = $row[16];
                    $teacher = Teacher::getSqlTeacher($params_gv)->first();
                    if (empty($teacher)) {
                        $params['json_params']['teacher_name'] = $row[16] ?? '';
                    }
                } else {
                    $params['json_params']['teacher_name'] = $row[16] ?? '';
                }




                if ($row[8] == '' && $row[10] == '' && $row[12] == '' && $row[14] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập số điểm! ');
                    continue;
                }


                // check user có trong bảng certificate chưa để cập nhật hoặc thêm mới
                if (isset($student->id) && isset($class->id) && isset($teacher->id)) {
                    $params_certificate['student_id'] = $student->id ?? null;
                    $params_certificate['class_id'] = $class->id ?? null;
                    $params_certificate['teacher_id'] = $teacher->id ?? null;
                    $certificate = Certificate::getSqlCertificate($params_certificate)->first();
                    if ($certificate) {
                        $certificate->type = $row[6] != '' ? strtolower(trim($row[6])) : $certificate->type;
                        $certificate->total_skill = $row[7] != '' ? $row[7] : $certificate->total_skill;
                        $certificate->score_listen = $row[8] != '' ? $row[8] : $certificate->score_listen;
                        $certificate->day_score_listen = $day_score_listen != null ? $day_score_listen : $certificate->day_score_listen;
                        $certificate->score_speak = $row[10] != '' ? $row[10] : $certificate->score_speak;
                        $certificate->day_score_speak = $day_score_speak != null ? $day_score_speak : $certificate->day_score_speak;
                        $certificate->score_read = $row[12] != '' ? $row[12] : $certificate->score_read;
                        $certificate->day_score_read = $day_score_read != null ? $day_score_read : $certificate->day_score_read;
                        $certificate->score_write = $row[14] != '' ? $row[14] : $certificate->score_write;
                        $certificate->day_score_write = $day_score_write != null ? $day_score_write : $certificate->day_score_write;
                        $certificate->json_params->note = $row[19] != '' ? $row[19] : $certificate->json_params->note;
                        $certificate->save();
                        $this->rowUpdate++;
                        continue;
                    } else {
                        // Thêm mới
                        $params['student_id'] = $student->id ?? null;
                        $params['class_id'] = $class->id ?? null;
                        $params['teacher_id'] = $teacher->id ?? null;
                        $params['type'] = strtolower(trim($row[6])) ?? "";
                        $params['total_skill'] = $row[7] ?? 0;
                        $params['score_listen'] = $row[8] ?? 0;
                        $params['day_score_listen'] = $day_score_listen;
                        $params['score_speak'] = $row[10] ?? 0;
                        $params['day_score_speak'] = $day_score_speak;
                        $params['score_read'] = $row[12] ?? 0;
                        $params['day_score_read'] = $day_score_read;
                        $params['score_write'] = $row[14] ?? 0;
                        $params['day_score_write'] = $day_score_write;
                        $params['json_params']['note'] = $row[19] ?? '';
                        $certificate = certificate::create($params);
                        $this->rowInsert++;
                        continue;
                    }
                } else {
                    // Thêm mới
                    $params['student_id'] = $student->id ?? null;
                    $params['class_id'] = $class->id ?? null;
                    $params['teacher_id'] = $teacher->id ?? null;
                    $params['type'] = strtolower(trim($row[6])) ?? "";
                    $params['total_skill'] = $row[7] ?? 0;
                    $params['score_listen'] = $row[8] ?? 0;
                    $params['day_score_listen'] = $day_score_listen;
                    $params['score_speak'] = $row[10] ?? 0;
                    $params['day_score_speak'] = $day_score_speak;
                    $params['score_read'] = $row[12] ?? 0;
                    $params['day_score_read'] = $day_score_read;
                    $params['score_write'] = $row[14] ?? 0;
                    $params['day_score_write'] = $day_score_write;
                    $params['json_params']['note'] = $row[19] ?? '';
                    $certificate = certificate::create($params);
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
