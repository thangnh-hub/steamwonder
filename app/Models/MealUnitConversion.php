<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealUnitConversion extends Model
{
   protected $table = 'tb_meal_unit_conversions';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];


    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }

    public function unitFrom()
    {
        return $this->belongsTo(MealUnit::class, 'from_unit_id', 'id');
    }
    public function unitTo()
    {
        return $this->belongsTo(MealUnit::class, 'to_unit_id', 'id');
    }
}
