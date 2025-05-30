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

    public function getQuantitativeAttribute()
    {
        $raw = $this->json_params->quantitative ?? [];
        return collect($raw)->map(function ($item) {
            return json_decode(json_encode($item), true); // ép stdClass thành array
        })->toArray();
    }

}
