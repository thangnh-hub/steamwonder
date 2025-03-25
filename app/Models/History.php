<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'tb_history';
    protected $with = array('user','status_old','status_new');

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

    public function user()
    {
        return $this->belongsTo(Admin::class, 'student_id', 'id');
    }
    public function status_old()
    {
        return $this->belongsTo(StatusStudent::class, 'status_study_old', 'id');
    }
    public function status_new()
    {
        return $this->belongsTo(StatusStudent::class, 'status_study_new', 'id');
    }
    //lớp
    public function class_old()
    {
        return $this->belongsTo(tbClass::class, 'class_id_old', 'id');
    }
    public function class_new()
    {
        return $this->belongsTo(tbClass::class, 'class_id_new', 'id');
    }
    //trình độ
    public function level_new()
    {
        return $this->belongsTo(Level::class, 'levels_id_new', 'id');
    }
    public function level_old()
    {
        return $this->belongsTo(Level::class, 'levels_id_old', 'id');
    }
    //CHương trinh
    public function syllabuss_new()
    {
        return $this->belongsTo(Syllabus::class, 'syllabuss_id_new', 'id');
    }
    public function syllabuss_old()
    {
        return $this->belongsTo(Syllabus::class, 'syllabuss_id_old', 'id');
    }
    //Khóa học
    public function courses_new()
    {
        return $this->belongsTo(Course::class, 'courses_id_new', 'id');
    }
    public function courses_old()
    {
        return $this->belongsTo(Course::class, 'courses_id_old', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_id_update', 'id');
    }
}
