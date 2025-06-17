<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Helpers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Page;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Area;
use App\Models\User;
use App\Models\LessonUser;
use App\Models\Attendances;
use App\Models\AttendanceStudent;
use App\Models\Receipt;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Mail\UserRegisterConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->responseData['menu'] = Menu::getSqlMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
    }

    public function setSessionUser($id)
    {
        session(['user' => $id]);
        return redirect()->back()->with('successMessage', __('Thay đổi thành công!'));
    }
    public function index()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $this->responseData['detail'] = $user;
            $seo_title = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_title : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_title'} ?? '';
            $seo_keyword = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_keyword : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_keyword'} ?? '';
            $seo_description = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_description : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_description'} ?? '';
            $seo_image = $this->responseData['setting']->seo_og_image ?? '';
            $this->responseData['meta']['seo_title'] = $seo_title;
            $this->responseData['meta']['seo_keyword'] = $seo_keyword;
            $this->responseData['meta']['seo_description'] = $seo_description;
            $this->responseData['meta']['seo_image'] = $seo_image;
            $this->responseData['menu'] = Menu::getSqlMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
            $this->responseData['gender'] = Consts::GENDER;
            $this->responseData['students'] = optional($user->parent)->parentStudents
                ? optional($user->parent)->parentStudents->map(fn($val) => $val->student)
                : [];
            return $this->responseView('frontend.pages.user.account');
        }
        return redirect()->route('home')->with('errorMessage', __('Yêu cầu đăng nhập!'));
    }

    public function login(LoginRequest $request)
    {
        $current = $request->input('current') ?? route('home');
        $referer = $request->input('referer') ?? '';
        $url = $current == route('home') ? $referer : $current;
        if (Auth::guard('web')->check()) {
            return $this->sendResponse('', 'success');
        }
        try {
            $username = $request->email;
            $password = $request->password;
            $attempt = Auth::guard('web')->attempt([
                'username' => $username,
                'password' => $password,
                'status' => Consts::USER_STATUS['active']
            ]);
            if ($attempt) {
                session()->flash('successMessage', 'Chào mừng ' . Auth::guard('web')->user()->name);
                return $this->sendResponse(['url' => $url], 'success');
            }

            abort(401, __('Tài khoản hoặc mật khẩu không chính xác!'));
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('home')->with('successMessage', __('Bạn đã đăng xuất!'));
    }

    // Signup new account
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
            'repassword' => 'required|same:password',
            'json_params.cccd' => [
                'required',
                'min:8',
                'max:255',
            ],
        ]);
        DB::beginTransaction();
        try {
            $params = $request->all();
            $cccdExists = DB::table('admins')
                ->whereJsonContains('json_params->cccd', $params['json_params']['cccd'])
                ->exists();
            if ($cccdExists) {
                return $this->sendResponse('error', __('The CCCD has already been taken'));
            }
            unset($params['repassword']);
            $lastAdmin = Student::orderBy('id', 'desc')->first();
            $lastAdminCode = $lastAdmin->id ?? 0;
            // Extract the numeric part and increment it
            $numericPart = (int)$lastAdminCode;
            // Calculate the number of digits required for the numeric part
            $numDigits = max(4, strlen((string)$numericPart));
            // Add one to the numeric part
            $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);


            // lấy khu vực online
            $params_area['keyword'] = 'online';
            $area = Area::getSqlArea($params_area)->first();
            $params['area_id'] = $area->id ?? null;
            $params['admin_code'] = 'Online' . $newNumericPart;
            $params['admin_type'] = Consts::ADMIN_TYPE['student'];
            $params['role'] = 8;
            $params['status'] = Consts::STATUS['deactive'];
            $user = Admin::create($params);
            $confirmationCode = Str::random(32);
            $user->code = $confirmationCode;
            $user->save();
            Mail::to($user->email)->send(new UserRegisterConfirmation($user->email, $confirmationCode));
            DB::commit();
            session()->flash('successMessage', __('Đăng ký thành công. Vui lòng kích hoạt trong email của bạn'));
            return $this->sendResponse($user, __('Signup successed!'));
        } catch (Exception $ex) {
            DB::rollBack($ex);
            abort(422, __($ex->getMessage()));
        }
    }

    // Verify new account from email
    public function verifyAccount(Request $request)
    {
        try {
            $user = User::where('email_verification_code', $request->code)->first();

            if (empty($user)) {
                throw new Exception(__('Account verification failed'));
            }
            $user->email_verification_code = null;
            $user->status = Consts::STATUS['active'];
            $user->save();

            Auth::login($user, true);
            return redirect()->route('home')->with('successMessage', __('Chào mừng ') . $user->name);
        } catch (Exception $ex) {
            return $this->sendResponse('error', $ex->getMessage());
        }
    }

    public function forgotPasswordForm(Request $request)
    {
        $this->responseData['menu'] = Menu::getSqlMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
        return $this->responseView('frontend.pages.user.forgot_password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        try {
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::send('emails.forget_password_frontend', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject(__('Reset Password'));
            });
            return redirect()->route('home')->with('successMessage', __('Chúng tôi đã gửi email liên kết đặt lại mật khẩu của bạn!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function resetPasswordForm($token)
    {
        $this->responseData['menu'] = Menu::getSqlMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
        // các thông số thẻ meta
        $this->responseData['token'] = $token;
        return $this->responseView('frontend.pages.user.reset_password');
        // return $this->responseView('pages.user.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6'
        ]);
        $params = $request->all();
        DB::beginTransaction();
        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' =>  $params['email'],
                    'token' => $params['token']
                ])
                ->first();
            if (!$updatePassword) {
                return back()->with('errorMessage', __('Mã token hết hạn!'));
            }
            $user = User::where('email', $params['email'])
                ->update(['password' => bcrypt($params['password'])]);

            DB::table('password_resets')->where(['email' => $params['email']])->delete();
            DB::commit();

            return redirect()->route('home')->with('successMessage', __('Mật khẩu của bạn đã được cập nhật!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6'
        ]);
        $admin = Admin::find($request->admin);
        DB::beginTransaction();
        try {
            if (!Hash::check($request->old_password, $admin->password)) {
                return back()->with('errorMessage', __('Mật khẩu cũ không chính xác!'));
            } else {
                $admin->password = $request->password;
                $admin->save();
            }
            DB::commit();
            return redirect()->route('home')->with('successMessage', __('Mật khẩu của bạn đã được cập nhật!'));
        } catch (Exception $ex) {
            DB::rollBack();
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function changeAccountForm()
    {
        return $this->responseView('pages.user.change_account');
    }

    public function changeAccount(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'birthday' => 'required',
            'phone' => 'required|min:9',
            'gender' => 'required',
            'address' => 'nullable|string',
            'password' => 'nullable|min:8',
        ]);
        $params = $request->all();
        if (!Auth::check()) {
            abort(401, __('User is not found'));
        }
        $auth = Auth::guard('web')->user();
        $user = Admin::find($auth->id);
        DB::beginTransaction();
        try {
            $user->name = $params['name'] ?? '';
            $user->phone = $params['phone'] ?? '';
            $user->birthday = $params['birthday'] ?? '';
            $user->gender = $params['gender'] ?? '';
            $user->json_params->address = $params['address'] ?? '';
            // Kiểm tra và cập nhật json_params (giả định json_params là một đối tượng JSON đã decode)
            $jsonParams = $user->json_params ?? new \stdClass(); // Đảm bảo json_params là một đối tượng
            $jsonParams->address = $params['address'] ?? '';
            $user->json_params = $jsonParams;

            // Cập nhật mật khẩu nếu có giá trị
            if (!empty($params['password'])) {
                $user->password = $params['password'];
            }
            $user->save();
            DB::commit();
            return back()->with('successMessage', __('Cập nhật thông tin thành công'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function myStudent()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('home')->with('errorMessage', __('Yêu cầu đăng nhập!'));
        }

        $student = $this->getStudent();
        $parents = collect($student->studentParents)->map(function ($val) {
            return [
                'parent' => $val->parent,
                'relationship' => $val->relationship,
            ];
        });
        $this->responseData['parents'] = $parents;

        $this->responseData['student'] = $student;
        return $this->responseView('frontend.pages.user.student');
    }

    /**
     * Xem theo dạng lịch là dạng ngày
     * Ngày hiện tại và lịch
     * Chỉ xem thông tin
     * Màn hình thống kê(ăn muộn, đón muộn, nghỉ học)
     */
    public function myAttendance()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('home')->with('errorMessage', __('Yêu cầu đăng nhập!'));
        }
        $student = $this->getStudent();
        // Lấy thông tin điểm danh của học sinh của ngày hôm nay
        $class_id = $student->currentClass->id;
        $date = Carbon::now()->format('Y-m-d');
        $attendance = Attendances::where('class_id', $class_id)
            ->whereDate('tracked_at', $date)->first();
        $detail = null;
        if ($attendance) {
            $detail = AttendanceStudent::where('class_attendance_id', $attendance->id)
                ->where('student_id', $student->id)
                ->first();
        }
        // Lấy thông tin điểm danh để xem theo tháng
        $attendance_student = AttendanceStudent::whereHas('attendance', fn($q) => $q->where('class_id', $class_id))
            ->where('student_id', $student->id)
            ->get();


        $events = [];
        foreach ($attendance_student as $val) {
            switch ($val->status) {
                case 'checkin':
                    $events[] = [
                        'title' => 'Đến: ' . Carbon::parse($val->checkin_at)->format('H:i'),
                        'start' => Carbon::parse($val->attendance->tracked_at)->format('Y-m-d'),
                    ];
                    if ($val->checkout_at != null) {
                        $events[] = [
                            'title' => 'Vể: ' . Carbon::parse($val->checkout_at)->format('H:i'),
                            'start' => Carbon::parse($val->attendance->tracked_at)->format('Y-m-d'),
                        ];
                    }
                    break;
                case 'absent_unexcused':
                    $events[] = [
                        'title' => 'Nghỉ không phép',
                        'start' => Carbon::parse($val->attendance->tracked_at)->format('Y-m-d'),
                    ];
                    break;
                case 'absent_excused':
                    $events[] = [
                        'title' => 'Nghỉ có phép',
                        'start' => Carbon::parse($val->attendance->tracked_at)->format('Y-m-d'),
                    ];
                    break;
                default:
                    break;
            }
        }
        $this->responseData['detail'] = $detail;
        $this->responseData['events'] = $events;
        return $this->responseView('frontend.pages.user.attendance');
    }


    /**
     * tài chính: Lịch sử thông báo phí và lịch sử thanh toán
     */
    public function myTeacher()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('home')->with('errorMessage', __('Yêu cầu đăng nhập!'));
        }
        $student = $this->getStudent();
        $teachers = $student->currentClass->teacher->filter(function ($val) {
            return $val->pivot->status != Consts::STATUS_DELETE;
        });
        $this->responseData['teachers'] = $teachers;
        $this->responseData['student'] = $student;
        $this->responseData['class'] = $student->currentClass;
        return $this->responseView('frontend.pages.user.teacher');
    }


    /**
     * tài chính: Lịch sử thông báo phí và lịch sử thanh toán
     */
    public function myReceipt()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('home')->with('errorMessage', __('Yêu cầu đăng nhập!'));
        }
        $student = $this->getStudent();
        $receipt = Receipt::where('student_id', $student->id)
            // ->where('status','!=',Consts::STATUS_RECEIPT['pending'])
            ->with(['receiptTransaction', 'receiptTransaction.user_cashier'])
            ->get();
        $this->responseData['rows'] = $receipt;
        return $this->responseView('frontend.pages.user.receipt');
    }
    /**
     * Thực đơn hàng ngày
     */

    public function getStudent()
    {
        $user = Auth::guard('web')->user();
        $id_student = session('user') ?? 0;
        $students = optional($user->parent)->parentStudents
            ? optional($user->parent)->parentStudents->map(fn($val) => $val->student)
            : collect();
        $student = $students->firstWhere('id', $id_student) ?? $students->first();
        session(['user' => $student->id]);
        $this->responseData['students'] = $students;
        return $student;
    }
}
