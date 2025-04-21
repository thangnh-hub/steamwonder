<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_relationships';

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
    public static function getSqlRelationship($params = [])
    {

        $query = Relationship::select('tb_relationships.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_relationships.first_name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_relationships.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_relationships.id', $params['id']);
            });
        
        $query->groupBy('tb_relationships.id');
        return $query;
    }
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}
