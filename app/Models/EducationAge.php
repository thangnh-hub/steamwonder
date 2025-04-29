<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class EducationAge extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_education_ages';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlEducationAge($params = [])
    {
        $query = self::select('tb_education_ages.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%');
                });
            })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_education_ages.status', $params['status']);
        }) 
        ->when(!empty($params['area_id']), function ($query) use ($params) {
            return $query->where('tb_education_ages.area_id', $params['area_id']);
        }) ;   
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_education_ages.' . $params['order_by'], 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_education_ages.id');
    }

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
   
}
