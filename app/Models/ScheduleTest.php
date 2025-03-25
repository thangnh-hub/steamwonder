<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ScheduleTest extends Model
{
    protected $table = 'tb_schedule_test';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlScheduleTest($params = [])
    {
        $query = ScheduleTest::select('tb_schedule_test.*')
            ->selectRaw('admins.name as admin_name')
            ->leftJoin('admins', 'admins.id', '=', 'tb_schedule_test.id_admin_action')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_schedule_test.id', $params['id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                $query->where('tb_schedule_test.is_type', $params['is_type']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $query->where('tb_schedule_test.json_params->title', 'like', '%' . $params['keyword'] . '%');
            });
        if (!empty($params['from_date'])) {
            $query->where('tb_schedule_test.time', '>=', date('Y-m-d', strtotime($params['from_date'])));
        }

        if (!empty($params['to_date'])) {
            $query->where('tb_schedule_test.time', '<=', date('Y-m-d', strtotime($params['to_date'])));
        }
        $query->orderBy('time', 'ASC');
        return $query;
    }

    public static function getScheduleTestActive()
    {
        $query = ScheduleTest::select('tb_schedule_test.*')
            ->selectRaw('COUNT(tb_history_schedule_test.id) as total')
            ->leftJoin('tb_history_schedule_test', 'tb_schedule_test.id', '=', 'tb_history_schedule_test.id_schedule_test')
            ->where('tb_schedule_test.time', '>=', date('Y-m-d', time()))
            ->where('slot', '>', function ($query) {
                $query->select(DB::raw('COUNT(id)'))
                    ->from('tb_history_schedule_test')
                    ->whereNull('result')
                    ->whereColumn('id_schedule_test', 'tb_schedule_test.id');
            })
            ->groupBy('tb_schedule_test.id');
        return $query;
    }

    public static function getSqlScheduleTime($params = [])
    {
        $query = ScheduleTest::select('tb_schedule_test.time')
            ->leftJoin('tb_history_schedule_test', 'tb_history_schedule_test.id_schedule_test', '=', 'tb_schedule_test.id')
            ->when(!empty($params['schedule_test']), function ($query) use ($params) {
                return $query->whereIn('tb_schedule_test.id', $params['schedule_test']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_schedule_test.is_type', $params['is_type']);
            });
        $query->whereNull('tb_history_schedule_test.result');
        $query->orderBy('tb_history_schedule_test.id', 'ASC');
        return $query;
    }
}
