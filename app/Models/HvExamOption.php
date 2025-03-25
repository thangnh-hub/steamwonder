<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HvExamOption extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_hv_exam_options';

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
    public static function getSqlHvExamOption($params = [])
    {
        $query = HvExamOption::select('tb_hv_exam_options.*')
            ->when(!empty($params['id_level']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_options.id_level', $params['id_level']);
            })
            ->when(!empty($params['organization']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_options.organization', $params['organization']);
            })
            ->when(!empty($params['skill_test']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_options.skill_test', $params['skill_test']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_hv_exam_options.id', $params['id']);
            });
        $query->groupBy('tb_hv_exam_options.id');
        return $query;
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id');
    }
}
