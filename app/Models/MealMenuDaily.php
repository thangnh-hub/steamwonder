<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealMenuDaily extends Model
{
    protected $table = 'tb_meal_menu_daily';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlMenuDaily($params = [])
    {
        $query = MealMenuDaily::select('tb_meal_menu_daily.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')  
                        ->orWhere('code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('area_id', $params['area_id']);
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                return $query->where('date', $params['date']);
            })
            ->when(!empty($params['permisson_area_id']), function ($query) use ($params) {
                if (is_array($params['permisson_area_id'])) {
                    return $query->whereIn('tb_meal_menu_daily.area_id', $params['permisson_area_id']);
                }
                return $query->where('tb_meal_menu_daily.area_id',  $params['permisson_area_id']);
            })
            ->when(!empty($params['meal_age_id']), function ($query) use ($params) {
                return $query->where('meal_age_id', $params['meal_age_id']);
            });

        if (!empty($params['order_by'])) {
            $query->orderBy('tb_meal_menu_daily.' . $params['order_by'], 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }

    public function mealAge()
    {
        return $this->belongsTo(MealAges::class, 'meal_age_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function menuDishes()
    {
        return $this->hasMany(MealMenuDishesDaily::class, 'menu_daily_id', 'id');
    }
    public function menuIngredients()
    {
        return $this->hasMany(MealMenuIngredientDaily::class, 'menu_daily_id', 'id');
    }

}
