<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_menus';

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

    /*
    Re-sort menu
     */
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

    public static function getSqlMenu($params = [])
    {
        $query = Menu::select('tb_menus.*')
            ->selectRaw('count(b.id) AS sub')
            ->leftJoin('tb_menus AS b', 'tb_menus.id', '=', 'b.parent_id')
            ->groupBy('tb_menus.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_menus.id', '=', $params['id']);
            })
            ->when(!empty($params['parent_id']), function ($query) use ($params) {
                $query->where('tb_menus.parent_id', '=', $params['parent_id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_menus.name', 'like', '%' . $params['keyword'] . '%');
                });
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_menus.status', $params['status']);
        } else {
            $query->where('tb_menus.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_menus.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_menus.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_menus.id desc');
        }

        return $query;
    }
}
