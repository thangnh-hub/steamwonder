<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_service_detail';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlServiceCategory($params = [])
    {
        $query = self::select('tb_service_detail.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')
                                 ->orWhere('json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_service_detail.status', $params['status']);
        }) ;   
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_service_detail.' . $params['order_by'], 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_service_detail.id');
    }

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
