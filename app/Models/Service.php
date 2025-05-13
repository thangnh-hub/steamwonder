<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_service';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlService($params = [])
    {
        $query = self::select('tb_service.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('name', 'like', '%' . $keyword . '%');
                });
            })
        ->when(!empty($params['different_id']), function ($query) use ($params) {
            if (is_array($params['different_id'])) {
                return $query->whereNotIn('tb_service.id', $params['different_id']);
            }
            return $query->where('tb_service.id', '!=', $params['different_id']);
        })
        ->when(!empty($params['service_category_id']), function ($query) use ($params) {
            return $query->where('tb_service.service_category_id', $params['service_category_id']);
        })
        ->when(!empty($params['education_program_id']), function ($query) use ($params) {
            return $query->where('tb_service.education_program_id', $params['education_program_id']);
        })
        ->when(!empty($params['education_age_id']), function ($query) use ($params) {
            return $query->where('tb_service.education_age_id', $params['education_age_id']);
        })
        ->when(!empty($params['is_attendance']), function ($query) use ($params) {
            return $query->where('tb_service.is_attendance', $params['is_attendance']);
        })
        ->when(!empty($params['is_default']), function ($query) use ($params) {
            return $query->where('tb_service.is_default', $params['is_default']);
        })
        ->when(!empty($params['service_type']), function ($query) use ($params) {
            return $query->where('tb_service.service_type', $params['service_type']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_service.status', $params['status']);
        })
        ->when(!empty($params['area_id']), function ($query) use ($params) {
            return $query->where('tb_service.area_id', $params['area_id']);
        }) ;
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_service.' . $params['order_by'], 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_service.id');
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

    public function service_category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id');
    }

    public function education_program()
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id', 'id');
    }

    public function education_age()
    {
        return $this->belongsTo(EducationAge::class, 'education_age_id', 'id');
    }

    public function serviceDetail()
    {
        return $this->hasMany(ServiceDetail::class, 'service_id');
    }

    public function studentWithServices()
    {
        return $this->hasMany(StudentService::class, 'service_id');
    }

}
