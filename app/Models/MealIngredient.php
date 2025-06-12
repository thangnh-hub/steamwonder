<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealIngredient extends Model
{
    protected $table = 'tb_meal_ingredients';
    protected $with = array('ingredientCategory', 'unitDefault');
    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlIngredient($params = [])
    {
        $query = MealIngredient::select('tb_meal_ingredients.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_meal_ingredients.name', 'like', '%' . $keyword . '%')  ;
                });
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_meal_ingredients.type', $params['type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_meal_ingredients.status', $params['status']);
            })
            ->when(!empty($params['ingredient_category_id']), function ($query) use ($params) {
                return $query->where('tb_meal_ingredients.ingredient_category_id', $params['ingredient_category_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_meal_ingredients.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                if (is_array($params['different_id'])) {
                    return $query->whereNotIn('tb_meal_ingredients.id', $params['different_id']);
                }
                return $query->where('tb_meal_ingredients.id', '!=', $params['different_id']);
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
    public function ingredientCategory()
    {
        return $this->belongsTo(MealIngredientCategory::class, 'ingredient_category_id');
    }

    public function unitDefault()
    {
        return $this->belongsTo(MealUnit::class, 'default_unit_id');
    }

    public function ingredientdailyMenus()
    {
        return $this->hasMany(MealMenuIngredientDaily::class, 'ingredient_id');
    }
    public function ingredientplanningMenus()
    {
        return $this->hasMany(MealMenuIngredient::class, 'ingredient_id');
    }
}
