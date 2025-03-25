<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class EvaluationTeacher extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_evaluations';

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

    public static function getSqlEvaluationTeacher($params = [])
    {
        
        $query = EvaluationTeacher::select('tb_evaluations.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_evaluations.evaluation', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_evaluations.json_params->evaluation->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_evaluations.student_id', $params['student_id']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_evaluations.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_evaluations.id', $params['id']);
            });
        if (!empty($params['is_type'])) {
            $query->where('tb_evaluations.is_type', $params['is_type']);
        } 
        if (!empty($params['class_id'])) {
            $query->where('tb_evaluations.class_id', $params['class_id']);
        } else{
            $query->where('tb_evaluations.class_id', 0);
        }
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $query->where('tb_evaluations.from_date', '>=', $params['from_date']);
        }
        if (!empty($params['to_date'])) {
            $query->where('tb_evaluations.to_date', '<=', $params['to_date']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_evaluations.status', $params['status']);
        } else {
            $query->where('tb_evaluations.status', "!=", Consts::STATUS_DELETE);
        }
        $query->groupBy('tb_evaluations.id');
        return $query;
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}
