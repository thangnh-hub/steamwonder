<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Dormitory;
use App\Models\Dormitory_user;
use App\Models\DormitoryHistory;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use stdClass;

class DormitoryService
{
    protected $list_area;
    protected $list_student;

    public function __construct()
    {
        $this->list_area  = $this->getAreaInDormitoryActive();
        $this->list_student  = $this->getStudentInDormitory();
    }

    public static function getData(array $conditions)
    {
        $query = DormitoryHistory::query();
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $query->where($key, $value[0], $value[1]);
            } else {
                $query->where($key, $value);
            }
        }
        return $query;
    }
    public static function updateData($params = [], $arr_value = [])
    {
        $query = self::getData($params)->orderBy('id', 'DESC')->first();
        $query->update($arr_value);
        return $query;
    }

    public static function createDormitoryHistory($params = [])
    {
        if ($params != '') {
            DormitoryHistory::create($params);
        }
        return;
    }

    public function checkRoomEent($id, $time)
    {
        $params['id_dormitory'] = $id;
        $check = false;
        $check_dormitory = DormitoryHistory::getSqlDormitoryHistory($params)->get();
        if ($check_dormitory) {
            foreach ($check_dormitory as $val) {
                if ($val->time_out == null || $val->time_out == '') {
                    if ($val->time_in <= Carbon::parse($time)->lastOfMonth()->toDateString()) {
                        $check = true;
                        break;
                    }
                } else {
                    if ($val->time_in < Carbon::parse($time)->lastOfMonth()->toDateString() && $val->time_out >= Carbon::parse($time)->lastOfMonth()->toDateString()) {
                        $check = true;
                        break;
                    }
                }
            }
        }
        return $check;
    }

    public static function updateStatusDormitory($dormitory, $type, $dormitory_user)
    {
        switch ($type) {
            case 'checkout':
                $dormitory->quantity = $dormitory->quantity - 1;
                $dormitory->status = Consts::STATUS_DORMITORY['already'];
                if ($dormitory->quantity <= 0) {
                    $dormitory->status = Consts::STATUS_DORMITORY['empty'];
                    $dormitory->gender = Consts::GENDER['other'];
                }
                $dormitory->save();
                break;
            case 'checkin':
                $dormitory->quantity = $dormitory->quantity + 1;
                // cập nhật lại trạng thái của phòng nếu chưa có
                if ($dormitory->quantity < $dormitory->slot) {
                    $dormitory->status = Consts::STATUS_DORMITORY['already'];
                } else {
                    // đổi trạng thái sang đầy
                    $dormitory->status = Consts::STATUS_DORMITORY['full'];
                }
                // nếu phòng chưa có giới tình thì cập nhật giới tình phòng theo giới tính hv
                if ($dormitory->gender == Consts::GENDER['other']) {
                    $dormitory->gender = $dormitory_user->student->gender != null ?  $dormitory_user->student->gender : Consts::GENDER['other'];
                } else {
                    // nếu phòng có giới tình r thì cập nhật giới tính học viên theo giới tính phòng
                    $student = Student::find($dormitory_user->id_user);
                    $student->gender = $dormitory->gender;
                    $student->save();
                }
                $dormitory->save();
                break;

            default:

                break;
        }
    }


    /** Lấy list khu vực theo các phòng có dữ liệu */
    public static function getAreaInDormitoryActive()
    {
        $params = [];
        if (Auth::guard('admin')->check()) {
            $id_user = Auth::guard('admin')->user()->id;
            $list_areaId = DataPermissionService::getPermisisonAreas($id_user);
            $params['list_area'] = $list_areaId;
        }
        $list_area = Dormitory::getSqlDormitoryArea($params)->get();
        return $list_area;
    }

    /** Lấy danh sách phòng theo khu vực */
    public function getDormitoryInArea($area_id = null)
    {
        // $params['status_other_deactive'] = true;
        $params['area_id'] = $area_id;
        $list_dormitory = Dormitory::getSqlDormitory($params)->get();
        return $list_dormitory;
    }
    /** Lấy tất cả học viên ở ktx */
    public function getStudentInDormitory()
    {
        $params['status_other_deactive'] = true;
        $list_student = Dormitory_user::getSqlDormitoryUser($params)->whereNotNull('id_dormitory')->get();
        return $list_student;
    }

    /** Lấy các tháng trong khoảng thời gian truyền vào */
    public function getMonthInTime($from_month, $to_month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $from_month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $to_month)->startOfMonth();
        $months = [];
        while ($startDate->lessThanOrEqualTo($endDate)) {
            $months[] = $startDate->format('Y-m');
            $startDate->addMonth();
        }
        return $months;
    }

    /** Gán từng tháng cho các khu vực */
    public function addMonthToArea($from_month, $to_month)
    {
        $months = $this->getMonthInTime($from_month, $to_month);
        foreach ($this->list_area as $area) {
            $area->month = new \stdClass();
            foreach ($months as $month) {
                $area->month->$month = new \stdClass();
            }
        }
    }

    /** Thống kê học viên đang ở  tới tháng truyền vào*/
    public function thongke_hocvien_dango_toithang($area, $month)
    {
        // lất tất cả học viên đang ở tới tháng, nam
        $endOfMonth = Carbon::parse($month)->endOfMonth();
        $already_male = $this->list_student
            ->filter(function ($item, $key) use ($area, $endOfMonth) {
                return $item->dormitory->area->id == $area &&
                    $item->status == 'already' &&
                    Carbon::parse($item->time_in)->lte($endOfMonth) &&
                    $item->user_gender == 'male';
            })
            ->count();
        // lất tất cả học viên đang ở tới tháng, nu
        $already_female = $this->list_student
            ->filter(function ($item, $key) use ($area, $endOfMonth) {
                return $item->dormitory->area->id == $area &&
                    $item->status == 'already' &&
                    Carbon::parse($item->time_in)->lte($endOfMonth) &&
                    $item->user_gender == 'female';
            })
            ->count();
        // lất tất cả học viên đang ở tới tháng, khác
        $already_other = $this->list_student
            ->filter(function ($item, $key) use ($area, $endOfMonth) {
                return $item->dormitory->area->id == $area &&
                    $item->status == 'already' &&
                    Carbon::parse($item->time_in)->lte($endOfMonth) &&
                    $item->user_gender == 'other';
            })
            ->count();
        $already = new stdClass();
        $already->already_male = $already_male;
        $already->already_female = $already_female;
        $already->already_other = $already_other;
        $already->already_total = $already_male + $already_female + $already_other;
        return $already;
    }

    /** Thống kê học viên vào trong tháng truyền vào*/
    public function thongke_hocvien_vao_trongthang($area, $month)
    {
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, nam
        $come_male = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_in)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_in)->year ==
                    Carbon::parse($month)->year &&
                    $item->user_gender == 'male';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, nữ
        $come_female = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_in)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_in)->year ==
                    Carbon::parse($month)->year &&
                    $item->user_gender == 'female';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, khac
        $come_other = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_in)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_in)->year ==
                    Carbon::parse($month)->year &&
                    $item->user_gender == 'other';
            })
            ->count();

        $come = new stdClass();
        $come->come_male = $come_male;
        $come->come_female = $come_female;
        $come->come_other = $come_other;
        $come->come_total = $come_male + $come_female + $come_other;
        return $come;
    }

    /** Thống kê học viên rời đi trong tháng truyền vào*/
    public function thongke_hocvien_roidi_trongthang($area, $month)
    {
        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, nam
        $leave_male = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_out)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_out)->year ==
                    Carbon::parse($month)->year &&
                    $item->status == 'leave' &&
                    $item->user_gender == 'male';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, nữ
        $leave_female = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_out)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_out)->year ==
                    Carbon::parse($month)->year &&
                    $item->status == 'leave' &&
                    $item->user_gender == 'female';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, khac
        $leave_other = $this->list_student
            ->filter(function ($item, $key) use ($area, $month) {
                return $item->dormitory->area->id == $area &&
                    Carbon::parse($item->time_out)->month ==
                    Carbon::parse($month)->month &&
                    Carbon::parse($item->time_out)->year ==
                    Carbon::parse($month)->year &&
                    $item->status == 'leave' &&
                    $item->user_gender == 'other';
            })
            ->count();

        $leave = new stdClass();
        $leave->leave_male = $leave_male;
        $leave->leave_female = $leave_female;
        $leave->leave_other = $leave_other;
        $leave->leave_total = $leave_male + $leave_female + $leave_other;
        return $leave;
    }

    /** Thống kê số phòng trống tới tháng truyền vào*/
    public function thongke_phong_trong_toithang($area, $month)
    {
        $dormitory = $this->getDormitoryInArea($area)->filter(function ($item, $key) use ($area, $month) {
            if ($this->checkRoomEent($item->id, $month) == true) {
                return $item->area_id == $area;
            }
        });
        // Tổng slot là nam
        $total_slot_male = $dormitory->filter(function ($item, $key) {
            return $item->gender == 'male';
        })->sum('slot');
        // Tổng slot là nữ
        $total_slot_female = $dormitory->filter(function ($item, $key) {
            return $item->gender == 'female';
        })->sum('slot');
        // Tổng slot là khác
        $total_slot_other = $dormitory->filter(function ($item, $key) {
            return $item->gender == 'other';
        })->sum('slot');

        $already = $this->thongke_hocvien_dango_toithang($area, $month);
        $empty = new stdClass();
        $male = $total_slot_male - $already->already_male;
        $female = $total_slot_female - $already->already_female;
        $other = $total_slot_other - $already->already_other;
        $empty->empty_male = $male;
        $empty->empty_female = $female;
        $empty->empty_other = $other;
        $empty->empty_total = $male + $female + $other;
        return $empty;
    }

    /** Tổng hợp dữ liệu để trả ra view báo cáo KTX chi tiết theo tháng*/
    public function reportDormitoryMonth($from_month, $to_month)
    {
        $list_area = $this->list_area;
        if ($from_month != '' && $to_month != '') {
            $this->addMonthToArea($from_month, $to_month);
            foreach ($list_area as $area) {
                $area_id = $area->area_id;
                foreach ($area->month as $month => $val_null) {
                    $already = $this->thongke_hocvien_dango_toithang($area_id, $month);
                    $come = $this->thongke_hocvien_vao_trongthang($area_id, $month);
                    $leave = $this->thongke_hocvien_roidi_trongthang($area_id, $month);
                    $empty = $this->thongke_phong_trong_toithang($area_id, $month);
                    $area->month->$month->already = $already;
                    $area->month->$month->come = $come;
                    $area->month->$month->leave = $leave;
                    $area->month->$month->empty = $empty;

                    //Đếm số phòng và chỗ theo tháng
                    $dormitory = $this->getDormitoryInArea($area_id)->filter(function ($item, $key) use ($area_id, $month) {
                        if ($this->checkRoomEent($item->id, $month) == true) {
                            return $item->area_id == $area_id;
                        }
                    });
                    $area->month->$month->total_dormitory = count($dormitory);
                    $area->month->$month->total_slot = $dormitory->sum(function ($item) {
                        return $item->slot ?? 0;
                    });
                }
            }
        }

        return $list_area;
    }

    /** Tổng hợp dữ liệu của báo cáo tổng hợp KTX */
    public function reportDormitory()
    {
        $list_area = $this->list_area;
        $month = Carbon::now()->format('Y-m');
        foreach ($list_area as $area) {
            $area_id = $area->area_id;
            $already = $this->thongke_hocvien_dango_toithang($area_id, $month);
            $empty = $this->thongke_phong_trong_toithang($area_id, $month);
            $area->already = $already;
            $area->empty = $empty;
        }
        return $list_area;
    }
}
