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

    public function forgot()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }
        return view('admin.pages.forgot');
    }

    public function forgotPass(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'email' => 'required|email:rfc,dns'
            ]);
            $email = $request->email;
            if (isset($email)) {
                //  check email
                $check = Admin::where('email', $email)->first();
                if ($check == null) {
                    return redirect()->back()->with('errorMessage', __('Email does not exist!'));
                }
                $code = Str::random(16);
                Admin::where('email', $email)->update(['remember_token' => $code]);
                $data = [
                    'email' => $email,
                    'code' => $code,
                    'time' => time(),
                ];
                $token = encrypt($data);
                Mail::send(
                    'emails.forget_password',
                    [
                        'token' => $token
                    ],
                    function ($message) use ($email) {
                        $message->to($email);
                        $message->subject(__('Your request has been sent'));
                    }
                );
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Request sent successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function resetPass($token)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        $data = decrypt($token);
        if ($data) {
            // 10 phút
            if ($data['time'] < time() - (10 * 6000)) {
                return redirect()->route('admin.forgot')->with('errorMessage', __('The token has expired!'));
            };
        }
        return view('admin.pages.change_pass', ['token' => $token]);
    }

    public function resetPassPost(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required_with:password|same:password'
        ]);

        $data = decrypt($request->token);
        if ($data) {
            // 10 phút
            if ($data['time'] < time() - (10 * 6000)) {
                return redirect()->route('admin.forgot')->with('errorMessage', __('The token has expired!'));
            };
        }
        Admin::where('email', $data['email'])->update(['password' => bcrypt($request->password)]);
        return redirect()->route('admin.login')->with('successMessage', __('Password changed successfully!'));
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        Cookie::queue(Cookie::forget('server_admin'));
        return redirect()->back();
    }
}
