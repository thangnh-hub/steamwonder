<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_decisions';

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

    public static function getSqlDecision($params = [])
    {
        $query = Decision::select('tb_decisions.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_decisions.code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_decisions.json_params->student->admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_decisions.json_params->student->name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_decisions.id', $params['id']);
            })
            ->when(!empty($params['from_date']), function ($query) use ($params) {
                return $query->where('tb_decisions.active_date', '>=', $params['from_date']);
            })
            ->when(!empty($params['to_date']), function ($query) use ($params) {
                return $query->where('tb_decisions.active_date', '<=', $params['to_date']);
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_decisions.json_params->student->id', $params['student_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_decisions.is_type', $params['is_type']);
            });


        $query->groupBy('tb_decisions.id');
        return $query;
    }
}
