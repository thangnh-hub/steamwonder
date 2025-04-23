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

  public function department()
  {
    return $this->belongsTo(Department::class, 'department_id', 'id');
  }

  public function direct_manager()
  {
    return $this->belongsTo(Admin::class, 'parent_id', 'id');
  }

  public function area()
  {
    return $this->belongsTo(Area::class, 'area_id', 'id');
  }

  public function getRole()
  {
    return $this->belongsTo(Role::class, 'role', 'id');
  }

  // Lẩy ra danh sách menu_id và function_code của các quyền (role và json_params->role_extend) đi theo user (không tính config mở rộng)
  public function getPermissionAccessByRoleAttribute()
  {
    $arr_role_extend = $this->json_params->role_extend ?? [];
    array_push($arr_role_extend, $this->role);

    $role = Role::whereIn('id', $arr_role_extend)->get();
    // Extract access
    $extractAccess = fn($key) => $role->pluck("json_access.$key")
      ->filter()  // Loại bỏ null
      ->flatten() // Chuyển mảng lồng nhau thành 1 mảng phẳng
      ->unique()  // Loại bỏ giá trị trùng nhau
      ->values()  // Reset key index của mảng
      ->toArray(); // Chuyển về mảng thuần

    $access = (object) [
      'menu_id' => $extractAccess('menu_id'),
      'function_code' => $extractAccess('function_code'),
    ];

    return $access;
  }

  // Lấy danh sách function mở rộng theo account
  public function getFunctionExtendsAttribute()
  {
    $functionCodes = $this->json_params->function_code ?? [];

    return ModuleFunction::whereIn('function_code', $functionCodes)->get();
  }

  // Lấy danh sách các quyền mở rộng
  public function getRoleExtendsAttribute()
  {
    $roleExtends = $this->json_params->role_extend ?? [];

    return Role::whereIn('id', $roleExtends)->get();
  }

  // Lấy danh sách các khu vực được quản lý
  public function getAreaExtendsAttribute()
  {
    $areaIds = $this->json_params->area_id ?? [];

    return Area::whereIn('id', $areaIds)->get();
  }
}
