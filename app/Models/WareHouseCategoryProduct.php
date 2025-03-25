<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouseCategoryProduct extends Model
{
    protected $table = 'tb_warehouse_category_product';

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

    public static function getSqlWareHouseCategoryProduct($params = [])
    {

        $query = WareHouseCategoryProduct::select('tb_warehouse_category_product.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_warehouse_category_product.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_warehouse_category_product.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_category_product.status', $params['status']);
            });
        $query->groupBy('tb_warehouse_category_product.id');
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
    public function children()
    {
        return $this->hasMany(WareHouseCategoryProduct::class, 'category_parent');
    }

    public function parent()
    {
        return $this->belongsTo(WareHouseCategoryProduct::class, 'category_parent');
    }
}
