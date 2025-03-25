<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class CmsPost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_posts';

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

    public static function getSqlCmsPost($params, $lang = 'vi')
    {

        $query = CmsPost::selectRaw('tb_cms_posts.*')
            ->selectRaw('GROUP_CONCAT(tb_cms_taxonomys.name, " ") as taxonomy_name, GROUP_CONCAT(tb_cms_taxonomys.id) as list_taxonomy_id, tb_cms_taxonomys.alias as taxonomy_alias')
            ->leftJoin('tb_cms_relationships', 'tb_cms_relationships.object_id', '=', 'tb_cms_posts.id')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_relationships.taxonomy_id')
            ->selectraw('admins.name as admin_name')
            ->leftJoin('admins', 'tb_cms_posts.admin_created_id', '=', 'admins.id')

            ->when(!empty($params['keyword']), function ($query) use ($params, $lang) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword, $lang) {
                    return $where->where('tb_cms_posts.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_posts.json_params->name->' . $lang, 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.alias', $params['alias']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                if (is_array($params['taxonomy_id'])) {
                    return $query->whereIn('tb_cms_taxonomys.id', $params['taxonomy_id']);
                } else {
                    return $query->where('tb_cms_taxonomys.id', '=', $params['taxonomy_id']);
                }
            })
            ->when(!empty($params['list_id']), function ($query) use ($params) {
                if (is_array($params['list_id'])) {
                    return $query->whereIn('tb_cms_posts.id', $params['list_id']);
                } else {
                    return $query->where('tb_cms_posts.id', $params['list_id']);
                }
            })
            ->when(isset($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.is_featured', $params['is_featured']);
            })
            ->when(!empty($params['related_post']), function ($query) use ($params) {
                return $query->whereIn('tb_cms_posts.id', $params['related_post']);
            })
            ->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_cms_posts.id', $params['other_list']);
            })
            ->when(!empty($params['archives']), function ($query) use ($params) {
                $query->whereJsonContains('tb_cms_posts.json_params->paramater->archives', $params['archives']);
            })
            ->when(!empty($params['tags']), function ($query) use ($params) {
                $query->whereJsonContains('tb_cms_posts.json_params->tags', $params['tags']);
            });

        if (!empty($params['object_id'])) {
            $query->whereIn('tb_cms_posts.id', $params['object_id']);
        }
        if (!empty($params['next'])) {
            $query->where('tb_cms_posts.id', '>', $params['next']);
        }
        if (!empty($params['prev'])) {
            $query->where('tb_cms_posts.id', '<', $params['prev']);
        }
        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        $query->groupBy('tb_cms_posts.id');
        return $query;
    }
}
