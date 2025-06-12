<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealDishes extends Model
{
    protected $table = 'tb_meal_dishes';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlDishes($params = [])
    {
        $query = MealDishes::select('tb_meal_dishes.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')  ;
                });
            })
            ->when(!empty($params['dishes_type']), function ($query) use ($params) {
                return $query->where('dishes_type', $params['dishes_type']);
            })
            ->when(!empty($params['dishes_time']), function ($query) use ($params) {
                return $query->where('dishes_time', $params['dishes_time']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            })
            ;

        if (!empty($params['order_by'])) {
            $query->orderBy('tb_meal_dishes.' . $params['order_by'], 'asc');
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

    // hàm lấy định lượng món ăn
    public function getQuantitativeAttribute()
    {
        $raw = $this->json_params->quantitative ?? [];
        return collect($raw)->map(function ($item) {
            return json_decode(json_encode($item), true); // ép stdClass thành array
        })->toArray();
    }

    // lấy tên nguyên liệu
    public function getIngredientNames()
    {
        // Lấy danh sách ID nguyên liệu từ json_params->quantitative
        $ingredientIds = array_keys((array)($this->json_params->quantitative ?? []));

        if (empty($ingredientIds)) {
            return [];
        }

        // Truy vấn bảng nguyên liệu để lấy tên
        return MealIngredient::whereIn('id', $ingredientIds)
            ->pluck('name')
            ->toArray();
    }
    public function dishesplanningMenus()
    {
        return $this->hasMany(MealMenuDishes::class, 'dishes_id');
    }

    public function dishesdailyMenus()
    {
        return $this->hasMany(MealMenuDishesDaily::class, 'dishes_id');
    }
}
