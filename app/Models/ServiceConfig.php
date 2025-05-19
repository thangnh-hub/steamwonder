<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class ServiceConfig extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_service_config';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlServiceConfig($params = [])
    {
        $query = ServiceConfig::select('tb_service_config.*')
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_service_config.id', $params['id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_service_config.area_id', $params['area_id']);
            });
        $query->groupBy('tb_service_config.id');
        return $query;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
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
