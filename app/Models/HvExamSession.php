<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HvExamSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_hv_exam_session';

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
    public static function getSqlHvExamSession($params = [])
    {
        $query = HvExamSession::select('tb_hv_exam_session.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                $query->leftJoin('tb_classs', 'tb_classs.id', '=', 'tb_hv_exam_session.id_class');
                return $query->where('tb_classs.name', 'like', '%' . $keyword . '%');
            })
            ->when(!empty($params['day_exam']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.day_exam', $params['day_exam']);
            })
            ->when(!empty($params['id_invigilator']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.id_invigilator', $params['id_invigilator']);
            })
            ->when(!empty($params['id_grader_exam']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.id_grader_exam', $params['id_grader_exam']);
            })
            ->when(!empty($params['id_level']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.id_level', $params['id_level']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.is_type', $params['is_type']);
            })
            ->when(!empty($params['skill_test']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.skill_test', $params['skill_test']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_session.id', $params['id']);
            });
        $query->groupBy('tb_hv_exam_session.id');
        return $query;
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'id_class', 'id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id');
    }
    public function invigilator()
    {
        return $this->belongsTo(Admin::class, 'id_invigilator', 'id');
    }
    public function grader_exam()
    {
        return $this->belongsTo(Admin::class, 'id_grader_exam', 'id');
    }
}
