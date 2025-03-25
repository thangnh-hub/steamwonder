<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TeacherTest extends Model
{
    use Notifiable;
    protected $table = 'tb_teacher_tests';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];
    public static function getSqlTeacherTest($params = [])
    {   
        $query = TeacherTest::select('tb_teacher_tests.*')
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_teacher_tests.status', $params['status']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_teacher_tests.user_id', $params['user_id']);
            });
        $query->groupBy('tb_teacher_tests.id');
        return $query;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function info_question_curent()
    {
        return $this->belongsTo(TeacherQuiz::class, 'current_question', 'id');
    }
}
