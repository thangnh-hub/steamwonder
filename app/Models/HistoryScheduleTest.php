<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class HistoryScheduleTest extends Model
{
    protected $table = 'tb_history_schedule_test';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlScheduleTestUser($params = [])
    {
        $query = HistoryScheduleTest::select('tb_history_schedule_test.*')
            ->selectRaw('tb_schedule_test.time,tb_schedule_test.is_type')
            ->leftJoin('tb_schedule_test', 'tb_schedule_test.id', '=', 'tb_history_schedule_test.id_schedule_test')
            ->when(!empty($params['id_user_action']), function ($query) use ($params) {
                return $query->where('tb_history_schedule_test.id_user_action', $params['id_user_action']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_schedule_test.is_type', $params['is_type']);
            });
        $query->orderBy('tb_schedule_test.time', 'ASC');
        return $query;
    }
    public static function getSqlHistory($params = [])
    {
        $query = HistoryScheduleTest::select('tb_history_schedule_test.*')
            ->selectRaw('tb_user_actions.json_params as param_action')
            ->leftJoin('tb_user_actions', 'tb_user_actions.id', '=', 'tb_history_schedule_test.id_user_action')
            ->selectRaw('tb_schedule_test.is_type as type_schedule_test')
            ->leftJoin('tb_schedule_test', 'tb_schedule_test.id', '=', 'tb_history_schedule_test.id_schedule_test')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_history_schedule_test.id', $params['id']);
            })
            ->when(!empty($params['id_schedule_test']), function ($query) use ($params) {
                $query->where('tb_history_schedule_test.id_schedule_test', $params['id_schedule_test']);
            });
        $query->orderBy('id', 'DESC');
        return $query;
    }
   
}
