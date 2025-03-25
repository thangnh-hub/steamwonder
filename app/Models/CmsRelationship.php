<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class CmsRelationship extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_relationships';

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

    public static function getCmsRelationship($params = [])
    {
        $query = CmsRelationship::select('tb_cms_relationships.*')
            ->leftJoin('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_cms_relationships.object_id')
            ->when(!empty($params['arr_id']), function ($query) use ($params) {
                if (is_array($params['arr_id'])) {
                    return $query->whereIn('tb_cms_relationships.taxonomy_id', $params['arr_id']);
                } else {
                    return $query->where('tb_cms_relationships.taxonomy_id', $params['arr_id']);
                }
            });
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_relationships.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_relationships.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_relationships.iorder ASC');
        }

        return $query;
    }
}
