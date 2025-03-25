<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Staff extends Model
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

    public static function getSqlStaff($params = [])
    {   
        $query = Staff::select('admins.*', 'tb_roles.name AS role_name')
            ->when(!empty($params['keyword']), function ($query) use ($params) {

                $keyword = $params['keyword'];

                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%');
                });
            })->when(!empty($params['direct_manager_id']), function ($query) use ($params) {
                $query->whereJsonContains('admins.json_params->direct_manager_id', $params['direct_manager_id']);
            })->orderBy('admins.id', 'desc');

        $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');
        $query->where('admins.admin_type', Consts::ADMIN_TYPE['staff']);

        if (!empty($params['status'])) {
            $query->where('admins.status', $params['status']);
        } else {
            $query->where('admins.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('admins.id');
        return $query;
    }
}
