<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonSylabuQuiz extends Model
{
    protected $table = 'tb_lesson_sylabus_quizs';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlLessonSylabuQuiz($params = [])
    {
        $query = LessonSylabuQuiz::select('tb_lesson_sylabus_quizs.*')
            ->selectRaw('GROUP_CONCAT("", b.id) sub_quiz_id')
            ->leftJoin('tb_lesson_sylabus_quizs AS b', 'tb_lesson_sylabus_quizs.id', '=', 'b.parent_id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_majors.id', $params['id']);
            })
            ->groupBy('tb_lesson_sylabus_quizs.id');
        return $query;
    }
}
