<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_class_student';

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
    public static function getSqlStudentClass($params = [])
    {
        $query = StudentClass::select('tb_class_student.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $query->leftJoin('tb_students', 'tb_students.id', '=', 'tb_class_student.student_id');
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_students.first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.student_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.nickname', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_class_student.class_id', $params['class_id']);
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_class_student.student_id', $params['student_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_class_student.status', $params['status']);
            });
        if (!empty($params['area_id'])) {
            $query->leftJoin('tb_class', 'tb_class.id', '=', 'tb_class_student.class_id');
            $query->where('tb_class.area_id', $params['area_id']);
        }
        return $query;
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendances::class, 'class_id', 'class_id');
    }
}
