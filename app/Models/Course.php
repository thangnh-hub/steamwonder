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
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_courses.id', $params['id']);
            });

        if (!empty($params['status'])) {
            $query->where('tb_courses.status', $params['status']);
        } else {
            $query->where('tb_courses.status', "!=", Consts::STATUS_DELETE);
        }
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_courses.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_courses.' . $params['order_by'] . ' desc');
            }
        }

        $query->groupBy('tb_courses.id');
        return $query;
    }
    public function classs()
    {
        return $this->hasMany(tbClass::class, 'course_id', 'id');
    }
}
