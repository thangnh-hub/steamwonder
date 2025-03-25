<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Consts;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'user_type',
        'user_code',
        'avatar',
        'json_params',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['is_super_user'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'json_params' => 'object',
        'json_profiles' => 'object',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function StatusStudent()
    {
        return $this->belongsTo(StatusStudent::class, 'status_study', 'id');
    }

    public static function getSqlUser($params = [])
    {
        $query = User::select('users.*')
            ->selectRaw('tb_fields.name as field_name, tb_fields.code as field_code')
            ->leftJoin('tb_fields', 'users.json_params->field_id', '=', 'tb_fields.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('users.name', 'like', '%' . $keyword . '%')
                        ->orWhere('users.json_params->contact', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%')
                        ->orWhere('users.phone', 'like', '%' . $keyword . '%')
                        ->orWhere('users.user_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['user_type']), function ($query) use ($params) {
                return $query->where('users.user_type', $params['user_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('users.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('users.id', $params['id']);
            })
            ->when(!empty($params['field_id']), function ($query) use ($params) {
                return $query->where('users.json_params->field_id', $params['field_id']);
            })
            ->when(!empty($params['target_search']), function ($query) use ($params) {
                return $query->whereJsonContains('users.json_params->target_search', $params['target_search']);
            });
        return $query;
    }
}
