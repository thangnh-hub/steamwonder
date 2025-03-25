<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_user_cvs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
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
    public static function getSqlCv($params = [])
    {
        $query = Cv::select('tb_user_cvs.*')
            ->leftJoin('admins', 'admins.id', '=', 'tb_user_cvs.user_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_user_cvs.cv_title', 'like', '%' . $keyword . '%');
                });
            })
            
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                $query->where('tb_user_cvs.user_id', '=', $params['user_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                $query->where('tb_user_cvs.is_type', '=', $params['is_type']);
            })
            ->when(!empty($params['cv_code']), function ($query) use ($params) {
                $query->where('tb_user_cvs.cv_code', '=', $params['cv_code']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('tb_user_cvs.status', '=', $params['status']);
            })
            ->when(!empty($params['is_top']), function ($query) use ($params) {
                $query->where('tb_user_cvs.is_top', '=', $params['is_top']);
            })
            ->when(!empty($params['is_main']), function ($query) use ($params) {
                $query->where('tb_user_cvs.is_main', '=', $params['is_main']);
            })
            ->when(!empty($params['job_type_id']), function ($query) use ($params) {
                $query->where('tb_user_cvs.job_type_id', '=', $params['job_type_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_user_cvs.id', $params['id']);
            })
            ->when(!empty($params['other_id']), function ($query) use ($params) {
                $query->where('tb_user_cvs.id', "!=", $params['other_id']);
            })
            
            ->when(!empty($params['is_search']), function ($query) use ($params) {
                $query->where('tb_user_cvs.json_params->is_search', '=', $params['is_search'])
                    ->orWhere('tb_user_cvs.json_params->is_search', '=', null);
            });
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_user_cvs.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_user_cvs.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_user_cvs.is_main desc, tb_user_cvs.id desc');
        }

        $query->groupBy('tb_user_cvs.id');

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
