<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Consts;

class LeaveRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_leave_requests';

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

    public static function getSqlLeaveRequest($params = [])
    {
        $query = LeaveRequest::select('tb_leave_requests.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $query->leftJoin('admins', 'admins.id', '=', 'tb_leave_requests.user_id');
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_leave_requests.user_id', $params['user_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_leave_requests.status', $params['status']);
            })
            ->orderBy('tb_leave_requests.id', 'DESC');
        return $query;
    }

    public function admins()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approver_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Admin::class, 'parent_id', 'id');
    }
}
