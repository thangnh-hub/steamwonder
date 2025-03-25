<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHousePosition extends Model
{
    protected $table = 'tb_warehouse_positions';

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

    public static function getSqlWareHousePosition($params = [])
    {

        $query = WareHousePosition::select('tb_warehouse_positions.*')
            ->selectRaw('GROUP_CONCAT("", b.id) sub_id')
            ->leftJoin('tb_warehouse_positions AS b', 'tb_warehouse_positions.id', '=', 'b.parent_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_positions.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_positions.status', $params['status']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_positions.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['warehouse_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouse_positions.warehouse_id', $params['warehouse_permission']);
            });
        $query->orderBy('tb_warehouse_positions.warehouse_id');
        $query->groupBy('tb_warehouse_positions.id');

        return $query;
    }

    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(WareHousePosition::class, 'parent_id');
    }
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
    public function parent()
    {
        return $this->belongsTo(WareHousePosition::class, 'parent_id');
    }
    public function allParents()
    {
        return $this->parent()->with('allParents');
    }
}
