<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealMenuDishesDaily extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_meal_menu_dishes_daily';
    
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

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }

    public function menu_daily()
    {
        return $this->belongsTo(MealMenuPlanning::class, 'menu_daily_id', 'id');
    }

    public function dishes()
    {
        return $this->belongsTo(MealDishes::class, 'dishes_id', 'id');
    }

}
