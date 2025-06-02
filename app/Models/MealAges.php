<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealAges extends Model
{
    protected $table = 'tb_meal_ages';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlMealAge($params = [])
    {
        $query = MealAges::select('tb_meal_ages.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')  
                        ->orWhere('code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            });

        if (!empty($params['order_by'])) {
            $query->orderBy('tb_meal_ages.' . $params['order_by'], 'asc');
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
}
