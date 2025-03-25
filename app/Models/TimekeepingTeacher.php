<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimekeepingTeacher extends Model
{
    protected $table = 'tb_chamcongbosung';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];
    
    public static function getSqlTimekeeping($params = [])
    {

        $query = TimekeepingTeacher::select('tb_chamcongbosung.*')
            ->leftJoin('admins', 'admins.id', '=', 'tb_chamcongbosung.teacher_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                return $query->where('tb_chamcongbosung.date', $params['date']);
            })
            ->when(!empty($params['month']), function ($query) use ($params) {
                return $query->whereMonth('tb_chamcongbosung.date', $params['month']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_chamcongbosung.date', $params['year']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_chamcongbosung.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_chamcongbosung.type', $params['type']);
            })
            ->when(!empty($params['approve']), function ($query) use ($params) {
                return $query->where('tb_chamcongbosung.is_approve', $params['approve']);
            });

        $query->groupBy('tb_chamcongbosung.id');

        return $query;
    }
    public function teacher()
    {
        return $this->belongsTo(Admin::class, 'teacher_id', 'id');
    }
    public function periods()
    {
        return $this->belongsTo(Period::class, 'period', 'id');
    }
}
