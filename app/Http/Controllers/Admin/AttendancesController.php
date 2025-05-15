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
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $params = $request->only(['status', 'checkin_parent_id', 'checkin_teacher_id', 'json_params']);
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
            if ($params['json_params']['img']) {
                // Xóa ảnh cũ nếu có
                if ($attendance_student && $attendance_student->json_params->img) {
                    $oldFilePath = public_path($attendance_student->json_params->img);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                // Nếu có ảnh mới thì lưu vào thư mục
                // Bỏ tiền tố base64 (nếu có)
                $image = str_replace('data:image/png;base64,', '', $params['json_params']['img']);
                $image = str_replace(' ', '+', $image);
                // Ngày hiện tại (để tạo thư mục)
                $today = Carbon::parse($tracked_at)->format('dmY');
                // Định nghĩa đường dẫn thư mục
                $directory = "data/attendance/{$today}/{$class_id}";
                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                // Tên file (ví dụ: student_4314.png)
                $fileName = 'student_' . $student_id . '_' . time() . '.png';
                // Đường dẫn đầy đủ để lưu ảnh
                $filePath = "{$directory}/{$fileName}";
                // Lưu ảnh vào thư mục public
                file_put_contents($filePath, base64_decode($image));
                // Trả về đường dẫn ảnh
                $publicPath = "data/attendance/{$today}/{$class_id}/{$fileName}";
            }
            $params['json_params']['img'] = $publicPath ?? null;
            // Đã có thì cập nhật
            // Chưa có thì tạo mới
            if ($attendance_student) {
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function show(Attendances $attendances)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendances $attendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendances $attendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendances $attendances)
    {
        //
    }
}
