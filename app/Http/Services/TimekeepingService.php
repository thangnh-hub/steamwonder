<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Teacher;
use App\Models\tbClass;
use App\Models\Schedule;
use App\Models\TimekeepingTeacher;
use Illuminate\Support\Facades\DB;
use stdClass;
use Carbon\Carbon;

class TimekeepingService
{
    /**
     * Xử lý các thông tin báo cáo chấm công
     * @author: make by ThanhKudo and refactor by ThangNH
     * @created_at: 2024/11/01
     * @lastest_update_at: 2024/11/07
     */

    protected $teachers;
    protected $classs;
    protected $schedules;
    protected $bosungs;
    protected $nhanxets;
    protected $month;

    public function __construct($month, $teacher_id = null, $keyword = '')
    {
        $this->month  = $month;
        $this->teachers  = $this->getTeacherActive($month, $teacher_id, $keyword);
        $this->classs = $this->getClassByMonth($month, $teacher_id);
        $this->schedules = $this->getScheduleByClass();
        $this->bosungs = $this->getChamCongBoSung($month);
        $this->nhanxets = $this->getClassNhanXetInMonth($month);
    }

    /**
     * Lấy ra giáo viên có buổi học đã điểm danh trong tháng
     */
    public function getTeacherActive($month, $teacher_id, $keyword)
    {
        $monthYear = explode('-', $month);
        $params['year'] = $monthYear[0];
        $params['months'] = $monthYear[1];
        $params['teacher_id'] = $teacher_id;
        $params['status'] = Consts::SCHEDULE_STATUS["dadiemdanh"];
        $params['keyword'] = $keyword;
        $teachers = Teacher::getSqlTeacherInMonth($params)->get();

        return $teachers;
    }

    /**
     * Lấy ra các lớp có buổi học đã điểm danh trong tháng
     */
    public function getClassByMonth($month, $teacher_id)
    {
        $monthYear = explode('-', $month);
        $params['year'] = $monthYear[0];
        $params['months'] = $monthYear[1];
        $params['status'] = Consts::SCHEDULE_STATUS["dadiemdanh"];
        $params['teacher_id'] = $teacher_id;
        $classs = tbClass::getClassInMonthByTeacher($params)->get();

        return $classs;
    }

    /**
     * Lấy tất cả các buổi học theo danh sách lớp
     */
    public function getScheduleByClass()
    {
        $list_id = [];
        foreach ($this->classs as $class) {
            $list_id[] = $class->id;
        }
        $schedules = Schedule::whereIn('class_id', $list_id)->get();
        return $schedules;
    }

    /**
     * Lấy tất cả chấm công bổ sung trong tháng đã duyệt
     */
    public function getChamCongBoSung($month)
    {
        $monthYear = explode('-', $month);
        $params['year'] = $monthYear[0];
        $params['months'] = $monthYear[1];
        $params['approve'] = Consts::APPROVE['1'];
        $bosungs = TimekeepingTeacher::getSqlTimekeeping($params)->get();

        return $bosungs;
    }

    /**
     * Lấy nhận xét của lớp trong tháng
     */
    public function getClassNhanXetInMonth($month)
    {
        $year = explode('-', $month)[0];
        $month = explode('-', $month)[1];
        $params['type_class'] = 'lopchinh';
        /** Lấy danh sách nhận xét trong tháng */
        $subQuery = DB::table('tb_evaluations')
            ->select('class_id', 'from_date', 'to_date')
            ->whereMonth('tb_evaluations.from_date', $month)
            ->whereYear('tb_evaluations.from_date', $year)
            ->whereMonth('tb_evaluations.to_date', $month)
            ->whereYear('tb_evaluations.to_date', $year)
            ->groupBy('class_id', 'from_date', 'to_date');
        /** Main query tính toán tổng số lần nhận xét */
        $list_evaluations_class = DB::table(DB::raw("({$subQuery->toSql()}) as t"))
            ->mergeBindings($subQuery)
            ->leftJoin('tb_classs', 'tb_classs.id', '=', 't.class_id')
            ->select('t.class_id', 'tb_classs.name', DB::raw('COUNT(*) as total'))
            ->groupBy('t.class_id', 'tb_classs.name')
            ->get();

        return $list_evaluations_class;
    }

    public function thongke_buoihoc_theolop_trongthang($class_id, $month, $teacher_id)
    {
        $schedules_class = $this->schedules->where('class_id', $class_id);
        // Ngày bắt đầu
        $day_start = $schedules_class->min('date');
        // Ngày kết thúc
        $day_end = $schedules_class->max('date');
        // Ngày dự kiến
        $day_end_expected = $schedules_class->filter(function ($item) use ($class_id, $month) {
            return is_null($item->is_add_more);
        })->max('date');

        // Tổng số buổi đã học
        // Không đếm buổi học của GV nước ngoài
        $total_attendance = $schedules_class->filter(function ($item, $key) use ($class_id, $month) {
            return  $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"] && Carbon::parse($item->date)->format('Y-m') <= $month && $item->type == 'gv';
        })->count();
        // Tổng số buổi đã điểm danh trong tháng
        $attendant_in_month = $schedules_class->filter(function ($item) use ($class_id, $month, $teacher_id) {
            return  $item->teacher_id == $teacher_id && $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"] && Carbon::parse($item->date)->format('Y-m') == $month;
        })->count();
        // Tổng số buổi fulltime
        $total_fulltime = $schedules_class->filter(function ($item, $key) use ($class_id, $month, $teacher_id) {
            return  $item->teacher_id == $teacher_id && $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"] && $item->type_schedule == Consts::TEACHER_TYPE["fulltime"]  && Carbon::parse($item->date)->format('Y-m') == $month;
        })->count();
        // Tổng số buổi parttime
        $total_parttime = $schedules_class->filter(function ($item, $key) use ($class_id, $month, $teacher_id) {
            return  $item->teacher_id == $teacher_id && $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"] && $item->type_schedule == Consts::TEACHER_TYPE["parttime"]  && Carbon::parse($item->date)->format('Y-m') == $month;
        })->count();

        $thongke = new stdClass();
        $thongke->day_start = $day_start != '' ? date('d/m/Y', strtotime($day_start)) : '---';
        $thongke->day_end_expected = $day_end_expected != '' ? date('d/m/Y', strtotime($day_end_expected)) : '---';
        $thongke->day_end = $day_end != '' ? date('d/m/Y', strtotime($day_end)) : '---';
        $thongke->total_attendance = $total_attendance;
        $thongke->attendant_in_month = $attendant_in_month;
        $thongke->total_fulltime = $total_fulltime;
        $thongke->total_parttime = $total_parttime;

        return $thongke;
    }

    public function thongke_trangthai_chuyengiao($class_id)
    {
        $thongke = new stdClass();
        $thongke->text_transfer_status = '';

        foreach (Consts::TRANSFER_STATUS as $key => $value) {
            if ($key != 'hoc_chinh') {
                $thongke->$key = $this->schedules->filter(function ($item) use ($class_id, $value) {
                    return $item->class_id == $class_id && $item->transfer_status === $value && $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"] && Carbon::parse($item->date)->format('Y-m') <= $this->month;
                })->count();

                if ($thongke->$key > 0) {
                    $thongke->text_transfer_status .= __($value) . ': ' . $thongke->$key . '. ';
                }
            }
        }

        return $thongke;
    }

    // Lấy buổi học điểm danh muộn trong tháng của lớp
    public function buoihoc_diemdanh_muon_trongthang($class_id, $teacher_id)
    {
        $attendance_late_schedules = $this->schedules->filter(function ($item, $key) use ($class_id, $teacher_id) {
            if ($item->class_id == $class_id && $item->teacher_id == $teacher_id && Carbon::parse($item->date)->format('Y-m') == $this->month && $item->status == Consts::SCHEDULE_STATUS["dadiemdanh"]) {
                $attendanceDate = Carbon::parse($item->attendance_time);
                $scheduleDate = Carbon::parse($item->date);
                // lấy những buổi có ngày điểm danh quá 1 ngày so với lịch học
                return $scheduleDate->diffInDays($attendanceDate) > 1;
            };
        });
        return $attendance_late_schedules;
    }

    /** Lấy các ngày trong tháng */
    public function getDayInMonth($months)
    {
        $days = [];
        $year = explode('-', $months)[0];
        $month = explode('-', $months)[1];
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            $days[] = (object)[
                'date' => $startDate->toDateString(),
                'day' => $startDate->format('d'),
                'day_of_Week' => $startDate->dayOfWeek,
            ];
            $startDate->addDay();
        }
        return $days;
    }

    /** Tổng hợp ca học theo từng ngày */
    public function getScheduleInDay($teacher_id, $class_id, $months)
    {
        $list_day = $this->getDayInMonth($months);
        $schedules_by_day = $this->schedules
            ->where('teacher_id', $teacher_id)
            ->where('class_id', $class_id)
            ->where('status', 'dadiemdanh');

        foreach ($list_day as $val) {
            $schedules = $schedules_by_day->filter(function ($item, $key) use ($val) {
                return $item->date == $val->date;
            });
            $val->periods = [];
            $text_periods = '';
            if ($schedules) {
                foreach ($schedules as $schedule) {
                    $period = new stdClass();
                    $period->id = $schedule->period_id;
                    $period->status = $schedule->status;
                    $text_periods .= ' Ca ' . $schedule->period_id . ',';
                    $val->periods[] = $period;
                }
            }
            $val->text_periods = rtrim($text_periods, ',');
        }
        return $list_day;
    }


    // Show dữ liệu để trả ra view
    public function reportTimekeeping()
    {
        $teachers = $this->teachers;
        foreach ($teachers as $teacher) {
            // Lấy các lớp theo giáo viên
            $total_attendance_in_month = 0;

            $classs = $this->classs->filter(function ($item, $key) use ($teacher) {
                $list_teacher_schedule_id = explode(",", $item->list_teacher_schedule_id);
                return  in_array($teacher->id, $list_teacher_schedule_id);
            });

            $teacher->bosung = $this->bosungs->filter(function ($item, $key) use ($teacher) {
                return  $item->teacher_id == $teacher->id && Carbon::parse($item->date)->format('Y-m') == $this->month;
            })->count();


            // Bổ sung thông tin cho từng lớp
            $list_class_by_teacher = [];
            foreach ($classs as $class) {
                $item = new stdClass;
                $item->class = $class;
                $item->thongke_buoihoc = $this->thongke_buoihoc_theolop_trongthang($class->id, $this->month, $teacher->id);
                $item->thongke_trangthai = $this->thongke_trangthai_chuyengiao($class->id);
                $item->attendance_late = $this->buoihoc_diemdanh_muon_trongthang($class->id, $teacher->id)->count();

                $total_attendance_in_month += $item->thongke_buoihoc->attendant_in_month ?? 0;

                // Buổi học theo chương trình nếu lớp k setup
                $item->lesson_number = $class->syllabus->lesson ?? ($class->lesson_number ?? 0);

                // Đếm nhận xét
                $evaluations = $this->nhanxets->first(function ($item) use ($class) {
                    return $item->class_id == $class->id;
                });
                $item->total_evaluations = $evaluations->total ?? 0;

                array_push($list_class_by_teacher, $item);
            }

            $teacher->classs = $list_class_by_teacher;
            $teacher->total_attendance_in_month = $total_attendance_in_month;
        }

        return $teachers;
    }

    /** Lấy dữ liệu giáo viên theo dạng lịch để trả ra view */
    public function reportTimekeepingCalender()
    {
        $teachers = $this->teachers;
        foreach ($teachers as $teacher) {
            // Lấy các lớp theo giáo viên
            $total_attendance_in_month = 0;

            $classs = $this->classs->filter(function ($item, $key) use ($teacher) {
                $list_teacher_schedule_id = explode(",", $item->list_teacher_schedule_id);
                return  in_array($teacher->id, $list_teacher_schedule_id);
            });

            $teacher->bosung = $this->bosungs->filter(function ($item, $key) use ($teacher) {
                return  $item->teacher_id == $teacher->id && Carbon::parse($item->date)->format('Y-m') == $this->month;
            })->count();

            // Bổ sung thông tin cho từng lớp
            $list_class_by_teacher = [];
            foreach ($classs as $class) {
                $item = new stdClass;
                $item->class = $class;
                $item->thongke_buoihoc = $this->thongke_buoihoc_theolop_trongthang($class->id, $this->month, $teacher->id);
                $item->thongke_trangthai = $this->thongke_trangthai_chuyengiao($class->id);
                $item->attendance_late = $this->buoihoc_diemdanh_muon_trongthang($class->id, $teacher->id)->count();
                $total_attendance_in_month += $item->thongke_buoihoc->attendant_in_month ?? 0;
                // Buổi học theo chương trình nếu lớp k setup
                $item->lesson_number = $class->syllabus->lesson ?? ($class->lesson_number ?? 0);
                // Đếm nhận xét
                $evaluations = $this->nhanxets->first(function ($item) use ($class) {
                    return $item->class_id == $class->id;
                });
                $item->total_evaluations = $evaluations->total ?? 0;

                // Thêm ngày trong tháng vào class
                $item->calender = $this->getScheduleInDay($teacher->id, $class->id, $this->month);
                array_push($list_class_by_teacher, $item);
            }

            $teacher->classs = $list_class_by_teacher;
            $teacher->total_attendance_in_month = $total_attendance_in_month;
        }
        return $teachers;
    }
}
