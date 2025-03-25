<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $casts = [
        'json_params' => 'object',
    ];
    protected $table = 'tb_certificate';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public static function getSqlCertificate($params = [])
    {
        $query = Certificate::select('tb_certificate.*')
            ->leftJoin('admins', 'tb_certificate.student_id','admins.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orwhereJsonContains('tb_certificate.json_params->student_name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                $year = $params['year'];
                return $query->where(function ($where) use ($year) {
                    return $where->whereYear('tb_certificate.day_score_listen', $year )
                        ->orWhereYear('tb_certificate.day_score_speak', $year )
                        ->orWhereYear('tb_certificate.day_score_read', $year )
                        ->orWhereYear('tb_certificate.day_score_write', $year );
                });
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_certificate.class_id', $params['class_id']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_certificate.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                return $query->whereIn('tb_certificate.student_id', $params['permission']);
            })

            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_certificate.student_id', $params['student_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_certificate.type', $params['type']);
            })
            ->when(!empty($params['total_skill']), function ($query) use ($params) {
                return $query->where('tb_certificate.total_skill', $params['total_skill']);
            });

        return $query;
    }
    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
    public function assistant_teacher()
    {
        return $this->belongsTo(Teacher::class, 'assistant_teacher_id', 'id');
    }
}
