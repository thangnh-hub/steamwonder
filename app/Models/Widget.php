<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_widgets';

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

    public static function getSqlWidget($params = [])
    {
        $query = Widget::select('tb_widgets.*')
            ->selectRaw('tb_widget_configs.name AS widget_name')
            ->leftJoin('tb_widget_configs', 'tb_widget_configs.widget_code', '=', 'tb_widgets.widget_code')
            ->groupBy('tb_widgets.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_widgets.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_widgets.title', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['widget_code']), function ($query) use ($params) {
                $query->where('tb_widgets.widget_code', '=', $params['widget_code']);
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                return $query->whereIn('tb_widgets.id', $params['list_id']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_widget_configs.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_widgets.status', $params['status']);
        } else {
            $query->where('tb_widgets.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_widgets.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_widgets.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_widgets.id desc');
        }

        return $query;
    }
}
