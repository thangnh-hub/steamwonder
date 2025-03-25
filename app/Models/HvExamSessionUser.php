<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HvExamSessionUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_hv_exam_session_user';

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

    public static function getSqlHvExamSessionUser($params = [])
    {
        $query = HvExamSessionUser::select('tb_hv_exam_session_user.*')
            ->leftJoin('tb_hv_exam_session', 'tb_hv_exam_session.id', '=', 'tb_hv_exam_session_user.id_exam_session')
            ->leftJoin('admins', 'admins.id', '=', 'tb_hv_exam_session_user.id_user')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['day_exam']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.day_exam', $params['day_exam']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session_user.user_id', $params['user_id']);
            })
            ->when(!empty($params['id_exam_session']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session_user.id_exam_session', $params['id_exam_session']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session_user.status', $params['status']);
            })
            ->when(!empty($params['id_class']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session_user.id_class', $params['id_class']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session_user.id', $params['id']);
            });
        $query->groupBy('tb_hv_exam_session_user.id');
        return $query;
    }
    public function classs()
    {
        return $this->belongsTo(tbClass::class, 'id_class', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'id_user', 'id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id');
    }
    public function exam_session()
    {
        return $this->belongsTo(HvExamSession::class, 'id_exam_session', 'id');
    }

}
