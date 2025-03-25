<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class CmsProduct extends Model
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

    public static function getsqlCmsProduct($params, $lang = 'vi', $isPaginate = false)
    {


        $query = CmsPost::selectRaw('tb_cms_posts.*')
            ->selectRaw('round(AVG(tb_reviews.rating),0) as rating')
            ->selectraw('count(tb_reviews.id) as count_review')
            ->leftJoin('tb_reviews', 'tb_cms_posts.id', '=', 'tb_reviews.id_product')

            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->selectRaw('count(tb_wishlist.id) AS wishlist')
                    ->leftJoin('tb_wishlist', function ($join)  use ($params) {
                        return $join->on('tb_wishlist.object_id', '=', 'tb_cms_posts.id')
                            ->where("tb_wishlist.user_id",  $params['user_id']);
                    });

            })
            ->when(!empty($params['keyword']), function ($query) use ($params, $lang) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword, $lang) {
                    return $where->where('tb_cms_posts.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_posts.json_params->title->' . $lang, 'like', '%' . $keyword . '%');
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

            ->when(!empty($params['arr_id']), function ($query) use ($params) {
                if (is_array($params['arr_id'])) {
                    return $query->whereIn('tb_cms_posts.id', $params['arr_id']);
                } else {
                    return $query->where('tb_cms_posts.id', $params['arr_id']);
                }
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.is_featured', $params['is_featured']);
            })
            ->when(!empty($params['related_post']), function ($query) use ($params) {
                return $query->whereIn('tb_cms_posts.id', $params['related_post']);
            })
            ->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_cms_posts.id', $params['other_list']);
            })
            ->when(!empty($params['tags']), function ($query) use ($params) {
                $query->whereJsonContains('tb_cms_posts.json_params->tags', $params['tags']);
            })
            ->when(!empty($params['price']), function ($query) use ($params) {
                $arr_price = explode(';', $params['price']);
                $query->where('tb_cms_posts.price', '>=', $arr_price[0]);
                $query->where('tb_cms_posts.price', '<=', $arr_price[1]);
            })
            ->when(!empty($params['size']), function ($query) use ($params) {
                $query->whereJsonContains('tb_cms_posts.json_params->paramater->size', $params['size']);
            })
            ->when(!empty($params['brand']), function ($query) use ($params) {
                $query->whereJsonContains('tb_cms_posts.json_params->paramater->brands', $params['brand']);
            });


        if (!empty($params['object_id'])) {
            $query->whereIn('tb_cms_posts.id', $params['object_id']);
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
        if (!empty($params[''])) {
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
