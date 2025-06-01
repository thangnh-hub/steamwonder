<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Attendances;
use App\Models\AttendanceStudent;
use App\Models\tbClass;
use App\Models\Area;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Services\DataPermissionService;
use Exception;
use Carbon\Carbon;

class AttendancesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'attendance';
        $this->viewPart = 'admin.pages.attendance';
        $this->responseData['module_name'] = 'Quản lý điểm danh';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = [];
        $params = $request->only(['keyword', 'class_id', 'area_id', 'tracked_at']);
        $params['tracked_at'] = $params['tracked_at'] ?? date('Y-m-d', time());
        $this->responseData['classs'] = tbClass::all();
        $this->responseData['areas'] = Area::all();
        $this->responseData['list_teacher'] = Teacher::all();
        $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['params'] = $params;
        if (isset($params['class_id']) && $params['class_id'] != "") {
            $rows = StudentClass::getSqlStudentClass($params)->get();
            // Thông tin điểm danh
            $attendance = Attendances::where('class_id', $params['class_id'])
                ->whereDate('tracked_at', $params['tracked_at'])->first();
            // Chi tiết điểm danh
            if ($attendance) {
                $attendance_students = AttendanceStudent::where('class_attendance_id', $attendance->id)->get();
                foreach ($rows as $row) {
                    $row->attendance = $attendance_students->where('student_id', $row->student_id)->first();
                }
            }
        }

        $this->responseData['rows'] = $rows;
        $this->responseData['module_name'] = 'Điểm danh đến';
        return $this->responseView($this->viewPart . '.index');
    }
    public function checkout(Request $request)
    {
        $rows = [];
        $params = $request->only(['keyword', 'class_id', 'area_id', 'tracked_at']);
        $params['tracked_at'] = $params['tracked_at'] ?? date('Y-m-d', time());
        $this->responseData['classs'] = tbClass::all();
        $this->responseData['areas'] = Area::all();
        $this->responseData['list_teacher'] = Teacher::all();
        $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['params'] = $params;
        if (isset($params['class_id']) && $params['class_id'] != "") {
            // Thông tin điểm danh
            $attendance = Attendances::where('class_id', $params['class_id'])
                ->whereDate('tracked_at', $params['tracked_at'])
                ->with(['attendanceStudent' => function ($query) {
                    $query->where('status', Consts::ATTENDANCE_STATUS['checkin']); // Lọc chỉ những học sinh checkin
                }])
                ->first();
        }

        $this->responseData['rows'] =  $attendance->attendanceStudent ?? collect();
        $this->responseData['module_name'] = 'Điểm danh về';
        return $this->responseView($this->viewPart . '.checkout');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();
            $student_id = $request->input('student_id');
            $class_id = $request->input('class_id');
            $tracked_at = $request->input('tracked_at');
            $params = $request->only(['status', 'checkin_parent_id', 'checkin_teacher_id', 'checkout_parent_id', 'checkout_teacher_id', 'json_params']);
            //Lấy ngày điểm danh của lớp
            $attendance = Attendances::where('class_id', $class_id)->whereDate('tracked_at', $tracked_at)->first();
            // Chưa có thì tạo mới
            if (!$attendance) {
                $attendance =  Attendances::create([
                    'class_id' => $class_id,
                    'tracked_at' => $tracked_at,
                    'admin_created_id' => $admin->id,
                ]);
            }

            // Lấy thông tin điểm danh của học sinh
            $attendance_student = AttendanceStudent::where('student_id', $student_id)
                ->where('class_attendance_id', $attendance->id)->first();

            //Xử lý lưu ảnh nếu có
            $publicPath = $attendance_student->json_params->img ?? '';
            $publicPath_return = $attendance_student->json_params->img_return ?? '';
            if (isset($params['json_params']['img'])) {
                if ($this->isBase64($params['json_params']['img'])) {
                    // Xóa ảnh cũ nếu có
                    if (isset($attendance_student->json_params->img) && $attendance_student->json_params->img) {
                        $oldFilePath = public_path($attendance_student->json_params->img);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $today = Carbon::parse($tracked_at)->format('d_m_Y');
                    $directory = "data/attendance/{$today}/{$class_id}/checkin";
                    $fileName = 'student_' . $student_id . '_' . time() . '.png';
                    $publicPath = $this->storeImagePath($params['json_params']['img'], $directory, $fileName);
                }
            }
            if (isset($params['json_params']['img_return'])) {
                if ($this->isBase64($params['json_params']['img_return'])) {
                    // Xóa ảnh cũ nếu có
                    if (isset($attendance_student->json_params->img_return) && $attendance_student->json_params->img_return != '') {
                        $oldFilePath = public_path($attendance_student->json_params->img_return);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $today = Carbon::parse($tracked_at)->format('d_m_Y');
                    $directory = "data/attendance/{$today}/{$class_id}/checkout";
                    $fileName = 'student_' . $student_id . '_' . time() . '.png';
                    $publicPath_return = $this->storeImagePath($params['json_params']['img_return'], $directory, $fileName);
                }
            }
            $params['json_params']['img'] = $publicPath ?? null;
            $params['json_params']['img_return'] = $publicPath_return ?? null;

            // Đã có thì cập nhật
            if ($attendance_student) {
                // Chuyển object về mảng
                $old_json_params = is_object($attendance_student->json_params)
                    ? json_decode(json_encode($attendance_student->json_params), true)
                    : (is_array($attendance_student->json_params) ? $attendance_student->json_params : []);
                // Merge 2 mảng và cập nhật nếu có dữ liệu mới
                $arr_insert['json_params'] = array_replace_recursive($old_json_params, $params['json_params'] ?? []);
                $params['json_params'] = $arr_insert['json_params'];
                $params['admin_updated_id'] = $admin->id;
                $params['checkout_at'] = Carbon::now();

                $attendance_student->update($params);
            } else {
                $params['student_id'] = $student_id;
                $params['class_attendance_id'] = $attendance->id;
                $params['checkin_at'] = Carbon::now();
                AttendanceStudent::create($params);
            }
            DB::commit();
            return $this->sendResponse('success', __('Điểm danh thành công!'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->sendResponse('error', __($ex->getMessage()));
        }
    }

    public function attendanceSummaryByMonth(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $params = $request->only(['keyword', 'class_id', 'month', 'area_id']);
        $monthYear = $params['month'] ?? Carbon::now()->format('Y-m');
        $carbonDate = Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $carbonDate->daysInMonth;
        $params['permission_class'] = DataPermissionService::getPermissionClasses($admin->id);
        if (isset($params['class_id']) && $params['class_id'] != "") {
            // Lấy danh sách học sinh theo lớp
            $studentClass = StudentClass::getSqlStudentClass($params)
                ->with([
                    'attendances' => function ($query) use ($monthYear, $daysInMonth) {
                        $query->whereBetween('tracked_at', [
                            Carbon::createFromFormat('Y-m-d', "$monthYear-01")->startOfDay(),
                            Carbon::createFromFormat('Y-m-d', "$monthYear-$daysInMonth")->endOfDay()
                        ])->select('id', 'class_id', 'tracked_at');
                    },
                    'attendances.attendanceStudent' => function ($query) {
                        $query->select('id', 'student_id', 'class_attendance_id', 'status'); // Chỉ lấy cột cần thiết
                    }
                ])
                ->get();

            foreach ($studentClass as $row) {
                $attendancesByDay = [];
                foreach (range(1, $daysInMonth) as $day) {
                    $date = Carbon::createFromFormat('Y-m-d', "$monthYear-$day");
                    $attendance = $row->attendances->first(function ($item) use ($date) {
                        return isset($item->tracked_at) &&
                            Carbon::parse($item->tracked_at)->toDateString() === $date->toDateString();
                    });
                    if ($attendance) {
                        $attendanceStudent = $attendance->attendanceStudent
                            ->firstWhere('student_id', $row->student_id);

                        if ($attendanceStudent) {
                            $attendancesByDay[$day] = $attendanceStudent;
                        }
                    }
                }
                $row->attendances_by_day = $attendancesByDay;
            }
        }
        $this->responseData['areas'] = Area::all();
        $this->responseData['classs'] = tbClass::whereIn('id', $params['permission_class'])->get();
        $this->responseData['list_teacher'] = Teacher::all();
        $this->responseData['carbonDate'] = $carbonDate;
        $this->responseData['daysInMonth'] = $daysInMonth;
        $this->responseData['rows'] = $studentClass ?? [];
        $this->responseData['params'] = $params;
        $this->responseData['day_week'] = Consts::DAY_WEEK_MINI;

        return $this->responseView($this->viewPart . '.summary_by_month');
    }

    public function showSummaryByMonth(Request $request)
    {
        $class_id = $request->input('class_id');
        $student_id = $request->input('student_id');
        $date = $request->input('date');
        $attendance = Attendances::where('class_id', $class_id)
            ->whereDate('tracked_at', $date)->first();
        $detail = null;
        if ($attendance) {

            $detail = AttendanceStudent::where('class_attendance_id', $attendance->id)
                ->where('student_id', $student_id)
                ->first();
        }
        $list_teacher = Teacher::all();
        $result['view'] = view($this->viewPart . '.show_summary_by_month', compact('detail', 'list_teacher', 'date', 'class_id', 'student_id'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    public function updateOrstoreAttendance(Request $request)
    {
        $id = $request->input('id');
        $student_id = $request->input('student_id');
        $class_id = $request->input('class_id');
        $date = $request->input('date');

        $image_arrival = $request->input('image_arrival');
        $image_return = $request->input('image_return');

        DB::beginTransaction();
        try {
            $params = $request->only([
                'status',
                'checkin_parent_id',
                'checkout_parent_id',
                'checkin_teacher_id',
                'checkout_teacher_id',
                'checkin_at',
                'checkout_at',
                'json_params'
            ]);
            $attendance_student = AttendanceStudent::find($id);
            $today = Carbon::parse($date)->format('d_m_Y');
            // Xử lý lưu ảnh nếu có
            if ($image_arrival != null) {
                $params['json_params']['img'] = $attendance_student->json_params->img ?? '';
                if ($this->isBase64($image_arrival)) {
                    // Xóa ảnh cũ nếu có
                    if ($attendance_student && $attendance_student->json_params->img) {
                        $oldFilePath = public_path($attendance_student->json_params->img);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $directory = "data/attendance/{$today}/{$class_id}/checkin";
                    $fileName = 'student_' . $student_id . '_' . time() . '.png';
                    $params['json_params']['img'] = $this->storeImagePath($image_arrival, $directory, $fileName);
                }
            }
            if ($image_return != null) {
                if ($this->isBase64($image_return)) {
                    $params['json_params']['img_return'] = $attendance_student->json_params->img_return ?? '';
                    // Xóa ảnh cũ nếu có
                    if ($attendance_student && isset($attendance_student->json_params->img_return)) {
                        $returnoldFilePath = public_path($attendance_student->json_params->img_return);
                        if (file_exists($returnoldFilePath)) {
                            unlink($returnoldFilePath);
                        }
                    }
                    $return_directory = "data/attendance/{$today}/{$class_id}/checkout";
                    $return_fileName = 'student_' . $student_id . '_' . time() . '.png';
                    $params['json_params']['img_return'] = $this->storeImagePath($image_return, $return_directory, $return_fileName);
                }
            }
            // Check attendance
            if ($attendance_student) {
                // Cập nhật thông tin điểm danh
                // Chuyển object về mảng
                $old_json_params = is_object($attendance_student->json_params)
                    ? json_decode(json_encode($attendance_student->json_params), true)
                    : (is_array($attendance_student->json_params) ? $attendance_student->json_params : []);
                // Merge 2 mảng và cập nhật nếu có dữ liệu mới
                $arr_insert['json_params'] = array_replace_recursive($old_json_params, $params['json_params'] ?? []);
                $params['json_params'] = $arr_insert['json_params'];
                $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

                $attendance_student->update($params);
            } else {
                // Tìm hoặc tạo mới phiên điểm danh
                $attendance = Attendances::firstOrCreate(
                    [
                        'class_id' => $class_id,
                        'tracked_at' => $date,
                    ],
                    [
                        'admin_created_id' => Auth::guard('admin')->user()->id,
                    ]
                );
                // Tạo mới thông tin điểm danh học sinh
                $params['class_attendance_id'] = $attendance->id;
                $params['student_id'] = $student_id;
                AttendanceStudent::create($params);
            }
            DB::commit();
            return $this->sendResponse('success', __('Lưu thông tin thành công!'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->sendResponse('error', __($ex->getMessage()));
        }
    }

    public function storeImagePath($image_base64, $directory, $file_name = null)
    {
        // Bỏ tiền tố base64 (nếu có)
        $image = str_replace('data:image/png;base64,', '', $image_base64);
        $image = str_replace(' ', '+', $image);

        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        // Tên file (ví dụ: student_4314.png)
        $fileName = $file_name != null ? $file_name : 'image_' . time() . '.png';
        // Đường dẫn đầy đủ để lưu ảnh
        $filePath = "{$directory}/{$fileName}";
        // Lưu ảnh vào thư mục public
        file_put_contents($filePath, base64_decode($image));
        // Trả về đường dẫn ảnh
        $publicPath = $directory . "/{$fileName}";
        return $publicPath;
    }
    function isBase64($string)
    {
        return (bool) preg_match('/^data:image\/(png|jpeg|jpg|gif);base64,/', $string);
    }
}
