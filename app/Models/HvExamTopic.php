<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HvExamTopic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_hv_exam_topics';

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
    public static function getSqlHvExamTopics($params = [])
    {
        $query = HvExamTopic::select('tb_hv_exam_topics.*')
            ->when(!empty($params['id_level']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_topics.id_level', $params['id_level']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_topics.is_type', $params['is_type']);
            })
            ->when(!empty($params['skill_test']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_topics.skill_test', $params['skill_test']);
            })
            ->when(!empty($params['organization']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_topics.organization', $params['organization']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_topics.id', $params['id']);
            });
        $query->groupBy('tb_hv_exam_topics.id');
        return $query;
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id');
    }
    public function exam_questions()
    {
        return $this->hasMany(HvExamQuestions::class, 'id_topic', 'id');
    }
    public function exam_answers()
    {
        return $this->hasManyThrough(
            HvExamAnswers::class,
            HvExamQuestions::class,
            'id_topic',        // Khóa ngoại trên bảng questions
            'id_question',     // Khóa ngoại trên bảng answers
            'id',              // Khóa chính trên bảng topics
            'id'               // Khóa chính trên bảng questions
        );
    }
}
