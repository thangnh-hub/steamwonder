<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealWarehouseIngredient extends Model
{
    protected $table = 'tb_meal_warehouse_ingredients';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];
    public static function getsqlMealWarehouseIngredient($params = [])
    {
        $query = MealWarehouseIngredient::select('tb_meal_warehouse_ingredients.*')
        ->leftJoin('tb_meal_ingredients', 'tb_meal_ingredients.id', '=', 'tb_meal_warehouse_ingredients.ingredient_id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_meal_ingredients.name', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_meal_warehouse_ingredients.status', $params['status']);
        })
        ->when(!empty($params['area_id']), function ($query) use ($params) {
            if (is_array($params['area_id'])) {
                return $query->whereIn('tb_meal_warehouse_ingredients.area_id', $params['area_id']);
            }
            return $query->where('tb_meal_warehouse_ingredients.area_id', $params['area_id']);
        })
        ->when(!empty($params['permisson_area_id']), function ($query) use ($params) {
            if (is_array($params['permisson_area_id'])) {
                return $query->whereIn('tb_meal_warehouse_ingredients.area_id', $params['permisson_area_id']);
            }
            return $query->where('tb_meal_warehouse_ingredients.area_id',  $params['permisson_area_id']);
        });
        if (!empty($params['order_by'])) {
        $query->orderBy('tb_meal_warehouse_ingredients.' . $params['order_by'], 'asc');
        } else {
        $query->orderBy('tb_meal_warehouse_ingredients.id', 'desc');
        }
        return $query;
    }
    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function ingredients()
    {
        return $this->belongsTo(MealIngredient::class, 'ingredient_id', 'id');
    }
}
