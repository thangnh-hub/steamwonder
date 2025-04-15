<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class DataCrm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_data_crms';

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

    public static function getSqlDataCrm($params = [])
    {

        $query = DataCrm::select('tb_data_crms.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_data_crms.first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_data_crms.last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_data_crms.phone', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_data_crms.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_data_crms.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['admission_id']), function ($query) use ($params) {
                return $query->where('tb_data_crms.admission_id', $params['admission_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_data_crms.area_id', $params['area_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_data_crms.id', $params['id']);
            });

        
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_data_crms.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_data_crms.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderBy('tb_data_crms.id', 'desc');
        }
        $query->groupBy('tb_data_crms.id');
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

    public function dataCrmLogs()
    {
        return $this->hasMany(DataCrmLog::class, 'data_crm_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admission()
    {
        return $this->belongsTo(Admin::class, 'admission_id', 'id');
    }
}
