<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BlockContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_block_contents';

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

    public static function getSqlBlockContent($params = [])
    {
        $query = BlockContent::select('tb_block_contents.*')
            ->selectRaw('count(b.id) AS sub, tb_blocks.name AS block_name')
            ->leftJoin('tb_block_contents AS b', 'tb_block_contents.id', '=', 'b.parent_id')
            ->leftJoin('tb_blocks', 'tb_blocks.block_code', '=', 'tb_block_contents.block_code')
            ->groupBy('tb_block_contents.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_block_contents.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_block_contents.title', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['block_code']), function ($query) use ($params) {
                $query->where('tb_block_contents.block_code', '=', $params['block_code']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_blocks.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_block_contents.status', $params['status']);
        } else {
            $query->where('tb_block_contents.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_block_contents.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_block_contents.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_block_contents.id desc');
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
