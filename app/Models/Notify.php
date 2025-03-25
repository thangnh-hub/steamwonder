<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $table = 'tb_notification';
    protected $casts = [
        'json_params' => 'object',
    ];
    protected $guarded = [];
    public static function getNotify($params)
    {
        $query = Notify::select('tb_notification.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_notification.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_notification.type', $params['type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_notification.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_notification.id', $params['id']);
            })
            ->when(!empty($params['id_object']), function ($query) use ($params) {
                return $query->whereIn('tb_notification.id_object', $params['id_object']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_notification.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_notification.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_notification.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_notification.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_notification.id DESC, tb_notification.status ASC');
        }

        $query->groupBy('tb_notification.id');

        return $query;
    }
}
