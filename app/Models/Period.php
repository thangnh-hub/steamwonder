<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_periods';

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

    public static function getSqlPeriod($params = [])
    {
        $query = Period::select('tb_periods.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_periods.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_periods.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_periods.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_periods.status', $params['status']);
        } else {
            $query->where('tb_periods.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_periods.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_periods.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_periods.iorder ASC, tb_periods.id DESC');
        }
        $query->groupBy('tb_periods.id');

        return $query;
    }
}
