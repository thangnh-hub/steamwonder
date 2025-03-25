<?php

namespace App\Models;

use App\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DormitoryHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_dormitory_history';

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

    public static function getSqlDormitoryHistory($params = [])
    {
        $query = DormitoryHistory::select('tb_dormitory_history.*')
            ->leftJoin('tb_dormitory', 'tb_dormitory.id', '=', 'tb_dormitory_history.id_dormitory')
            // ->leftJoin('tb_dormitory_user', 'tb_dormitory_user.id_dormitory', '=', 'tb_dormitory_history.id_dormitory')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_dormitory.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id_dormitory']), function ($query) use ($params) {
                return $query->where('tb_dormitory_history.id_dormitory', $params['id_dormitory']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_dormitory.area_id', $params['area_id']);
            })
            ->when(!empty($params['list_area']), function ($query) use ($params) {
                return $query->whereIn('tb_dormitory.area_id', $params['list_area']);
            })
            ->when(!empty($params['gender']), function ($query) use ($params) {
                return $query->where('tb_dormitory.gender', $params['gender']);
            });
        if (!empty($params['from_month'])) {
            $query->where('tb_dormitory_history.time_in', '>=',Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
        }
        if (!empty($params['to_month'])) {
            $query->where('tb_dormitory_history.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
        }
        if (!empty($params['from_month']) && !empty($params['to_month'])) {
            $query->withCount([
                'dormitoryUsers as total_users_time_in' => function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_in', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                },
                'dormitoryUsers as total_users_time_out' => function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                }
            ]);
        } elseif (!empty($params['from_month'])) {
            $query->withCount([
                'dormitoryUsers as total_users_time_in' => function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_in', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                },
                'dormitoryUsers as total_users_time_out' => function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->where('tb_dormitory_user.time_out', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
                        $query->orwhereNull('tb_dormitory_user.time_out');
                    });
                }
            ]);
        } elseif (!empty($params['to_month'])) {
            $query->withCount([
                'dormitoryUsers as total_users_time_in' => function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_in', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                },
                'dormitoryUsers as total_users_time_out' => function ($query) use ($params) {
                    $query->where('tb_dormitory_user.time_out', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
                }
            ]);
        } else {
            $query->withCount([
                'dormitoryUsers as total_users_time_in' => function ($query) {
                    $query->whereNotNull('tb_dormitory_user.time_in');
                },
                'dormitoryUsers as total_users_time_out' => function ($query) {
                    $query->whereNotNull('tb_dormitory_user.time_out');
                }
            ]);
        };
        $query->orderBy('tb_dormitory_history.time_out', "ASC");
        return $query;
    }
    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class, 'id_dormitory', 'id');
    }
    public function dormitoryUsers()
    {
        return $this->hasMany(Dormitory_user::class, 'id_dormitory', 'id_dormitory');
    }
}
