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
use App\Models\Dormitory;
use App\Models\Dormitory_user;
use Carbon\Carbon;
use Exception;

class DormitoryImportStudent implements ToCollection
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
        try {
            foreach ($rows as $key => $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                if ($this->rowCount == 1) {
                    continue;
                }
                // Ngày vào KTX là bắt buộc
                if ($row[11] !== null && $row[11] !== '') {
                    $excelDateCount = $row[11];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $time_in = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[11]);
                            $time_in = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập ngày vào KTX!');
                    continue;
                }

                // time out
                if ($row[12] !== null && $row[12] !== '') {
                    $excelDateCount = $row[12];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $time_out = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[12]);
                            $time_out = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $time_out = null;
                }

                // time expires
                if ($row[13] !== null && $row[13] !== '') {
                    $excelDateCount = $row[13];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $time_expires = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[13]);
                            $time_expires = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $time_expires = null;
                }

                // kiểm tra khu vực
                $area_id = strtoupper(trim($row[7]));
                if ($row[7] !== null && $row[7] !== '') {
                    $area = $this->isArea->first(function ($item, $key) use ($area_id) {
                        return $item->code == $area_id;
                    });
                    if (isset($area->id)) {
                        $area_id =  $area->id;
                    } else {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã khu vực ' . $row[7] . ' không tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã khu vực!');
                    continue;
                }

                //Bắt buộc có giới tính
                if ($row[4] == null || $row[4] == '') {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập giới tính học viên!');
                    continue;
                }

                // Kiểm tra học viên
                $check_student = '';
                $student = trim($row[1]);
                if ($row[1] !== null && $row[1] !== '') {
                    $check_student = Student::where('admin_code', trim($row[1]))->first();
                    if ($check_student == null) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã học viên ' . $row[1] . ' không tồn tại');
                        continue;
                    }
                    // kiểm tra nếu trùng thì update lại
                    $params_dor['area_id'] = $area_id;
                    $params_dor['name'] = trim($row[8]);
                    $params_dor['don_nguyen'] = $row[10];
                    $dormitory = Dormitory::getSqlDormitory($params_dor)->first();
                    if ($dormitory == null) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy phòng ' . $row[8] . ($row[10] != '' ? ' đơn nguyên ' . $row[10] : '') . ' tại khu vực ' . $row[7] . '!');
                        continue;
                    }
                    $params_check_update['id_user'] = $check_student->id;
                    $params_check_update['dormitory'] = $dormitory->id;
                    $params_check_update['time_in'] = $time_in;
                    $check_update = Dormitory_user::getSqlDormitoryUser($params_check_update)->first();
                    if ($check_update) {
                        if ($check_update->status == 'already' && $time_out != null) {
                            // cập nhật số lượng và trạng thái phòng
                            $dormitory->quantity = $dormitory->quantity - 1;
                            $dormitory->status = Consts::STATUS_DORMITORY['already'];
                            if ($dormitory->quantity <= 0) {
                                $dormitory->status = Consts::STATUS_DORMITORY['empty'];
                                $dormitory->gender = Consts::GENDER['other'];
                            }
                            $dormitory->save();
                        }
                        $dormitory_user = Dormitory_user::find($check_update->id);
                        Dormitory_user::where('id', $check_update->id)
                            ->update([
                                "status" => $time_out != null  ? 'leave' : 'already',
                                "time_out" => $time_out,
                                "time_expires" => $time_expires,
                                "json_params->don_vao" => $row[14] ?? $dormitory_user->json_params->don_vao ?? '',
                                "json_params->ghi_chu" => $row[15] ?? $dormitory_user->json_params->ghi_chu ?? '',
                            ]);
                        $this->rowUpdate++;

                        continue;
                    } else {
                        // kiểm tra học viên đang ở trong phòng nào chưa
                        $params_check['status'] =  Consts::STATUS_DORMITORY_USER['already'];
                        $params_check['id_user'] =  $check_student->id;
                        $check_user =  Dormitory_user::getSqlDormitoryUser($params_check)->first();
                        if ($check_user) {
                            $this->rowError++;
                            array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Học viên ' . $row[1] . ' đã được ghép vào phòng ' . $check_user->dormitory->name . '!');
                            continue;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã học viên!');
                    continue;
                }

                // Lấy thông tin phòng học
                if ($row[8] !== null && $row[8] !== '') {
                    $params_dor['area_id'] = $area_id;
                    $params_dor['name'] = $row[8];
                    $params_dor['don_nguyen'] = $row[10];
                    $dormitory = Dormitory::getSqlDormitory($params_dor)->first();
                    if ($dormitory) {
                        // kiểm tra slot còn k
                        if ($dormitory->slot > $dormitory->quantity) {
                            // cập nhật lại giới tính của học viên
                            $gender = strtolower(trim($row[4]));
                            $check_student->gender = ($gender == 'nam') ? 'male' : ($gender == 'nữ' ? 'female' : 'other');
                            $check_student->save();

                            // Xử lý thêm học viên vào phòng và đổi lại trạng thái
                            $params['id_dormitory'] = $dormitory->id ?? 0;
                            $params['id_user'] = $check_student->id ?? 0;
                            $params['time_in'] = $time_in;
                            $params['time_out'] = $time_out;
                            $params['time_expires'] = $time_expires;
                            $params['status'] = $row[12] !== null && $row[12] !== '' ? 'leave' : 'already';
                            $params['json_params']['don_vao'] = $row[14] ?? '';
                            $params['json_params']['ghi_chu'] = $row[15] ?? '';
                            $dormitory_user = Dormitory_user::create($params);
                            $this->rowInsert++;
                            if ($row[12] == null || $row[12] == '') {
                                $dormitory->quantity = $dormitory->quantity + 1;
                                // cập nhật lại trạng thái của phòng nếu chưa có
                                if ($dormitory->quantity < $dormitory->slot) {
                                    $dormitory->status = Consts::STATUS_DORMITORY['already'];
                                    if ($dormitory->gender == Consts::GENDER['other']) {
                                        $dormitory->gender = $check_student->gender != null ? $check_student->gender : Consts::GENDER['other'];
                                    } else {
                                        $check_student->gender = $dormitory->gender;
                                        $check_student->save();
                                    }
                                } else {
                                    // đổi trạng thái sang đầy
                                    $dormitory->status = Consts::STATUS_DORMITORY['full'];
                                }
                                $dormitory->save();
                            }
                        } else {
                            $this->rowError++;
                            array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Phòng ' . $row[8] . ' tại khu vực ' . $row[7] . ' đã đầy!');
                            continue;
                        }
                    } else {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Không tìm thấy phòng ' . $row[8] . ($row[10] != '' ? ' đơn nguyên ' . $row[10] : '') . ' tại khu vực ' . $row[7] . '!');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập tên của phòng!');
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

    private function isHeaderRow(array $row)
    {
        // Các giá trị tiêu đề mong đợi
        $expectedHeaders = [
            'TSS',
            'Mã học viên *',
            'Họ tên',
            'CBTS',
            'Giới tính *',
            'Trạng thái HV *',
            'Khóa',
            'Khu vực *',
            'Phòng',
            'Trạng thái',
            'Đơn nguyên',
            'Ngày vào KTX *',
            'Ngày ra KTX',
            'Ngày hết hạn KTX',
            'Đơn vào KTX',
            'Ghi chú'
        ];

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
