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
use App\Http\Services\DormitoryService;
use Carbon\Carbon;
use Exception;

class DormitoryImport implements ToCollection
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
                // Check ngày thuê
                if ($row[3] !== null && $row[3] !== '') {
                    $excelDateCount = $row[3];
                    if (is_numeric($excelDateCount)) {
                        $unixTimestamp = ($excelDateCount - 25569) * 86400;
                        $formattedDate = date('m/d/Y', $unixTimestamp);
                        $date_range = Carbon::createFromFormat('m/d/Y', $formattedDate)->format('Y-m-d');
                    } else {
                        try {
                            $date_rangeString = trim($row[3]);
                            $date_range = Carbon::createFromFormat('d/m/Y', $date_rangeString)->format('Y-m-d');
                        } catch (Exception $e) {
                            $this->hasError = true;
                            $this->errorMessage = 'Invalid date of birth at admin code: ' . $row[0];
                            // return null;
                        }
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập ngày thuê KTX!');
                    continue;
                }

                // kiểm tra khu vực
                $area_id = strtoupper(trim($row[2]));
                if ($row[2] !== null && $row[2] !== '') {
                    $area = $this->isArea->first(function ($item, $key) use ($area_id) {
                        return $item->code == $area_id;
                    });
                    if (isset($area->id)) {
                        $area_id =  $area->id;
                    } else {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Mã khu vực ' . $row[2] . ' không tồn tại');
                        continue;
                    }
                } else {
                    $this->rowError++;
                    array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Cần nhập mã khu vực!');
                    continue;
                }

                // Lấy thông tin phòng học

                if ($row[0] !== null && $row[0] !== '') {
                    $params_dor['area_id'] = $area_id;
                    $params_dor['name'] = trim($row[0]);
                    $params_dor['don_nguyen'] = trim($row[1]);
                    $count_dormitory = Dormitory::getSqlDormitory($params_dor)->count();
                    if ($count_dormitory > 0) {
                        $this->rowError++;
                        array_push($this->arrErrorMessage, 'Vị trí ' . $key . ': Phòng ' . $row[0] . ($row[1] != '' ? ' đơn nguyên ' . $row[1] : '') . ' tại khu vực ' . $area->name . ' đã tồn tại!');
                        continue;
                    }
                    else {
                        // Xử lý thêm phòng và thêm lịch sử phòng
                        $params['area_id'] = $area_id;
                        $params['don_nguyen'] = trim($row[1]);
                        $params['name'] = trim($row[0]);
                        $params['slot'] = $row[4] ?? 0;
                        $params['time_start'] = $date_range;
                        $params['json_params']['address'] = $row[5] ?? '';
                        $dormitory = Dormitory::create($params);
                        $params_create = [
                            'id_dormitory' => $dormitory->id,
                            'time_in' => $date_range,
                        ];
                        DormitoryService::createDormitoryHistory($params_create);
                        $this->rowInsert++;
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
