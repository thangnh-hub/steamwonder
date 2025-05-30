<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouseInventory extends Model
{
    protected $table = 'tb_warehouse_inventory';
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

    public static function getSqlWareHouseInventory($params = [])
    {
        $query = WareHouseInventory::select('tb_warehouse_inventory.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_inventory.period', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.status', $params['status']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['department']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.department', $params['department']);
            })
            ->when(!empty($params['person_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.person_id', $params['person_id']);
            })
            ->when(!empty($params['period']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.period', $params['period']);
            })
            ->when(!empty($params['from_date']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.date_received', '>=', $params['from_date']);
            })
            ->when(!empty($params['to_date']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.date_received', '<=', $params['to_date']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_inventory.area_id', $params['area_id']);
            })
            ->when(!empty($params['warehouseinventory_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouse_inventory.warehouse_id', $params['warehouseinventory_permission']);
            });
        $query->groupBy('tb_warehouse_inventory.id');
        return $query;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function departments()
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }
    public function positions()
    {
        return $this->belongsTo(WareHousePosition::class, 'positions_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id', 'id');
    }
    public function person()
    {
        return $this->belongsTo(Admin::class, 'person_id', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
}
