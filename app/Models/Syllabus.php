<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_syllabuss';

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

    public static function getSqlSyllabus($params = [])
    {

        $query = Syllabus::select('tb_syllabuss.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_syllabuss.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_syllabuss.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.level_id', $params['level_id']);
            })
            ->when(!empty($params['type_offline']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.type', '!=','elearning');
            })
            ->when(!empty($params['type_online']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.type', 'elearning');
            })
            ->when(!empty($params['is_flag']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.is_flag', $params['is_flag']);
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->whereJsonContains('tb_syllabuss.json_params->is_featured', $params['is_featured']);
            })

            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.id', $params['id']);
            });
        if (!empty($params['is_approve'])) {
            $query->where('tb_syllabuss.is_approve', $params['is_approve']);
        }
        if (!empty($params['type'])) {
            $query->where('tb_syllabuss.type', $params['type']);
        } else {
            $query->where('tb_syllabuss.type', "!=", Consts::SYLLABUS_TYPE['elearning']);
        }
        $query->groupBy('tb_syllabuss.id');
        return $query;
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
    public function lessons()
    {
        return $this->hasMany(LessonSylabu::class, 'syllabus_id', 'id');
    }
}
