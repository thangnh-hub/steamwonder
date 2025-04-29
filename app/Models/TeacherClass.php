<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_class_teacher';

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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
}
