<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_user_jobs';

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

    public static function getSqlCmsJobs($params, $lang = 'vi')
    {

        $query = Jobs::selectRaw('tb_user_jobs.*')
            ->when(!empty($params['keyword']), function ($query) use ($params, $lang) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword, $lang) {
                    return $where->where('tb_user_jobs.job_title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_user_jobs.json_params->job_title->' . $lang, 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_user_jobs.id', $params['id']);
            })
            ->when(!empty($params['time_expired']), function ($query) use ($params) {
                return $query->where('tb_user_jobs.time_expired', ">=",$params['time_expired']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_user_jobs.id', '!=', $params['different_id']);
            });
        if (!empty($params['is_type'])) {
            $query->where('tb_user_jobs.is_type', $params['is_type']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_user_jobs.status', $params['status']);
        } else {
            $query->where('tb_user_jobs.status', "!=", Consts::STATUS_DELETE);
        };
        return $query;
    }
    public function maijor()
    {
        return $this->belongsTo(Major::class, 'maijor_code', 'code');
    }
    public function industry()
    {
        return $this->belongsTo(Field::class, 'industry_group', 'code');
    }
}
