<?php

namespace App\Models;

use App\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dormitory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_dormitory';

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
    public static function getSqlDormitory($params = [])
    {
        $query = Dormitory::select('tb_dormitory.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_dormitory.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['gender']), function ($query) use ($params) {
                return $query->where('tb_dormitory.gender', $params['gender']);
            })
            ->when(!empty($params['name']), function ($query) use ($params) {
                return $query->where('tb_dormitory.name', 'like', '%' . $params['name'] . '%');
            })
            ->when(!empty($params['don_nguyen']), function ($query) use ($params) {
                return $query->where('tb_dormitory.don_nguyen', 'like', '%' . $params['don_nguyen'] . '%');
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                if (is_array($params['area_id'])) {
                    return $query->whereIn('tb_dormitory.area_id', $params['area_id']);
                } else {
                    return $query->where('tb_dormitory.area_id', $params['area_id']);
                }
            })
            ->when(!empty($params['list_area']), function ($query) use ($params) {
                return $query->whereIn('tb_dormitory.area_id', $params['list_area']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_dormitory.id', $params['id']);
            });

        if (!empty($params['from_month'])) {
            $query->where('tb_dormitory.time_start', '>=', Carbon::parse($params['from_month'])->firstOfMonth()->toDateString());
        }
        if (!empty($params['to_month'])) {
            $query->where('tb_dormitory.time_start', '<=', Carbon::parse($params['to_month'])->lastOfMonth()->toDateString());
        }

        if (!empty($params['status'])) {
            $query->where('tb_dormitory.status', $params['status']);
        }
        if (!empty($params['status_other'])) {
            $query->where('tb_dormitory.status', '!=', $params['status_other']);
        }
        if (!empty($params['status_other_deactive'])) {
            $query->where('tb_dormitory.status', '!=', Consts::STATUS_DORMITORY['deactive']);
        }
        // $query->orderByRaw("FIELD(status, 'empty', 'already', 'full','deactive') ASC")
        //     ->orderBy('tb_dormitory.time_start', "DESC");

        $query->groupBy('tb_dormitory.id');
        return $query;
    }

    public static function getSqlDormitoryArea($params = [])
    {
        $query  = Dormitory::select('area_id')
            ->selectRaw('COUNT(*) as total_rooms')
            ->selectRaw('SUM(slot) as total_slot')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->where('tb_dormitory.status', '!=', Consts::STATUS_DORMITORY['deactive'])
            ->when(!empty($params['list_area']), function ($query) use ($params) {
                return $query->whereIn('tb_dormitory.area_id', $params['list_area']);
            })
            ->groupBy('area_id');
        return $query;
    }
    public static function getSqlDormitoryReportMuster($params = [])
    {
        $query  = Dormitory::select('tb_dormitory.*')
            // ->leftJoin('tb_dormitory_muster', 'tb_dormitory.id', '=', 'tb_dormitory_muster.id_dormitory')
            ->leftJoin('tb_dormitory_muster', function ($join) use ($params) {
                $join->on('tb_dormitory.id', '=', 'tb_dormitory_muster.id_dormitory')
                    ->when(!empty($params['time_muster']), function ($query) use ($params) {
                        return $query->where(function ($q) use ($params) {
                            $q->where('tb_dormitory_muster.time_muster', $params['time_muster'])
                                ->orWhereNull('tb_dormitory_muster.time_muster');
                        });
                    });
            })
            ->selectRaw('SUM(CASE WHEN tb_dormitory_muster.status = "present" THEN 1 ELSE 0 END) as total_present')
            ->selectRaw('SUM(CASE WHEN tb_dormitory_muster.status = "absent" THEN 1 ELSE 0 END) as total_absent')

            ->when(!empty($params['id']), function ($query) use ($params) {

                return $query->where('tb_dormitory.id', $params['id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {

                return $query->where('tb_dormitory.area_id', $params['area_id']);
            })
            ->when(!empty($params['list_area']), function ($query) use ($params) {
                return $query->whereIn('tb_dormitory.area_id', $params['list_area']);
            });
        if (!empty($params['status_other'])) {
            $query->where('tb_dormitory.status', '!=', $params['status_other']);
        }
        if (!empty($params['status_other_deactive'])) {
            $query->where('tb_dormitory.status', '!=', Consts::STATUS_DORMITORY['deactive']);
        }
        $query->groupBy('tb_dormitory.id');
        return $query;
    }
    public function dormitoryUsers()
    {
        return $this->hasMany(Dormitory_user::class, 'id_dormitory');
    }
    public function dormitoryHistory()
    {
        return $this->hasMany(DormitoryHistory::class, 'id_dormitory');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
