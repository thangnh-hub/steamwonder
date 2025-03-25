<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_orders';

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
        'syllabuss_json_params' => 'object',
    ];
    public static function getOrderCourses($params)
    {
        $query = Order::select('tb_orders.*')
            ->selectRaw('admins.name AS admin_name, admins.email AS admin_email, admins.phone AS admin_phone')
            ->leftJoin('admins', 'admins.id', 'tb_orders.customer_id')
            ->selectRaw('tb_syllabuss.name AS syllabuss_name, tb_syllabuss.json_params as syllabuss_json_params')
            ->leftJoin('tb_syllabuss', 'tb_syllabuss.id', 'tb_orders.syllabus_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['customer_id']), function ($query) use ($params) {
                return $query->where('tb_orders.customer_id', $params['customer_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_orders.syllabus_id', $params['syllabus_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        $query->groupBy('tb_orders.id');

        return $query;
    }

    public static function getOrderDetail($params)
    {
        $query = OrderDetail::select('tb_order_details.*')
            ->selectRaw('tb_cms_posts.name AS post_title, tb_cms_posts.image, tb_cms_posts.image_thumb, tb_cms_posts.alias')
            ->join('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_order_details.item_id')
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_order_details.status', $params['status']);
            })
            ->when(!empty($params['order_id']), function ($query) use ($params) {
                return $query->where('tb_order_details.order_id', $params['order_id']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_order_details.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_order_details.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_order_details.id DESC');
        }

        return $query;
    }
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id', 'id');
    }
    public function lessons()
    {
        return $this->hasMany(LessonSylabu::class, 'syllabus_id', 'syllabus_id');
    }
  
}
