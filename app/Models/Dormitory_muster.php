<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dormitory_muster extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_dormitory_muster';

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
    public static function getSqlDormitoryMuster($params = [])
    {
        $query = Dormitory_muster::select('tb_dormitory_muster.*')
            ->leftJoin('tb_dormitory', 'tb_dormitory.id', '=', 'tb_dormitory_muster.id_dormitory')
            ->leftJoin('admins', 'admins.id', '=', 'tb_dormitory_muster.id_user')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id_dormitory']), function ($query) use ($params) {
                return $query->where('tb_dormitory_muster.id_dormitory', $params['id_dormitory']);
            })
            ->when(!empty($params['gender']), function ($query) use ($params) {
                return $query->where('admins.gender', $params['gender']);
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
            ->when(!empty($params['time_muster']), function ($query) use ($params) {
                return $query->where('tb_dormitory_muster.time_muster', $params['time_muster']);
            });

        if (!empty($params['status'])) {
            $query->where('tb_dormitory_muster.status', $params['status']);
        }

        $query->groupBy('tb_dormitory_muster.id');
        return $query;
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_user', 'id');
    }
}
