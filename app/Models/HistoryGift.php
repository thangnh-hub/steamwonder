<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class HistoryGift extends Model
{
    protected $table = 'tb_history_gift';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlHistoryGift($params = [])
    {
        $query = HistoryGift::select('tb_history_gift.*')
            ->leftJoin('admins', 'admins.id', '=', 'tb_history_gift.student_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('admins.course_id', $params['course_id']);
            })
            ->when(!empty($params['product_id']), function ($query) use ($params) {
                return $query->where('tb_schedule_test.product_id', $params['product_id']);
            })
            ->when(!empty($params['student_id']), function ($query) use ($params) {
                return $query->where('tb_history_gift.student_id', $params['student_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_history_gift.status', $params['status']);
            });
        return $query;
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(WareHouseProduct::class, 'product_id', 'id');
    }
}
