<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'tb_payment_request';

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

    public static function getSqlPaymentRequest($params = [])
    {
        $query = PaymentRequest::select('tb_payment_request.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_payment_request.content', 'like', '%' . $keyword . '%');
                        // ->orWhere('tb_payment_request.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_payment_request.user_id', $params['user_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_payment_request.status', $params['status']);
            })
            ->when(!empty($params['dep_id']), function ($query) use ($params) {
                return $query->where('tb_payment_request.dep_id', $params['dep_id']);
            });
        $query->groupBy('tb_payment_request.id');
        return $query;
    }
    public function paymentDetails()
    {
        return $this->hasMany(PaymentRequestDetail::class, 'payment_id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(WarehouseDepartment::class, 'dep_id', 'id');
    }
    public function entry()
    {
        return $this->belongsTo(WareHouseEntry::class, 'entry_id', 'id');
    }
    public function approved_admin()
    {
        return $this->belongsTo(Admin::class, 'approved_id', 'id');
    }
    
}
