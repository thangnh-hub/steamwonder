<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_pages';

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

    public static function getSqlPage($params = [])
    {
        $query = Page::select('tb_pages.*')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_pages.id', '=', $params['id']);
            })
            ->when(!empty($params['route_name']), function ($query) use ($params) {
                $query->where('tb_pages.route_name', '=', $params['route_name']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                $query->where('tb_pages.alias', '=', $params['alias']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_pages.status', $params['status']);
        } else {
            $query->where('tb_pages.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_pages.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_pages.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_pages.iorder ASC, tb_pages.id desc');
        }

        return $query;
    }
}
