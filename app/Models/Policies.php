<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Policies extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_policies';

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

    public static function getSqlPolicies($params = [])
    {
        $query = Policies::select('tb_policies.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_policies.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_policies.code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_policies.area_id', $params['area_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_policies.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_policies.status', $params['status']);
        } else {
            $query->where('tb_policies.status', "!=", Consts::STATUS_DELETE);
        }
        $query->groupBy('tb_policies.id');
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
