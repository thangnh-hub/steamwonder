<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_holiday';

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

    public static function getSqlHoliday($params = [])
    {
        $query = Holiday::select('tb_holiday.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_holiday.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_holiday.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_holiday.id', $params['id']);
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                return $query->where('tb_holiday.date', $params['date']);
            })
            ->when(!empty($params['month']), function ($query) use ($params) {
                return $query->whereMonth('tb_holiday.date', $params['month']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_holiday.date', $params['year']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_holiday.id', '!=', $params['different_id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_holiday.status', $params['status']);
        } else {
            $query->where('tb_holiday.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_holiday.date');

        return $query;
    }
}
