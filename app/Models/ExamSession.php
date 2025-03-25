<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_exam_session';

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

    public static function getSqlExamSession($params = [])
    {
        
        $query = ExamSession::select('tb_exam_session.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_exam_session.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_exam_session.course_id', $params['course_id']);
            })
            ->when(!empty($params['day_exam']), function ($query) use ($params) {
                return $query->where('tb_exam_session.day_exam', $params['day_exam']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_exam_session.id', $params['id']);
            });
        return $query;
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
