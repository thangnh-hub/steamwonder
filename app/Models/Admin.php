<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'birthday',
        'email',
        'password',
        'code',
        'role',
        'status',
        'admin_type',
        'teacher_type',
        'admin_code',
        'area_id',
        'parent_id',
        'department_id',
        'avatar',
        'json_params',
    ];
    protected $casts = [
        'json_params' => 'object',
    ];
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
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function dormitoryUsers()
    {
        return $this->hasMany(Dormitory_user::class, 'id_user');
    }

    public function department()
    {
        return $this->belongsTo(WarehouseDepartment::class, 'department_id', 'id');
    }

    public function direct_manager()
    {
        return $this->belongsTo(Admin::class, 'parent_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
