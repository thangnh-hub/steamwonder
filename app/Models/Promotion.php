<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_promotions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlPromotion($params = [])
    {
        $query = Promotion::select('tb_promotions.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_promotions.promotion_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_promotions.promotion_name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_promotions.area_id', $params['area_id']);
            })
            ->when(!empty($params['promotion_type']), function ($query) use ($params) {
                return $query->where('tb_promotions.promotion_type', $params['promotion_type']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_promotions.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_promotions.status', $params['status']);
        }
        $query->groupBy('tb_promotions.id');
        return $query;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'id', 'service_id');
    }
    public function getServices()
    {
        return Service::whereIn('id', collect($this->json_params->services)->pluck('service_id'))->get();
    }
}
