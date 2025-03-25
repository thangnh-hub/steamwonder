<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_areas';

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

    public static function getSqlArea($params = [])
    {
        $query = Area::select('tb_areas.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_areas.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_areas.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['code']), function ($query) use ($params) {
                return $query->where('tb_areas.code', $params['code']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_areas.id', $params['id']);
                } else {
                    return $query->where('tb_areas.id', $params['id']);
                }
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_areas.id', '!=', $params['different_id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_areas.status', $params['status']);
        } else {
            $query->where('tb_areas.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_areas.id');

        return $query;
    }
    public function classs()
    {
        return $this->hasMany(tbClass::class, 'area_id', 'id');
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'area_id', 'id');
    }
}
