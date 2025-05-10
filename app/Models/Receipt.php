<?php

namespace App\Models;

use App\Consts;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_receipt';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlReceipt($params = [])
    {
        $query = self::select('tb_receipt.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('receipt_name', 'like', '%' . $keyword . '%')
                        ->orWhere('receipt_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_receipt.student_id', $params['student_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_receipt.area_id', $params['area_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_receipt.status', $params['status']);
            })
            ->when(!empty($params['created_at']), function ($query) use ($params) {
                return $query->whereDate('tb_receipt.created_at', $params['created_at']);
            });
        if (!empty($params['order_by'])) {
            $query->orderBy('tb_receipt.' . $params['order_by'], 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->groupBy('tb_receipt.id');
    }

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
    public function cashier()
    {
        return $this->belongsTo(Admin::class, 'cashier_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function payment_cycle()
    {
        return $this->belongsTo(PaymentCycle::class, 'payment_cycle_id', 'id');
    }
    public function receiptDetail()
    {
        return $this->hasMany(ReceiptDetail::class, 'receipt_id');
    }

    public function prev_receipt_detail()
    {
        return $this->hasMany(ReceiptDetail::class, 'prev_receipt_id', 'receipt_id');
    }
    public function prev_receipt()
    {
        return $this->belongsTo(Receipt::class, 'prev_receipt_id');
    }
}
