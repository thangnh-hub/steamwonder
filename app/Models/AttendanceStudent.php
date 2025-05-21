<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceStudent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_class_attendance_student';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendances::class, 'class_attendance_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
}
