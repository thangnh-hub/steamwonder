<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbParent extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_parents';

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

    public static function getSqlParent($params = [])
    {

        $query = tbParent::select('tb_parents.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_parents.first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_parents.last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_parents.phone', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_parents.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_parents.identity_card', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['admission_id']), function ($query) use ($params) {
                return $query->where('tb_parents.admission_id', $params['admission_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_parents.area_id', $params['area_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_parents.id', $params['id']);
            });


        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_parents.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_parents.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderBy('tb_parents.id', 'desc');
        }
        $query->groupBy('tb_parents.id');
        return $query;
    }
    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }

    public function dataCrm()
    {
        return $this->belongsTo(DataCrm::class, 'data_crm_id', 'id');
    }

    public function parentStudents()
    {
        return $this->hasMany(StudentParent::class, 'parent_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admission()
    {
        return $this->belongsTo(Admin::class, 'admission_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'member_id', 'id');
    }
}
