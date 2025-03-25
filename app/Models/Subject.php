<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_subjects';

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


    public static function getSqlSubject($params = [])
    {
        $query = Subject::select('tb_subjects.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_subjects.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_subjects.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_subjects.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_subjects.status', $params['status']);
        } else {
            $query->where('tb_subjects.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_subjects.id');
        return $query;
    }

}
