<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequestDetail extends Model
{
    protected $table = 'tb_payment_request_detail';

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

    public static function getSqlPaymentRequestDetail($params = [])
    {
        $query = PaymentRequestDetail::select('tb_payment_request_detail.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_payment_request_detail.tb_payment_request_detail', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_payment_request_detail.doc_number', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['date_arise']), function ($query) use ($params) {
                return $query->where('tb_payment_request_detail.date_arise', $params['date_arise']);
            });
        $query->groupBy('tb_payment_request_detail.id');
        return $query;
    }
    public function orderDetails()
    {
        return $this->hasMany(WareHouseOrderDetail::class, 'order_id');
    }
    public function payment_request()
    {
        return $this->belongsTo(PaymentRequest::class, 'payment_id');
    }
}
