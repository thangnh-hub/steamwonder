<?php

namespace App\Models;

use App\Consts;
use App\Components\Recusive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class StaffAdmission extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'admins';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['is_super_admin'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public static function getSqlStaffAdmission($params = [])
    {
        $query = StaffAdmission::select('admins.*', 'tb_roles.name AS role_name')
            ->selectRaw('GROUP_CONCAT("", b.id) sub_id')
            ->leftJoin('admins AS b', 'admins.id', '=', 'b.parent_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['parent_ids']), function ($query) use ($params) {
                $query->whereIn('admins.parent_id', $params['parent_ids']);
            })
            ->when(!empty($params['admin_type']), function ($query) use ($params) {
                $query->where('admins.admin_type', $params['admin_type']);
            })
            ->orderBy('admins.id', 'desc')
            ->when(!empty($params['arr_id']), function ($query) use ($params) {
                if (is_array($params['arr_id'])) {
                    return $query->whereIn('admins.id', $params['arr_id']);
                } else {
                    return $query->where('admins.id', $params['arr_id']);
                }
            });

        $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');

        if (!empty($params['parent_id'])) {
            $query->where('admins.parent_id', $params['parent_id']);
        }
        if (!empty($params['status'])) {
            $query->where('admins.status', $params['status']);
        } else {
            // $query->where('admins.status', "!=", Consts::STATUS_DELETE);
        }
        $query->groupBy('admins.id');
        return $query;
    }

    public function getAllStaffAdmissionChildren($id)
    {
        $recusive = new Recusive;
        $data = self::all();
        return  $recusive->staffAdmissionAllChild($data, $id);
    }
    public static function getAllStaffAdmissionChildrenAndSelf($id)
    {
        $recusive = new Recusive;
        $data = self::select('id', 'parent_id')->get();
        $arrID = $recusive->staffAdmissionAllChild($data, $id);
        array_unshift($arrID, $id);
        return  $arrID;
    }
}
