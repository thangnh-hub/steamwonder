<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseDepartment extends Model
{
    protected $table = 'tb_department';

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

    public static function getSqlWareHouseDepartment($params = [])
    {
        $query = WarehouseDepartment::select('tb_department.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_department.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_department.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_department.id', $params['id']);
                } else {
                    return $query->where('tb_department.id', $params['id']);
                }
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_department.area_id', $params['area_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_department.status', $params['status']);
            })
            ;
        $query->groupBy('tb_department.id')->orderBy('tb_department.id','desc');
        return $query;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
}
