<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealWareHouseEntryDetail extends Model
{
    protected $table = 'tb_meal_warehouse_entry_detail';

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

    public static function getSqlWareHouseEntryDetail($params = [])
    {
        $query = WareHouseEntryDetail::select('tb_warehouse_entry_detail.*')
            ->leftJoin('tb_warehouse_entry', 'tb_warehouse_entry.id', '=', 'tb_warehouse_entry_detail.entry_id')
            
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry_detail.status', $params['status']);
            })

            ->when(!empty($params['entry_id']), function ($query) use ($params) {
                if (is_array($params['entry_id'])) {
                    return $query->whereIn('tb_warehouse_entry_detail.entry_id', $params['entry_id']);
                } else {
                    return $query->where('tb_warehouse_entry_detail.entry_id', $params['entry_id']);
                }
            })
            
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry_detail.type', $params['type']);
            })
            ->when(!empty($params['warehouse_id']), function ($query) use ($params) {
                return $query->where('tb_warehouse_entry_detail.warehouse_id', $params['warehouse_id']);
            });
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
    public function ingredient()
    {
        return $this->belongsTo(MealIngredient::class, 'ingredient_id', 'id');
    }
    public function entry()
    {
        return $this->belongsTo(MealWareHouseEntry::class, 'entry_id', 'id');
    }
}
