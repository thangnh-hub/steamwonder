<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_attendances';
    protected $with = array('class', 'student');

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlAttendance($params = [])
    {
        $query = Attendance::select('tb_attendances.*')->leftJoin('admins', 'tb_attendances.user_id', '=', 'admins.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['list_area_id']), function ($query) use ($params) {
                $query->whereIn('admins.area_id', $params['list_area_id']);
            })
            ->when(!empty($params['not_attendance']), function ($query) use ($params) {
                return $query->where('tb_attendances.status', '!=', Consts::ATTENDANCE_STATUS['attendant']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_attendances.user_id', $params['user_id']);
            })
            ->when(!empty($params['list_user']), function ($query) use ($params) {
                return $query->whereIn('tb_attendances.user_id', $params['list_user']);
            })
            ->when(!empty($params['schedule_id']), function ($query) use ($params) {
                return $query->where('tb_attendances.schedule_id', $params['schedule_id']);
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                return $query->whereDate('tb_attendances.date', $params['date']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_attendances.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_attendances.id', $params['id']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_attendances.date', $params['year']);
            })
            ->when(!empty($params['month']), function ($query) use ($params) {
                return $query->whereMonth('tb_attendances.date', $params['month']);
            });
        if (isset($params['class_id'])) {
            $query->where('tb_attendances.class_id', $params['class_id']);
        }

        $query->groupBy('tb_attendances.id');
        return $query;
    }

    public static function getReportSqlAttendanceByMonth($params = [])
    {
        $query = Attendance::select(
            'tb_attendances.user_id',
            'tb_schedules.area_id',
            'tb_attendances.schedule_id',
            DB::raw('YEAR(tb_attendances.date) AS year'),
            DB::raw('MONTH(tb_attendances.date) AS month'),
            DB::raw('SUM(CASE WHEN tb_attendances.status = "attendant" THEN 1 ELSE 0 END) AS attendant_count'),
            DB::raw('SUM(CASE WHEN tb_attendances.status = "late" THEN 1 ELSE 0 END) AS late_count'),
            DB::raw('SUM(CASE WHEN tb_attendances.status = "absent" THEN 1 ELSE 0 END) AS absent_count')
        )
            ->groupBy('tb_attendances.user_id', 'year', 'month')
            ->orderBy('tb_attendances.user_id')
            ->orderBy('year')
            ->orderBy('month')
            ->leftJoin('admins', 'tb_attendances.user_id', '=', 'admins.id')
            ->leftJoin('tb_schedules', 'tb_attendances.schedule_id', '=', 'tb_schedules.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['late_absent']), function ($query) {
                return $query->havingRaw('SUM(CASE WHEN tb_attendances.status = "late" THEN 1 ELSE 0 END) > 0')
                    ->orHavingRaw('SUM(CASE WHEN tb_attendances.status = "absent" THEN 1 ELSE 0 END) > 0');
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                if (is_array($params['area_id'])) {
                    $query->whereIn('tb_schedules.area_id', $params['area_id']);
                } else {
                    $query->where('tb_schedules.area_id', $params['area_id']);
                }
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_attendances.user_id', $params['user_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_attendances.status', $params['status']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_attendances.class_id', $params['class_id']);
            })
            ->when(!empty($params['months']), function ($query) use ($params) {
                return $query->whereMonth('tb_attendances.date', $params['months']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_attendances.date', $params['year']);
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                return $query->whereIn('tb_attendances.user_id', $params['list_id']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_attendances.class_id', $params['permission']);
            });
        return $query;
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }
}
