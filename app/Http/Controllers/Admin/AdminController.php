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
use App\Exports\UserExport;
use App\Models\AdminMenu;
use App\Models\Language;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdminsImport;
use App\Exports\AdminExport;


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
        $this->responseData['params'] = $params;

        $admins = $this->adminService->getAdmins($params, true);

        $this->responseData['admins'] = $admins;
        $this->responseData['roles'] = Role::where('status', '=', Consts::STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['area'] = Area::getsqlArea(['status' => Consts::STATUS['active']])->get();
        $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();
        $this->responseData['departments'] =  Department::get();

        $this->responseData['status'] = Consts::USER_STATUS;
        $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['roles'] = Role::where('status', Consts::STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['area'] =  Area::where('status', '=', Consts::STATUS['active'])->get();
        $this->responseData['departments'] =  Department::get();
        $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();

        $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
        $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['gender'] = Consts::GENDER;

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
            'json_params'
        ]);
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
            switch ($params['admin_type']) {
                case Consts::ADMIN_TYPE['staff']:
                    $params['admin_code'] = 'CB' . $newNumericPart;
                    break;
                case Consts::ADMIN_TYPE['teacher']:
                    $params['admin_code'] = 'GV' . $newNumericPart;
                    break;
                case Consts::ADMIN_TYPE['admission']:
                    $params['admin_code'] = 'TS' . $newNumericPart;
                    break;
                default:
                    $params['admin_code'] = 'NV' . $newNumericPart;
                    break;
            }
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
        $this->responseData['admin'] = $admin;

        $this->responseData['roles'] = Role::where('status', Consts::STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['area'] =  Area::where('status', Consts::STATUS['active'])->get();
        $this->responseData['departments'] =  Department::get();
        $this->responseData['direct_manager'] = Admin::where('status', Consts::STATUS['active'])->get();

        $this->responseData['status'] = Consts::USER_STATUS;
        $this->responseData['admin_type'] = Consts::ADMIN_TYPE;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;

        $activeModules = Module::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['activeModules'] = $activeModules;

        $activeMenus = AdminMenu::whereNull('parent_id')->where('status', '=', Consts::USER_STATUS['active'])->with('children')->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['activeMenus'] = $activeMenus;

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
        DB::beginTransaction();
        try {
            $params = $request->all();

            $params['admin_code'] = $params['admin_code'] ?? $admin->admin_code;

            if ($request->filled('password_new')) {
                $params['password'] = $request->input('password_new');
            }
            // Chuyển object về mảng
            $old_json_params = is_object($admin->json_params)
                ? json_decode(json_encode($admin->json_params), true)
                : (is_array($admin->json_params) ? $admin->json_params : []);

            // Merge 2 mảng và cập nhật nếu có dữ liệu mới
            $arr_insert['json_params'] = array_replace_recursive($old_json_params, $params['json_params'] ?? []);
            // Update riêng phần quyền mở rộng
            $arr_insert['json_params']['role_extend'] = $params['json_params']['role_extend'] ?? [];
            // Nếu không tồn tại khu vực đc quản lý thì set null
            $arr_insert['json_params']['area_id'] = $params['json_params']['area_id'] ?? [];
            // Nếu không tồn tại menu mở rộng thì set null
            $arr_insert['json_params']['menu_id'] = $params['json_params']['menu_id'] ?? [];
            // Nếu không tồn tại chức năng mở rộng thì set null
            $arr_insert['json_params']['function_code'] = $params['json_params']['function_code'] ?? [];
            // Gán lại giá trị json_params
            $params['json_params'] = $arr_insert['json_params'];
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $admin->update($params);

            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (\Throwable $ex) {
            DB::rollBack();
            // Log lỗi để debug
            Log::error('Lỗi khi update người dùng: ' . $ex->getMessage(), ['trace' => $ex->getTraceAsString()]);

            return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra, vui lòng thử lại!'));
        }
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
        return $this->responseView($this->viewPart . '.account');
    }

    public function changeAccount(Request $request)
    {
        // Check user_auth
        if (!Auth::guard('admin')->check()) {
            return back()->withInput()->with('errorMessage', __('User is not found'));
        }
        $admin = Auth::guard('admin')->user();
        $id = $admin->id;
        $password = $admin->password;

        $request->validate([
            // 'email' => "required|email|max:255|unique:admins,email," . $id,
            // 'name' => 'required|string',
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
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }
        return $this->responseView($this->viewPart . '.password_forgot');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns|exists:admins',
        ]);

        $token = Str::random(64);

        $data = [
            'email' => $request->email,
            'token' => $token,
            'time' => time(),
        ];
        $token_data = encrypt($data);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forget_password', ['token' => $token_data], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject(__('Reset Password'));
        });

        return redirect()->route('admin.home')->with('successMessage', __('We have e-mailed your password reset link!'));
    }

    public function resetPasswordForm($token)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        $data = decrypt($token);
        // 10 phút
        if (isset($data['time']) && $data['time'] < time() - (10 * 6000)) {
            return redirect()->route('admin.password.forgot.get')->with('errorMessage', __('The token has expired!'));
        }
        $this->responseData['token'] = $token;
        return $this->responseView($this->viewPart . '.password_reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required_with:password|same:password'
        ]);
        try {
            $data = decrypt($request->token);

            if (isset($data['time']) && $data['time'] < time() - (10 * 6000)) {
                return redirect()->route('admin.password.forgot.get')->with('errorMessage', __('The token has expired!'));
            }

            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $data['email'],
                    'token' => $data['token']
                ])
                ->first();

            if (!$updatePassword) {
                return redirect()->back()->with('errorMessage', __('The token is invalid!'));
            }

            Admin::where('email', $data['email'])->update(['password' => bcrypt($request->password)]);

            DB::table('password_resets')->where(['email' => $data['email']])->delete();

            return redirect()->route('admin.login')->with('successMessage', __('Your password has been changed!'));
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMessage', __('The token is invalid!'));
        }
    }

    public function exportUser(Request $request)
    {
        $params = $request->all();
        return Excel::download(new UserExport($params), 'User.xlsx');
    }
    public function importUser(Request $request)
    {
        $params = $request->all();
        if (isset($params['file'])) {
            if ($this->checkFileImport($params['file']) == false) {
                $_datawith = 'errorMessage';
                $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
                session()->flash($_datawith, $mess);
                return $this->sendResponse($_datawith, $mess);
            }
            $_datawith = 'successMessage';
            $import = new AdminsImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            $data_count = $import->getRowCount();
            $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
            foreach ($data_count['error_mess'] as $val) {
                $mess .= '</br>' . $val;
            };
            if (count($data_count['error_mess']) > 0) {
                $_datawith = 'errorMessage';
            };
            session()->flash($_datawith, $mess);
            return $this->sendResponse($_datawith, $mess);
        }
        session()->flash('errorMessage', __('Cần chọn file để Import!'));
        return $this->sendResponse('warning', __('Cần chọn file để Import!'));
    }

    public function exportAdmin(Request $request)
    {
        $params = $request->all();
        return Excel::download(new AdminExport($params), 'Admin.xlsx');
    }
}
