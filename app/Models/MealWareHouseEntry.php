<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealWareHouseEntry extends Model
{
    protected $table = 'tb_meal_warehouse_entry';

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
        $query = WareHouseEntry::select('tb_meal_warehouse_entry.*')
            ->selectRaw('sum(tb_meal_warehouse_entry_detail.quantity) AS total_product')
            ->leftJoin('tb_meal_warehouse_entry_detail', 'tb_meal_warehouse_entry.id', '=', 'tb_meal_warehouse_entry_detail.entry_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_meal_warehouse_entry.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_meal_warehouse_entry.code', 'like', '%' . $keyword . '%');
                });
            })
            
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_meal_warehouse_entry.status', $params['status']);
            })
           
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_meal_warehouse_entry.type', $params['type']);
            });
        $query->groupBy('tb_meal_warehouse_entry.id');
        $query->orderBy('tb_meal_warehouse_entry.id', 'DESC');
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
  
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
