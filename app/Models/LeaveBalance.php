<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_leave_balances';

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
    public static function getSqlLeaveBalance($params = [])
    {
        $query = LeaveBalance::select('tb_leave_balances.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $query->leftJoin('admins', 'admins.id', '=', 'tb_leave_balances.user_id');
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('tb_leave_balances.user_id', $params['user_id']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->where('tb_leave_balances.year', $params['year']);
            });
        return $query;
    }
    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
