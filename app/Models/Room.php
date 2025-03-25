<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_rooms';

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

    public static function getSqlRoom($params = [])
    {
        $query = Room::select('tb_rooms.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_rooms.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_rooms.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_rooms.area_id', $params['area_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_rooms.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_rooms.status', $params['status']);
        } else {
            $query->where('tb_rooms.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_rooms.id');

        return $query;
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
