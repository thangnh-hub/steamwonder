<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Services\AdminService;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Area;
use App\Consts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Services\DataPermissionService;
use App\Http\Services\NotifyService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use App\Models\Language;
use App\Models\WarehouseDepartment;
use Illuminate\Support\Arr;

class AdminController extends Controller
{

  private $adminService;

  public function __construct()
  {
    parent::__construct();
    $this->adminService = new AdminService();
    $this->routeDefault  = 'admins';
    $this->viewPart = 'admin.pages.admins';
    $this->responseData['module_name'] = __('Admin user management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $params = $request->all();
    $admins = $this->adminService->getAdmins($params, true);
    $admins->getCollection()->transform(function ($item) {
      if (isset($item->json_params->area_id)) {
        $area_id = (array) $item->json_params->area_id;
        $list_area = Area::whereIn('id', $area_id)->get();
        $item['list_area'] = $list_area ?? null;
      }
      if (isset($item->json_params->role_extend)) {
        $role_extend = (array) $item->json_params->role_extend;
        $list_role = Role::whereIn('id', $role_extend)->get();
        $item['list_role'] = $list_role ?? null;
      }
      return $item;
    });
    $this->responseData['admins'] = $admins;
    $this->responseData['status'] = Consts::USER_STATUS;
    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $this->responseData['roles'] = $roles;
    $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
    $params_area['status'] = Consts::STATUS['active'];
    $area = Area::getsqlArea($params_area)->get();
    $this->responseData['area'] =  $area;
    $this->responseData['params'] = $params;
    $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();
    $this->responseData['departments'] =  WarehouseDepartment::get();
    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $this->responseData['roles'] = $roles;
    $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
    $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
    $params['status'] = Consts::STATUS['active'];
    $area = Area::getsqlArea($params)->get();
    $this->responseData['area'] =  $area;
    $this->responseData['my_info'] = Auth::guard('admin')->user()->id;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['gender'] = Consts::GENDER;
    $this->responseData['departments'] =  WarehouseDepartment::get();
    $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();

    return $this->responseView($this->viewPart . '.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $admin = Auth::guard('admin')->user();
    $request->validate([
      'name' => 'required',
      'email' => "required|email|max:255|unique:admins",
      'password' => "required|min:8|max:255",
    ]);

    $params = $request->only([
      'email',
      'phone',
      'birthday',
      'name',
      'role',
      'teacher_type',
      'status',
      'password',
      'avatar',
      'area_id',
      'parent_id',
      'admin_type',
      'admin_code',
      'department_id',
    ]);

    switch ($params['admin_type']) {
      case Consts::ADMIN_TYPE['staff']:
        // Find the last admin code
        $lastAdmin = Admin::orderBy('id', 'desc')->first();
        $lastAdminCode = $lastAdmin->id ?? 0;
        // Extract the numeric part and increment it
        $numericPart = (int)$lastAdminCode;
        // Calculate the number of digits required for the numeric part
        $numDigits = max(4, strlen((string)$numericPart));
        // Add one to the numeric part
        $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
        if (!isset($params['admin_code']) || $params['admin_code'] == null || $params['admin_code'] == '') {
          $params['admin_code'] = 'CB' . $newNumericPart;
        }
        $params['admin_type'] = Consts::ADMIN_TYPE['staff'];
        break;

      case Consts::ADMIN_TYPE['teacher']:
        // Find the last admin code
        $lastAdmin = Teacher::orderBy('id', 'desc')->first();
        $lastAdminCode = $lastAdmin->id ?? 0;
        // Extract the numeric part and increment it
        $numericPart = (int)$lastAdminCode;
        // Calculate the number of digits required for the numeric part
        $numDigits = max(4, strlen((string)$numericPart));
        // Add one to the numeric part
        $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
        if (!isset($params['admin_code']) || $params['admin_code'] == null || $params['admin_code'] == '') {
          $params['admin_code'] = 'GV' . $newNumericPart;
        }
        $params['admin_type'] = Consts::ADMIN_TYPE['teacher'];
        break;

      default:

        abort(402, "Error: Admin type is not valid!");
        break;
    }

    $params['admin_created_id'] = $admin->id;
    $params['admin_updated_id'] = $admin->id;

    Admin::create($params);

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    // Do not use this function
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $admin = Admin::find($id);

    if (!$admin) {
      return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Record not found!'));
    }
    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $this->responseData['status'] = Consts::USER_STATUS;
    $this->responseData['roles'] = $roles;
    $this->responseData['admin'] = $admin;
    $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
    $this->responseData['gender'] = Consts::GENDER;
    $params['status'] = Consts::STATUS['active'];
    $area = Area::getsqlArea($params)->get();
    $this->responseData['area'] =  $area;
    $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
    $this->responseData['departments'] =  WarehouseDepartment::get();
    $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();

    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Admin $admin)
  {
    $request->validate([
      'name' => 'required',
      'email' => "required|email|max:255|unique:admins,email," . $admin->id,
      'password_new' => 'nullable|min:8',
    ]);

    $params = $request->all();

    $params['admin_code'] = $params['admin_code'] ?? $admin->admin_code;

    if ($request->filled('password_new')) {
      $params['password'] = $request->input('password_new');
    }
    // Chuyển object về mảng
    $old_json_params = is_object($admin->json_params)
      ? json_decode(json_encode($admin->json_params), true)
      : ($admin->json_params ?? []);

    // Đảm bảo dữ liệu là mảng
    $old_json_params = is_array($old_json_params) ? $old_json_params : [];

    // Lấy json_params cũ và chuyển toàn bộ thành mảng (đa chiều)
    // $old_json_params = json_decode(json_encode($admin->json_params), true);
    // Merge 2 mảng và cập nhật nếu có dữ liệu mới 
    $arr_insert['json_params'] = array_replace_recursive($old_json_params, $params['json_params'] ?? []);
    // Update riêng phần quyền mở rộng
    $arr_insert['json_params']['role_extend'] = $params['json_params']['role_extend'] ?? [];
    // Nếu không tồn tại khu vực đc quản lý thì set null
    $arr_insert['json_params']['area_id'] = $params['json_params']['area_id'] ?? [];
    // Gán lại giá trị json_params
    $params['json_params'] = $arr_insert['json_params'];
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $admin->update($params);

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Admin $admin)
  {
    $admin->delete();

    return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
  }

  public function changeAccountForm()
  {
    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();

    $this->responseData['roles'] = $roles;

    return $this->responseView($this->viewPart . '.account');
  }

  public function changeAccount(Request $request)
  {
    // Check user_auth
    if (!Auth::guard('admin')->check()) {
      return back()->withInput()->with('errorMessage', __('User is not found'));
    }
    $id = Auth::guard('admin')->user()->id;
    $password = Auth::guard('admin')->user()->password;

    $request->validate([
      'email' => "required|email|max:255|unique:admins,email," . $id,
      'name' => 'required|string',
      'password_old' => 'required',
      'password' => 'required|string|min:6|confirmed',
      'password_confirmation' => 'required'
    ]);

    if (!Hash::check($request->password_old, $password)) {
      return back()->withInput()->with('errorMessage', __('Old password is invalid!'));
    }

    $user = Admin::where('id', $id)
      ->update([
        // 'email' => $request->email,
        'password' => bcrypt($request->password)
      ]);

    Auth::guard('admin')->logout();
    return redirect()->route('admin.login')->with('successMessage', __('Successfully updated. Please login again for security!'));
  }

  public function forgotPasswordForm(Request $request)
  {
    return redirect()->back()->with('warningMessage', __('This function is under development!'));
  }

  public function forgotPassword(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ]);

    $token = Str::random(64);

    DB::table('password_resets')->insert([
      'email' => $request->email,
      'token' => $token,
      'created_at' => Carbon::now()
    ]);

    Mail::send('emails.forget_password', ['token' => $token], function ($message) use ($request) {
      $message->to($request->email);
      $message->subject(__('Reset Password'));
    });

    return redirect()->back()->with('successMessage', __('We have e-mailed your password reset link!'));
  }

  public function resetPasswordForm($token)
  {
    $this->responseData['token'] = $token;
    return $this->responseView($this->viewPart . '.reset_password');
  }

  public function resetPassword(Request $request)
  {

    $request->validate([
      'email' => 'required|email|exists:admins',
      'password' => 'required|string|min:6|confirmed',
      'password_confirmation' => 'required'
    ]);

    $updatePassword = DB::table('password_resets')
      ->where([
        'email' => $request->email,
        'token' => $request->token
      ])
      ->first();

    if (!$updatePassword) {
      return back()->withInput()->with('errorMessage', __('Invalid token!'));
    }

    $user = Admin::where('email', $request->email)
      ->update(['password' => bcrypt($request->password)]);

    DB::table('password_resets')->where(['email' => $request->email])->delete();


    return redirect()->route('admin.home')->with('successMessage', __('Your password has been changed!'));
  }
  public function exportUser(Request $request)
  {
    $params = $request->all();
    return Excel::download(new UserExport($params), 'User.xlsx');
  }
}
