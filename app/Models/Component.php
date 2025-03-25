<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Component extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_components';

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

    public static function getSqlComponent($params = [])
    {
        $query = Component::select('tb_components.*')
            ->selectRaw('count(b.id) AS sub, tb_component_configs.name AS component_name')
            ->leftJoin('tb_components AS b', 'tb_components.id', '=', 'b.parent_id')
            ->leftJoin('tb_component_configs', 'tb_component_configs.component_code', '=', 'tb_components.component_code')
            ->groupBy('tb_components.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_components.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_components.title', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['component_code']), function ($query) use ($params) {
                $query->where('tb_components.component_code', '=', $params['component_code']);
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                return $query->whereIn('tb_components.id', $params['list_id']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_component_configs.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_components.status', $params['status']);
        } else {
            $query->where('tb_components.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_components.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_components.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_components.id desc');
        }

        return $query;
    }
    public function reSort(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $key => $menu) {
                $this->where('id', $key)->update($menu);
            }
            DB::commit();
            $return = ['error' => 0, 'msg' => ""];
        } catch (\Throwable $e) {
            DB::rollBack();
            $return = ['error' => 1, 'msg' => $e->getMessage()];
        }
        return $return;
    }
}
