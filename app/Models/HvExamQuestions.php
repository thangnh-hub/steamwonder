<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HvExamQuestions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_hv_exam_questions';

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
    public static function getSqlHvExamQuestions($params = [])
    {
        $query = HvExamQuestions::select('tb_hv_exam_questions.*')
            ->when(!empty($params['id_topic']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_questions.id_topic', $params['id_topic']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_questions.is_type', $params['is_type']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_questions.id', $params['id']);
            });
        $query->groupBy('tb_hv_exam_questions.id');
        return $query;
    }
    public function exam_answers()
    {
        return $this->hasMany(HvExamAnswers::class, 'id_question', 'id');
    }
}
