<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_service_category';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlServiceCategory($params = [])
    {
        $query = self::select('tb_service_category.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%')
                                 ->orWhere('json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_service_category.status', $params['status']);
        }) ;   
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_service_category.' . $params['order_by'], 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_service_category.id');
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
