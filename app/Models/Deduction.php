<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_deductions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlDeduction($params = [])
    {

        $query = Deduction::select('tb_deductions.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_deductions.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_deductions.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_deductions.status', $params['status']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_policies.area_id', $params['area_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_policies.type', $params['type']);
            })
            ->when(!empty($params['condition_type']), function ($query) use ($params) {
                return $query->where('tb_policies.condition_type', $params['condition_type']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_deductions.id', $params['id']);
            });

        $query->groupBy('tb_deductions.id');
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
