<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_fields';

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

    public static function getSqlField($params = [])
    {
        $query = Field::select('tb_fields.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_fields.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_fields.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_fields.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_fields.status', $params['status']);
        } else {
            $query->where('tb_fields.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_fields.id');

        return $query;
    }
}
