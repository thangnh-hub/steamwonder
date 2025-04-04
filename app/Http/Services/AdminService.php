<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Admin;
use App\Models\Role;
use App\Models\StaffAdmission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AdminService
{
  /**
   * Set SQL to get admin user from table admins
   * @author: ThangNH
   * @param:
   * - $params: array value to filter
   * - $isPaginate: boolean to paginate results
   * */

  public static function getAdmins($params, $isPaginate = false)
  {
    $query = Admin::select('admins.*', 'tb_roles.name AS role_name')
      ->when(!empty($params['keyword']), function ($query) use ($params) {

        $keyword = $params['keyword'];

        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.email', 'like', '%' . $keyword . '%')
            ->orWhere('admins.name', 'like', '%' . $keyword . '%')
            ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%');
        });
      })
      ->when(!empty($params['area_id']), function ($query) use ($params) {
        return $query->where('admins.area_id', $params['area_id']);
      })
      ->when(!empty($params['role']), function ($query) use ($params) {
        return $query->where('admins.role', $params['role']);
      })
      ->when(!empty($params['status']), function ($query) use ($params) {
        return $query->where('admins.status', $params['status']);
      })
      ->when(!empty($params['department_id']), function ($query) use ($params) {
        return $query->where('admins.department_id', $params['department_id']);
      })
      ->orderBy('admins.id', 'desc');
    if (!empty($params['admin_type'])) {
      $query->where('admins.admin_type', $params['admin_type']);
    } else {
      $query->where('admins.admin_type', "!=", 'student');
    }
    $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');
    if ($isPaginate) {
      $limit = Arr::get($params, 'limit', Consts::DEFAULT_PAGINATE_LIMIT);

      return $query->paginate($limit);
    }

    return $query->get();
  }

  // Get all access menu admin by user role
  public static function getAccessMenu()
  {
    $query = DB::table('tb_admin_menus AS a')
      ->selectRaw('a.*, count(b.id) AS submenu')
      ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
      ->where('a.status', Consts::USER_STATUS['active'])
      ->groupBy('a.id')
      ->orderByRaw('a.status ASC, a.iorder ASC, a.id DESC');

    // Admin user is super admin
    if (Auth::guard('admin')->user() != null && !Auth::guard('admin')->user()->is_super_admin) {
      $permission = AdminService::getPermisionAccess();
      $query->whereIn('a.id', $permission->menu_id);
    }

    return $query->get();
  }

  public static function getPermisionAccess()
  {
    $admin = Auth::guard('admin')->user();
    $arr_role_extend = $admin->json_params->role_extend ?? [];
    array_push($arr_role_extend, $admin->role);

    $role = Role::whereIn('id', $arr_role_extend)->get();

    $extractAccess = fn($key) => $role->pluck("json_access.$key")
      ->filter()
      ->flatten()
      ->unique()
      ->values()
      ->toArray();

    // Lấy danh sách function_code từ role và admin
    $functionCodes = array_merge($extractAccess('function_code'), $admin->json_params->function_code ?? []);
    // Lấy danh sách menu_id từ role và admin
    $menuIds = array_merge($extractAccess('menu_id'), $admin->json_params->menu_id ?? []);

    // Loại bỏ trùng lặp và reset index
    $functionCodes = array_values(array_unique($functionCodes));
    $menuIds = array_values(array_unique($menuIds));

    $access = (object) [
      'menu_id' => $menuIds,
      'function_code' => $functionCodes,
    ];

    return $access;
  }
}
