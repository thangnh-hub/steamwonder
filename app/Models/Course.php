<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_courses';

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

    public static function getSqlCourse($params = [])
    {

        $query = Course::select('tb_courses.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_courses.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_courses.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_courses.level_id', $params['level_id']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_courses.syllabus_id', $params['syllabus_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_courses.id', $params['id']);
            });

        if (!empty($params['status'])) {
            $query->where('tb_courses.status', $params['status']);
        } else {
            $query->where('tb_courses.status', "!=", Consts::STATUS_DELETE);
        }
        if (!empty($params['offline'])) {
            $query->leftJoin('tb_syllabuss', 'tb_syllabuss.id', '=', 'tb_courses.syllabus_id');
            $query->where('tb_syllabuss.type', '!=', 'elearning');
            $query->orWhereNull('tb_syllabuss.type');
        }
        if (!empty($params['type'])) {
            $query->where('tb_courses.type', $params['type']);
        } else {
            $query->where('tb_courses.type', "!=", Consts::SYLLABUS_TYPE['elearning']);
        }
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_courses.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_courses.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderBy('tb_courses.day_opening', 'desc');
            $query->orderBy('tb_courses.id', 'desc');
            // $query->orderByRaw('tb_courses.id desc');
        }
        if (!empty($params['user_id'])) {
            $query->leftJoin('tb_orders', 'tb_orders.json_params->courses_id', '=', 'tb_courses.id');
            $query->where('tb_orders.customer_id', $params['user_id']);
            $query->where('tb_orders.status', '1');
        }

        $query->groupBy('tb_courses.id');
        return $query;
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id', 'id');
    }
    public function classs()
    {
        return $this->hasMany(tbClass::class, 'course_id', 'id');
    }
}
