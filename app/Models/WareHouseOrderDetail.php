<?php

namespace App\Models;

use App\Consts;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WareHouseOrderDetail extends Model
{
    protected $table = 'tb_warehouse_order_detail_products';
    protected $with = array('product');

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

    public static function getWareHouseOrderDetail($params)
    {
        $query = WareHouseOrderDetail::select('*')
            ->leftJoin('tb_warehouse_order_products', 'tb_warehouse_order_products.id', '=', 'tb_warehouse_order_detail_products.order_id')
            ->when(!empty($params['order_id']), function ($query) use ($params) {
                if (is_array($params['order_id'])) {
                    return $query->whereIn('tb_warehouse_order_detail_products.order_id', $params['order_id']);
                } else {
                    return $query->where('tb_warehouse_order_detail_products.order_id', $params['order_id']);
                }
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                if (is_array($params['status'])) {
                    return $query->whereIn('tb_warehouse_order_products.status', $params['status']);
                } else {
                    return $query->where('tb_warehouse_order_products.status', $params['status']);
                }
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['period']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.period', $params['period']);
            })
            ->when(!empty($params['warehouse_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouse_order_products.warehouse_id', $params['warehouse_permission']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.type', $params['type']);
            })
            ->when(!empty($params['department_request']), function ($query) use ($params) {
                if (is_array($params['department_request'])) {
                    return $query->whereIn('tb_warehouse_order_products.department_request', $params['department_request']);
                } else {
                    return $query->where('tb_warehouse_order_products.department_request', $params['department_request']);
                }
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.area_id', $params['area_id']);
            });
        return $query;
    }
    public function product()
    {
        return $this->belongsTo(WareHouseProduct::class, 'product_id', 'id');
    }
    public function departmentInfor()
    {
        return $this->belongsTo(WarehouseDepartment::class, 'department', 'id');
    }
    public function order_warehouse()
    {
        return $this->belongsTo(WareHouseOrder::class, 'order_id');
    }
}
