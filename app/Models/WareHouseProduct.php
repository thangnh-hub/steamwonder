<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouseProduct extends Model
{
    protected $table = 'tb_warehouse_product';
    protected $with = array('category_product');
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

    public static function getSqlWareHouseProduct($params = [])
    {
        $query = WareHouseProduct::select('tb_warehouse_product.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_product.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouse_product.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_warehouse_product.id', $params['id']);
                } else {
                    return $query->where('tb_warehouse_product.id', $params['id']);
                }
            })
            ->when(!empty($params['warehouse_category_id']), function ($query) use ($params) {
                if (is_array($params['warehouse_category_id'])) {
                    return $query->whereIn('tb_warehouse_product.warehouse_category_id', $params['warehouse_category_id']);
                } else {
                    return $query->where('tb_warehouse_product.warehouse_category_id', $params['warehouse_category_id']);
                }
            })
            ->when(!empty($params['warehouse_type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_product.warehouse_type', $params['warehouse_type']);
            })
            ->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_warehouse_product.id', $params['other_list']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_product.status', $params['status']);
            })
            ->when(!empty($params['gift']), function ($query) use ($params) {
                return $query->where('tb_warehouse_product.gift', $params['gift']);
            });
        $query->groupBy('tb_warehouse_product.id')->orderBy('tb_warehouse_product.id', 'desc');
        return $query;
    }

    public function category_product()
    {
        return $this->belongsTo(WareHouseCategoryProduct::class, 'warehouse_category_id', 'id');
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
