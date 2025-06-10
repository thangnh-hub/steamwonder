<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptAdjustment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_receipt_adjustment';

    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlReceiptAdjustment($params)
    {
        $query = self::select('tb_receipt_adjustment.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $query->leftJoin('tb_students', 'tb_students.id', '=', 'tb_receipt_adjustment.student_id');
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_students.first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.student_code', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_students.nickname', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_receipt_adjustment.student_id', $params['student_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_receipt_adjustment.type', $params['type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_receipt_adjustment.status', $params['status']);
            });
        return $query->groupBy('tb_receipt_adjustment.id');
    }
    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }
    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }
    public function receiptOld()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id_old', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
