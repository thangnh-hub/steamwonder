<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_levels';

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

    public static function getSqlLevel($params = [])
    {
        
        $query = Level::select('tb_levels.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_levels.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_levels.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['subject_id']), function ($query) use ($params) {
                return $query->where('tb_levels.subject_id', $params['subject_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_levels.id', $params['id']);
            });
            
        
        $query->groupBy('tb_levels.id');
        return $query;
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
