<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class CmsTaxonomy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_taxonomys';

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

    public static function getSqlTaxonomy($params = [],$lang = 'vi')
    {
        $query = CmsTaxonomy::select('tb_cms_taxonomys.*')

            ->selectRaw('GROUP_CONCAT("", b.id) sub_taxonomy_id')
            ->leftJoin('tb_cms_taxonomys AS b', 'tb_cms_taxonomys.id', '=', 'b.parent_id')


            ->when(!empty($params['keyword']), function ($query) use ($params , $lang) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword, $lang) {
                    return $where->where('tb_cms_taxonomys.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_taxonomys.json_params->title->' . $lang, 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['taxonomy']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.taxonomy', $params['taxonomy']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', $params['id']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.alias', $params['alias']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.is_featured', $params['is_featured']);
            });

        if (!empty($params['count']) && $params['count'] == true) {
            $query->selectraw('count(tb_cms_relationships.id) as count_post');
            $query->leftJoin('tb_cms_relationships', 'tb_cms_taxonomys.id', '=', 'tb_cms_relationships.taxonomy_id');
        };

        if (!empty($params['status'])) {
            $query->where('tb_cms_taxonomys.status', $params['status']);
        } else {
            $query->where('tb_cms_taxonomys.status', "!=", Consts::STATUS_DELETE);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_taxonomys.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_taxonomys.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_taxonomys.taxonomy, tb_cms_taxonomys.iorder ASC, tb_cms_taxonomys.id DESC');
        }

        $query->groupBy('tb_cms_taxonomys.id');

        return $query;
    }
}
