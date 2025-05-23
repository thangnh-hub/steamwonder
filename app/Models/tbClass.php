<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_class';
    // protected $with = array('level', 'syllabus', 'course');

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

    public static function getSqlClass($params = [])
    {
        $query = tbClass::select('tb_class.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_class.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_class.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['permisson_area_id']), function ($query) use ($params) {
                if (is_array($params['permisson_area_id'])) {
                  return $query->whereIn('tb_class.area_id', $params['permisson_area_id']);
                }
                return $query->where('tb_class.area_id',  $params['permisson_area_id']);
              });
        if (isset($params['area_id']) && !empty($params['area_id'])) {
            $query->where('tb_class.area_id', $params['area_id']);
        }
        if (isset($params['room_id']) && !empty($params['room_id'])) {
            $query->whereIn('tb_class.id', $params['room_id']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_class.status', $params['status']);
        }

        return $query;
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function education_ages()
    {
        return $this->belongsTo(EducationAges::class, 'education_age_id', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function education_programs()
    {
        return $this->belongsTo(EducationPrograms::class, 'education_program_id', 'id');
    }

    // Lấy dánh sách học sinh hiện tại trong lớp
    public function currentStudents()
    {
        return $this->hasMany(Student::class, 'current_class_id', 'id');
    }

    public function students()
    {
        return $this
            ->belongsToMany(Student::class, StudentClass::class, 'class_id', 'student_id')
            ->withPivot('start_at', 'stop_at','status','type', 'json_params')
            ->withTimestamps();
    }

    // Relation với bảng teachers
    public function teacher()
    {
        return $this
            ->belongsToMany(Teacher::class, TeacherClass::class, 'class_id', 'teacher_id')
            ->withPivot('start_at', 'stop_at', 'status', 'is_teacher_main')
            ->withTimestamps();
    }
    public function mainTeacher()
    {
        return $this->hasOne(TeacherClass::class, 'class_id', 'id')
            ->where('is_teacher_main', 1)
            ->with('teacher');
    }
}
