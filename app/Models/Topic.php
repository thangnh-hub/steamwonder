<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_exam_topics';

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

    public static function getSqlTopic($params = [])
    {

        $query = Topic::select('tb_exam_topics.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_exam_topics.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_exam_topics.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_exam_topics.type', $params['type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_exam_topics.status', $params['status']);
            })
            ->when(!empty($params['list_topic']), function ($query) use ($params) {
                return $query->whereIn('tb_exam_topics.id', $params['list_topic']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_exam_topics.id', $params['id']);
            });
        return $query;
    }
}
