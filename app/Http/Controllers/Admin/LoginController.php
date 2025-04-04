<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Admin;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class LoginController extends Controller
{

    public function index()
    {
        $server_admin = Cookie::get('server_admin');
        if ($server_admin != null) {
            $server_admin = decrypt(Cookie::get('server_admin'));
            if (isset($server_admin['email']) && isset($server_admin['password'])) {
                Auth::guard('admin')->attempt([
                    'email' => $server_admin['email'],
                    'password' => $server_admin['password'],
                    'status' => $server_admin['status']
                ]);
            }
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        return view('admin.pages.login');
    }

    public function login(LoginRequest $request)
    {
        $url = $request->input('url') ?? route('admin.home');
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        $email = $request->email;
        $password = $request->password;
        $data = [
            'email' => $email,
            'password' => $password,
            'status' => Consts::USER_STATUS['active']
        ];

        $attempt = Auth::guard('admin')->attempt([
            'email' => $email,
            'password' => $password,
            'status' => Consts::USER_STATUS['active']
        ]);

        if (isset($request->remember)) {
            cookie()->queue(cookie('server_admin', encrypt($data), 15 * 24 * 60));
        }

        if ($attempt) {
            return redirect($url);
        }

        // Bổ sung thêm phần check nếu đăng nhập bằng admin_code
        $attempt_code = Auth::guard('admin')->attempt([
            'admin_code' => $email,
            'password' => $password,
            'status' => Consts::USER_STATUS['active']
        ]);

        if ($attempt_code) {
            return redirect($url);
        }
        /**
         * Check user và điều hướng theo admin_type
         * CBTS => làm bài test đầu vào theo CBTS
         * GV => làm bài test đầu vào theo GV
         */
        $user = Admin::where(function ($query) use ($email) {
            $query->where('email', $email)
                ->orWhere('admin_code', $email);
        })->first();
        
        if (!empty($user) && $user->status == 'deactive' && Hash::check($password, $user->password)) {
            
            $request->session()->put('login_email', $email);
            $request->session()->put('login_password', $password);
            if ($user->admin_type == Consts::ADMIN_TYPE['admission']) {
                return redirect()->route('test_staff.index');
            }
            if ($user->admin_type == Consts::ADMIN_TYPE['teacher']) {
                return redirect()->route('test_teacher.test');
            }
        }

        return redirect()->back()->with(
            'errorMessage',
            __('Wrong credential! Please try again!')
        );
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Cookie::queue(Cookie::forget('server_admin'));
        return redirect()->back();
    }
}
