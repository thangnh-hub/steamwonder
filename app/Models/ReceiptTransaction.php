<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptTransaction extends Model
{
    protected $table = 'tb_receipt_transaction';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlReceiptTransaction($params)
    {
        $query = self::select('tb_receipt_transaction.*')
            ->leftJoin('tb_receipt', 'tb_receipt.id', '=', 'tb_receipt_transaction.receipt_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_receipt.receipt_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_receipt.receipt_name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['keyword_student']), function ($query) use ($params) {
                $query->leftJoin('tb_students', 'tb_students.id', '=', 'tb_receipt.student_id');
                $keyword_student = $params['keyword_student'];
                return $query->where(function ($where) use ($keyword_student) {
                    return $where->where('tb_students.first_name', 'like', '%' . $keyword_student . '%')
                        ->orWhere('tb_students.last_name', 'like', '%' . $keyword_student . '%')
                        ->orWhere('tb_students.student_code', 'like', '%' . $keyword_student . '%')
                        ->orWhere('tb_students.nickname', 'like', '%' . $keyword_student . '%');
                });
            })
            ->when(!empty($params['from_date']), function ($query) use ($params) {
                return $query->whereDate('tb_receipt_transaction.payment_date', '>=', $params['from_date']);
            })
            ->when(!empty($params['to_date']), function ($query) use ($params) {
                return $query->whereDate('tb_receipt_transaction.payment_date', '<=', $params['to_date']);
            });
        return $query->groupBy('tb_receipt_transaction.id');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }
    public function user_cashier()
    {
        return $this->belongsTo(Admin::class, 'cashier', 'id');
    }
}
