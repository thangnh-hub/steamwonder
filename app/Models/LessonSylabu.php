<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class LessonSylabu extends Model
{
    protected $table = 'tb_lesson_sylabus';

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
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id', 'id');
    }
    public function quizs()
    {
        return $this->hasMany(Quiz::class,  'id_lesson', 'id');
    }
    public function grammars()
    {
        return $this->hasMany(LessonGrammar::class,  'id_lesson', 'id');
    }
    public function lesson_user()
    {
        if (Auth::guard('web')->check()) {
            return $this->hasMany(LessonUser::class, 'lesson_id', 'id');
        }
    }
}
