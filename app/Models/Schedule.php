<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_schedules';

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

    public static function getSqlSchedule($params = [])
    {
        $query = Schedule::select('tb_schedules.*')
            ->leftjoin('tb_attendances', 'tb_schedules.id', '=', 'tb_attendances.schedule_id')
            ->selectRaw('COUNT(CASE WHEN tb_attendances.status = "attendant" THEN 1 END) as total_attendant')
            ->selectRaw('COUNT(CASE WHEN tb_attendances.status = "absent" THEN 1 END) as total_absent')
            ->selectRaw('COUNT(CASE WHEN tb_attendances.status = "late" THEN 1 END) as total_late')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_schedules.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_schedules.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_schedules.class_id', $params['permission']);
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                $query->where('tb_schedules.date', $params['date']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                $query->where('tb_schedules.type', $params['type']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                $query->where('tb_schedules.area_id', $params['area_id']);
            })
            ->when(!empty($params['room_id']), function ($query) use ($params) {
                $query->where('tb_schedules.room_id', $params['room_id']);
            })
            ->when(!empty($params['period_id']), function ($query) use ($params) {
                $query->where('tb_schedules.period_id', $params['period_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_schedules.id', $params['id']);
            });
        if(isset($params['class_id'])){
            $query->where('tb_schedules.class_id', $params['class_id']);
        }
        if(isset($params['teacher_id'])){
            $query->where('tb_schedules.teacher_id', $params['teacher_id']);
        }
        if (!empty($params['from_date'])) {
            $query->where('tb_schedules.date', '>=', $params['from_date']);
        }
        if (!empty($params['to_date'])) {
            $query->where('tb_schedules.date', '<=', $params['to_date']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_schedules.status', $params['status']);
        } else {
            $query->where('tb_schedules.status', "!=", Consts::STATUS_DELETE);
        }
        $query->orderBy('date', 'ASC');
        $query->groupBy('tb_schedules.id');
        return $query;
    }

    public static function getReportSqlTimekeepingTeacher($params = [])
    {
        $query = Schedule::select('tb_schedules.*', DB::raw('GROUP_CONCAT(DISTINCT tb_schedules.class_id) as class_teacher'))
            ->groupBy('tb_schedules.teacher_id')
            ->leftjoin('admins', 'tb_schedules.teacher_id', '=', 'admins.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    $where->where('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['months']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', $params['months']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_schedules.date', $params['year']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_schedules.id', $params['id']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_schedules.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_schedules.status', $params['status']);
            });
        return $query;

    }
    public static function getReportSqlTimekeepingTeacherAll($params = [])
    {
        $query = Schedule::select('tb_schedules.*')
            ->leftjoin('admins', 'tb_schedules.teacher_id', '=', 'admins.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    $where->where('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['months']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', $params['months']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_schedules.date', $params['year']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_schedules.id', $params['id']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_schedules.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_schedules.status', $params['status']);
            });
        return $query;

    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'schedule_id', 'id');
    }
}
