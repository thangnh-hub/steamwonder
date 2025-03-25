<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    protected $table = 'tb_user_actions';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];
    public static function getSqlUserAction($params = [])
    {
        $query = UserAction::select('tb_user_actions.*')
            ->selectRaw('GROUP_CONCAT(tb_history_schedule_test.id_schedule_test, " ") as id_schedule_test')
            ->leftJoin('tb_history_schedule_test', 'tb_history_schedule_test.id_user_action', '=', 'tb_user_actions.id')
            ->selectRaw('admins.name as admin_name')
            ->leftJoin('admins', 'admins.id', '=', 'tb_user_actions.action_user_id')
            ->selectRaw('tb_user_jobs.job_title as job_title')
            ->leftJoin('tb_user_jobs', 'tb_user_jobs.id', '=', 'tb_user_actions.job_id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_user_actions.id', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_user_actions.json_params->name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_user_actions.json_params->email', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['job_id']), function ($query) use ($params) {
                $query->where('tb_user_actions.job_id', $params['job_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                $query->where('tb_user_actions.action_type', $params['is_type']);
            })
            ->when(!empty($params['result_profile']), function ($query) use ($params) {
                $query->where('tb_user_actions.result_profile', $params['result_profile']);
            })
            ->when(!empty($params['result_interview']), function ($query) use ($params) {
                $query->where('tb_user_actions.result_interview', $params['result_interview']);
            })
            ->when(!empty($params['action_user_id']), function ($query) use ($params) {
                $query->where('tb_user_actions.action_user_id', $params['action_user_id']);
            })
            ->when(!empty($params['notnull']), function ($query) use ($params) {
                $query->whereNotNull($params['notnull']);
            });

        if (!empty($params['status'])) {
            $query->where('tb_user_actions.status', $params['status']);
        } else {
            $query->where('tb_user_actions.status', "!=", Consts::STATUS_DELETE);
        }

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_user_actions.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_user_actions.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_user_actions.id ASC');
        }
        $query->groupBy('tb_user_actions.id');
        return $query;
    }
}
