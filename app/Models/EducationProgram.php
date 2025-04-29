<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class EducationProgram extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_education_programs';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlEducationProgram($params = [])
    {
        $query = self::select('tb_education_programs.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%');
                });
            })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_education_programs.status', $params['status']);
        }) 
        ->when(!empty($params['area_id']), function ($query) use ($params) {
            return $query->where('tb_education_programs.area_id', $params['area_id']);
        }) ;   
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_education_programs.' . $params['order_by'], 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_education_programs.id');
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
