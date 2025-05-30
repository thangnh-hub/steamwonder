<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouseEntry extends Model
{
    protected $table = 'tb_warehouse_entry';

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

    public static function getSqlWareHouseWareHouseEntry($params = [])
    {
        $query = WareHouseEntry::select('tb_warehouse_entry.*')
            ->selectRaw('sum(tb_warehouse_entry_detail.quantity) AS total_product')
            ->selectRaw('sum(tb_warehouse_entry_detail.quantity_entry) AS total_product_entry')
            ->leftJoin('tb_warehouse_entry_detail', 'tb_warehouse_entry.id', '=', 'tb_warehouse_entry_detail.entry_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_entry.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouse_entry.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['period']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.period', $params['period']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.status', $params['status']);
            })
            ->when(!empty($params['order_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.order_id', $params['order_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.type', $params['type']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.warehouse_id', $params['warehouse_id']);
            })
            ->when(!empty($params['warehouse_id_deliver']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry.warehouse_id_deliver', $params['warehouse_id_deliver']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                $query->whereJsonContains('tb_warehouse_entry.json_params->list_class_id', $params['class_id']);
            })
            ->when(!empty($params['entry_permission']), function ($query) use ($params) {
                return $query->whereIn('tb_warehouse_entry.id', $params['entry_permission']);
            })
            ->when(!empty($params['confirmed']), function ($query) use ($params) {
                if($params['confirmed'] == 'null'){
                    return $query->whereNull('tb_warehouse_entry.confirmed');
                }
                else{
                    return $query->where('tb_warehouse_entry.confirmed',  $params['confirmed']);
                }
            });
        $query->groupBy('tb_warehouse_entry.id');
        $query->orderBy('tb_warehouse_entry.id', 'DESC');
        return $query;
    }

    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function nguoi_giao()
    {
        return $this->belongsTo(Admin::class, 'staff_deliver', 'id');
    }
    public function nguoi_nhan()
    {
        return $this->belongsTo(Admin::class, 'staff_entry', 'id');
    }
    public function nguoi_de_xuat()
    {
        return $this->belongsTo(Admin::class, 'staff_request', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id', 'id');
    }
    public function warehouse_deliver()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id_deliver', 'id');
    }
    public function order_warehouse()
    {
        return $this->belongsTo(WareHouseOrder::class, 'order_id');
    }
    public function entryDetails()
    {
        return $this->hasMany(WareHouseEntryDetail::class, 'entry_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function area_deliver()
    {
        return $this->belongsTo(Area::class, 'area_id_deliver');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_request', 'id');
    }
    public function staff()
    {
        return $this->belongsTo(Admin::class, 'staff_request', 'id');
    }
}
