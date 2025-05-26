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

    public static function getSqlUnit($params = [])
    {
        $query = MealUnitConversion::select('tb_meal_unit_conversions.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%') ;
                });
            })
            ->when(!empty($params['is_base']), function ($query) use ($params) {
                return $query->where('is_base', $params['is_base']);
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
}
