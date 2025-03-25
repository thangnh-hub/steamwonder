<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_majors';

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

    public static function getSqlMajor($params = [])
    {
        $query = Major::select('tb_majors.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_majors.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_majors.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_majors.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_majors.status', $params['status']);
        } else {
            $query->where('tb_majors.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_majors.id');

        return $query;
    }
}
