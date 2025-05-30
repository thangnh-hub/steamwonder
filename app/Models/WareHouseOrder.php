<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouseOrder extends Model
{
    protected $table = 'tb_warehouse_order_products';
    protected $with = array('warehouse','department','staff','orderDetails');
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

    public static function getSqlWareHouseOrder($params = [])
    {

        $query = WareHouseOrder::select('tb_warehouse_order_products.*')
            ->selectRaw('sum(tb_warehouse_order_detail_products.quantity) AS total_product')
            ->leftJoin('tb_warehouse_order_detail_products', 'tb_warehouse_order_products.id', '=', 'tb_warehouse_order_detail_products.order_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_order_products.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouse_order_products.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                if (is_array($params['status'])) {
                    return $query->whereIn('tb_warehouse_order_products.status', $params['status']);
                } else {
                    return $query->where('tb_warehouse_order_products.status', $params['status']);
                }
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.type', $params['type']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['period']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.period', $params['period']);
            })
            ->when(!empty($params['department_request']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.department_request', $params['department_request']);
            })
            ->when(!empty($params['staff_request']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.staff_request', $params['staff_request']);
            })
            ->when(!empty($params['order_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouse_order_products.id', $params['order_permission']);
            });
        $query->groupBy('tb_warehouse_order_products.id');
        $query->orderBy('tb_warehouse_order_products.id', 'DESC');
        return $query;
    }
    public static function reportWareHouseOrder($params = [])
    {

        $query = WareHouseOrder::select('tb_warehouse_order_products.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_order_products.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouse_category_product.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.status', $params['status']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.type', $params['type']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['period']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.period', $params['period']);
            })
            ->when(!empty($params['department_request']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.department_request', $params['department_request']);
            })
            ->when(!empty($params['staff_request']), function ($query) use ($params) {
                return $query->where('tb_warehouse_order_products.staff_request', $params['staff_request']);
            });
        $query->groupBy('tb_warehouse_order_products.id');
        return $query;
    }

    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function staff()
    {
        return $this->belongsTo(Admin::class, 'staff_request', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_request', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function admin_approved()
    {
        return $this->belongsTo(Admin::class, 'approved_id', 'id');
    }
    public function orderDetails()
    {
        return $this->hasMany(WareHouseOrderDetail::class, 'order_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function entry()
    {
        return $this->hasOne(WareHouseEntry::class, 'order_id', 'id');
    }
}
