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
use App\Consts;
use Carbon\Carbon;
use Exception;

class StudentImport implements ToCollection
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
    protected $isField;
    private $rowCount = 0;
    private $rowUpdate = 0;
    private $rowInsert = 0;
    public $hasError = false;
    public $errorMessage;

    public function __construct($params = [])
    {
        set_time_limit(0);
        $this->params = $params;
        $this->idsStudent = Student::get()->pluck('admin_code');
        $params_area['status'] = Consts::STATUS['active'];
        $this->isArea =  Area::getsqlArea()->get();
        $this->isField =  Field::getsqlField()->get();
        $parent_id = Auth::guard('admin')->user()->id;
        // $params['parent_ids'] = StaffAdmission::getAllStaffAdmissionChildrenAndSelf($parent_id);
        $this->isAdmission = StaffAdmission::getsqlStaffAdmission($params)->get();
    }
    public function collection(Collection $rows)
    {
        try {
            $list_idsRow = $rows->pluck(0)->toArray();
            $filteredIdsRow = array_filter($list_idsRow, function ($value) {
                return trim($value) !== '';
            });

            foreach ($rows as $row) {
                $this->rowCount++;
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }
                // Bỏ qua hàng tiêu đề
                // if ($this->isHeaderRow($row->toArray())) {
                //     continue;
                // }
                if ($this->rowCount == 1) {
                    continue;
                }
                // Count rows is import

                // lấy id khu vực theo mã
                $area_id = strtoupper(trim($row[2]));
                if ($row[2] !== null && $row[2] !== '') {
                    $area = $this->isArea->first(function ($item, $key) use ($area_id) {
                        return $item->code == $area_id;
                    });
                    $area_id = isset($area->id) ? $area->id : null;
                }

                // lấy id cán bộ tuyển sinh theo mã
                $admission_id = strtoupper(trim($row[1]));
                if ($row[1] !== null && $row[1] !== '') {
                    $admission = collect($this->isAdmission)->first(function ($item, $key) use ($admission_id) {
                        return $item->admin_code == $admission_id;
                    });
                    $admission_id = isset($admission->id) ? $admission->id : null;
                }

                // Lấy id ngành nghề
                $arr_id_field = [];
                if ($row[21] !== null && $row[21] !== '') {
                    $arr_name_field = array_map('trim', explode('/', $row[21]));
                    foreach ($arr_name_field as $val) {
                        $field = $this->isField->first(function ($item, $key) use ($val) {
                            return strtolower($item->name) == strtolower(trim($val));
                        });
                        if ($field) {
                            $arr_id_field[] = $field->id;
                        }
                    }
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

                $rowEmail = $row[3];
                if ($rowEmail !== null && $rowEmail !== '') {
                    try {
                        $birthdayString = trim($row[7]);
                        $birthday = Carbon::createFromFormat('d/m/Y', $birthdayString)->format('Y-m-d');
                    } catch (Exception $e) {
                        $this->hasError = true;
                        $this->errorMessage = 'Sai định dạng ngày sinh ở học viên: ' . $row[0];
                        // return null;
                    }

                    $student = Student::where('admin_code', '!=', $row[0])->where('email', $rowEmail)->first();
                    if ($student) {
                        $this->hasError = true;
                        $this->errorMessage = 'Email: ' . $rowEmail . ' đã tồn tại trên hệ thống !';
                        return null;
                    }
                    $email = $rowEmail;
                } else {
                    $uniqueTimestamp = microtime(true);
                    $email = $uniqueTimestamp . '@tuhoctiengduc.vn';
                }

                // Xử lý nếu không có Mã SV
                $row[0] = trim($row[0]);
                $rowCode = $row[0];

                // Xử lý nếu k nhập ngày sinh hoặc ngày sinh k hợp lệ
                if ($row[7] !== null && $row[7] !== '') {
                    $excelDateCount = $row[7];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $birthday = Carbon::createFromFormat('m/d/Y', $formattedDate);
                    } else {
                        try {
                            $birthdayString = trim($row[7]);
                            $birthday = Carbon::createFromFormat('d/m/Y', $birthdayString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Sai định dạng ngày sinh ở học viên: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $birthday = Carbon::now()->format('Y-m-d');
                }
                // đổi giới tính về đúng định dang male, female
                if ($row[8] !== null && $row[8] !== '') {
                    $gender = trim(Str::slug($row[8]));
                    if ($gender == 'nam') {
                        $gender = 'male';
                    } elseif ($gender == 'nu') {
                        $gender = 'female';
                    } else {
                        $gender = Consts::GENDER['other'];
                    }
                } else {
                    $gender = Consts::GENDER['other'];
                }

                // Format lại loại hợp đồng
                $contract_type = '';
                if ($row[17] !== null && $row[17] !== '') {
                    if (in_array(trim($row[17]), Consts::CONTRACT_TYPE)) {
                        $contract_type = Consts::CONTRACT_TYPE[$row[17]];
                    }
                }

                $commonIds = collect($this->idsStudent)->intersect($row[0])->toArray();
                // check nếu Mã học viên đã tôn tại thì update
                if (!empty($commonIds)) {
                    // check CCCD là duy nhất
                    if ($row[14] !== null && $row[14] !== '') {
                        $check_cccd = Student::where('admin_code', '!=', $row[0])->whereJsonContains('admins.json_params->cccd', $row[14])->count();
                        if ($check_cccd > 0) {
                            $this->hasError = true;
                            $this->errorMessage = 'CCCD đã tồn tại';
                            // return null;
                        }
                    }

                    $student = Student::where('admin_code', $row[0])->first();
                    $student_name = $student->name;

                    if ($row[15] !== null && $row[15] !== '') {
                        $excelDateCount = $row[15];
                        if (is_numeric($excelDateCount)) {
                            $unixTimestamp = ($excelDateCount - 25569) * 86400;
                            $formattedDate = date('m/d/Y', $unixTimestamp);
                            $date_range = Carbon::createFromFormat('m/d/Y', $formattedDate);
                        } else {
                            try {
                                $date_rangeString = trim($row[15]);
                                $date_range = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                            } catch (Exception $e) {
                                $this->hasError = true;
                                $this->errorMessage = 'Sai định dạng ngày cấp ở học viên: ' . $row[0];
                                // return null;
                            }
                        }
                    } else {
                        $date_range = null;
                    }

                    // Check trạng thái học
                    if ($row[23] !== null && $row[23] !== '') {
                        $status_student = StatusStudent::where('name', 'like', '%' . $row[23] . '%')->first();
                        if ($status_student) {
                            $id_status_student = $status_student->id;
                        }
                    }
                    $arr_data['admission_id'] = $admission_id != '' ? $admission_id : $student->admission_id;
                    $arr_data['area_id'] = $area_id != '' ? $area_id : $student->area_id;
                    $arr_data['email'] = $email != null ? $email : ($student->email ?? null);
                    $arr_data['name'] = $row[5] != '' ? $row[5] : ($student->name ?? null);
                    $arr_data['phone'] = $row[4] != '' ? $row[4] : $student->phone;
                    $arr_data['birthday'] = $birthday;
                    $arr_data['gender'] = $gender;
                    $arr_data['version'] = $row[20] != '' ? $row[20] : ($student->version ?? null);
                    // $arr_data['status_study'] = $id_status_student ?? $student->status_study;     // Không cho sửa trạng thái học viên nữa
                    $arr_data['json_params'] = (array) $student->json_params;
                    $params_json['json_params']['address'] = $row[6] ?? ($student->json_params->address ?? null);
                    $params_json['json_params']['form_training'] = $row[9] ?? ($student->json_params->form_training ?? null);
                    $params_json['json_params']['dad_name'] = $row[10] ?? ($student->json_params->dad_name ?? null);
                    $params_json['json_params']['dad_phone'] = $row[11] ?? ($student->json_params->dad_phone ?? null);
                    $params_json['json_params']['mami_name'] = $row[12] ?? ($student->json_params->mami_name ?? null);
                    $params_json['json_params']['mami_phone'] = $row[13] ?? ($student->json_params->mami_phone ?? null);
                    $params_json['json_params']['cccd'] = $row[14] ?? ($student->json_params->cccd ?? null);
                    $params_json['json_params']['date_range'] = $date_range != null ? $date_range : ($student->json_params->date_range ?? null);
                    $params_json['json_params']['issued_by'] = $row[16] ?? ($student->json_params->issued_by ?? null);
                    $params_json['json_params']['contract_type'] = $contract_type  != '' ? $contract_type : ($student->json_params->contract_type ?? null);
                    $params_json['json_params']['contract_status'] = $row[18] ?? ($student->json_params->contract_status ?? null);
                    $params_json['json_params']['contract_performance_status'] = $row[19] ?? ($student->json_params->contract_performance_status ?? null);
                    $params_json['json_params']['field_id'] = count($arr_id_field) > 0 ? $arr_id_field : null;
                    $params_json['json_params']['note_cskh'] = $row[22] ?? ($student->json_params->note_cskh ?? null);
                    foreach ($params_json['json_params'] as $key => $value) {
                        $arr_data['json_params'][$key] = $value;
                    }

                    // Thêm lịch sử trạng thái cho học viên
                    // $history = HistoryService::addHistoryStatusStudy($student->id, $student->status_study, $id_status_student);
                    $student->update($arr_data);
                    $this->rowUpdate++;
                }
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
        $expectedHeaders = ['Mã SV', 'Mã CBTS', 'Mã KV', 'Email', 'SĐT', 'Họ và tên', 'Địa chỉ', 'Ngày sinh', 'Giới tính', 'Hình thức đào tạo', 'Họ tên cha', 'Sđt cha', 'Họ tên mẹ', 'Sđt mẹ', 'CCCD', 'Ngày cấp', 'Cấp bởi', 'Loại hợp đồng', 'Trạng thái hợp đồng', 'Tình trạng thực hiện hợp đồng', 'Version', 'Ngành nghề'];

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
        ];
        return $data_count;
    }
}
