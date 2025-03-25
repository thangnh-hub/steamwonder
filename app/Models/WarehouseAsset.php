<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseAsset extends Model
{
    protected $table = 'tb_warehouses_asset';
    protected $with = array('product','warehouse','department','position','staff_entry_use');

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

    public static function getSqlWareHouseAsset($params = [])
    {
        $query = WarehouseAsset::select('tb_warehouses_asset.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouses_asset.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouses_asset.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.department_id', $params['department_id']);
            })
            ->when(!empty($params['position_id']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.position_id', $params['position_id']);
            })
            ->when(!empty($params['staff_entry']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.staff_entry', $params['staff_entry']);
            })
            ->when(!empty($params['list_position_id']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouses_asset.position_id', $params['list_position_id']);
            })
            ->when(!empty($params['product_id']), function ($query) use ($params) {
                if (is_array($params['product_id'])) {
                    return $query->whereIn('tb_warehouses_asset.product_id', $params['product_id']);
                } else {
                    return $query->where('tb_warehouses_asset.product_id', $params['product_id']);
                }
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_warehouses_asset.id', $params['id']);
                } else {
                    return $query->where('tb_warehouses_asset.id', $params['id']);
                }
            })
            ->when(!empty($params['warehouse_category_id']), function ($query) use ($params) {
                $query->leftJoin('tb_warehouse_product', 'tb_warehouse_product.id', '=', 'tb_warehouses_asset.product_id');
                $query->where('tb_warehouse_product.warehouse_category_id', $params['warehouse_category_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                $query->leftJoin('tb_warehouses', 'tb_warehouses.id', '=', 'tb_warehouses_asset.warehouse_id');
                $query->where('tb_warehouses.area_id', $params['area_id']);
            })
            ->when(!empty($params['warehouse_entry_id']) || !empty($params['warehouse_deliver_id']), function ($query) use ($params) {
                $query->leftJoin('tb_warehouse_entry', 'tb_warehouse_entry.id', '=', 'tb_warehouses_asset.entry_id');
            })
            ->when(!empty($params['warehouse_entry_id']), function ($query) use ($params) {
                $query->where('tb_warehouse_entry.id', $params['warehouse_entry_id']);
            })
            ->when(!empty($params['warehouse_deliver_id']), function ($query) use ($params) {
                $query->where('tb_warehouse_entry.id', $params['warehouse_deliver_id']);
            })
            ->when(!empty($params['product_type']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.product_type', $params['product_type']);
            })
            ->when(!empty($params['list_product_type']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouses_asset.product_type', $params['list_product_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.status', $params['status']);
            })
            ->when(!empty($params['state']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.state', $params['state']);
            })
            ->when(!empty($params['asset_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouses_asset.warehouse_id', $params['asset_permission']);
            })
            ->when(!empty($params['entry_id']), function ($query) use ($params) {
                return $query->where('tb_warehouses_asset.entry_id', $params['entry_id']);
            });
        $query->groupBy('tb_warehouses_asset.id')->orderBy('tb_warehouses_asset.product_id', 'desc');
        return $query;
    }

    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id', 'id');
    }
    public function warehouse_entry()
    {
        return $this->belongsTo(WareHouseEntry::class, 'entry_id', 'id');
    }
    public function warehouse_deliver()
    {
        return $this->belongsTo(WareHouseEntry::class, 'deliver_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(WareHouseProduct::class, 'product_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(WarehouseDepartment::class, 'department_id', 'id');
    }
    public function position()
    {
        return $this->belongsTo(WareHousePosition::class, 'position_id', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function staff_entry_use()
    {
        return $this->belongsTo(Admin::class, 'staff_entry', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
}
