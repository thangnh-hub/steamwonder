<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class   UserClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_user_class';
    protected $with = array('user', 'class');

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
    public static function getSqlUserClass($params = [])
    {
        $query = UserClass::select('tb_user_class.*', DB::raw('COUNT(*) as repeat_count'),'tb_areas.name AS area_name')
            ->leftJoin('admins', 'admins.id', '=', 'tb_user_class.user_id')
            ->leftJoin('tb_areas', 'admins.area_id', '=', 'tb_areas.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                return $query->whereIn('tb_user_class.user_id', $params['list_id']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_user_class.user_id', $params['user_id']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_user_class.class_id', $params['class_id']);
            })
            ->when(!empty($params['array_class_id']), function ($query) use ($params) {
                return $query->whereIn('tb_user_class.class_id', $params['array_class_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_user_class.status', $params['status']);
            })
            ->when(!empty($params['student_state']), function ($query) use ($params) {
                return $query->where('admins.state', $params['student_state']);
            });
        $query->groupBy('tb_user_class.user_id');

        return $query;
    }

    public static function getSqlUserClassDept($params = [])
    {
        $query = UserClass::select('tb_user_class.*', DB::raw('COUNT(*) as repeat_count'),'tb_areas.name AS area_name','tb_courses.name AS course_name')
            ->leftJoin('admins', 'admins.id', '=', 'tb_user_class.user_id')
            ->leftJoin('tb_areas', 'admins.area_id', '=', 'tb_areas.id')
            ->leftJoin('tb_classs', 'tb_user_class.class_id', '=', 'tb_classs.id')
            ->leftJoin('tb_courses', 'tb_classs.course_id', '=', 'tb_courses.id')
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                return $query->whereIn('tb_user_class.user_id', $params['list_id']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_user_class.class_id', $params['class_id']);
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_classs.course_id', $params['course_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_areas.id', $params['area_id']);
            })
            ->when(!empty($params['array_class_id']), function ($query) use ($params) {
                return $query->whereIn('tb_user_class.class_id', $params['array_class_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_user_class.status', $params['status']);
            })
            ->when(!empty($params['ketoan_xacnhan']), function ($query) use ($params) {
                return $query->where('admins.ketoan_xacnhan', $params['ketoan_xacnhan']);
            })
            ->when(!empty($params['version']), function ($query) use ($params) {
                return $query->where('admins.version', $params['version']);
            })
            ->when(!empty($params['contract_type']), function ($query) use ($params) {
                $query->whereJsonContains('admins.json_params->contract_type', $params['contract_type']);
            })
            ->when(!empty($params['student_state']), function ($query) use ($params) {
                return $query->where('admins.state', $params['student_state']);
            });
        $query->selectRaw('COUNT(tb_schedules.id) AS total_schedules');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.status = "dadiemdanh" THEN 1 END) AS total_attendance');
        $query->groupBy('tb_user_class.id');

        return $query;
    }
    public function user()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
}
