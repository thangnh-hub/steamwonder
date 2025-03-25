<?php

namespace App\Models;

use App\Consts;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_wishlist';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public static function getsqlWhishlist($params)
    {
        $query = Wishlist::selectRaw('tb_wishlist.id as id_wishlist,tb_wishlist.user_id,tb_wishlist.object_id,tb_cms_posts.*')
            ->leftJoin('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_wishlist.object_id')
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_wishlist.user_id', $params['user_id']);
            });
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

        return $query;
    }
}
