<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankAcademic extends Model
{
    protected $table = 'tb_ranked_academics';
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlRankAcademic($params = [])
    {
        $query = RankAcademic::select('tb_ranked_academics.*')
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_ranked_academics.id', $params['id']);
            })
            ->when(!empty($params['from_points']), function ($query) use ($params) {
                return $query->where('tb_ranked_academics.from_points', '>=', $params['from_points']);
            })
            ->when(!empty($params['to_points']), function ($query) use ($params) {
                return $query->where('tb_ranked_academics.to_points', '<=',$params['to_points']);
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_ranked_academics.level_id', $params['level_id']);
            })
            ->when(!empty($params['ranks']), function ($query) use ($params) {
                return $query->where('tb_ranked_academics.ranks', $params['ranks']);
            });
        $query->groupBy('tb_ranked_academics.id');
        $query->orderBy('tb_ranked_academics.level_id', 'asc');

        return $query;
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
}
