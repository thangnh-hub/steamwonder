<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealIngredientCategory extends Model
{
    protected $table = 'tb_meal_ingredients_category';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlIngredientCategory($params = [])
    {
        $query = MealIngredientCategory::select('tb_meal_ingredients_category.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')  ;
                });
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('type', $params['type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            })
            ;

        $query->orderBy('id', 'desc')->groupBy('id');

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

    public function mealIngredients()
{
    return $this->hasMany(MealIngredient::class, 'ingredient_category_id');
}
}
