<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\StaffAdmission;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\tbClass;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->viewPart = 'admin.pages.home';
        $this->responseData['module_name'] = __('Welcome to Admin System!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('admin')->user()->status == 'deactive') {
            return redirect()->route('test_staff.index');
        }
        //all admin
        $admin = Admin::where('status', Consts::STATUS['active'])->get();

        //CBTS
        $admission = $admin->filter(function ($item) {
            return $item->admin_type == Consts::ADMIN_TYPE['admission'];
        });
        $this->responseData['admission'] = isset($admission) ? $admission->count() : "0";

        //Giáo viên
        $teacher = $admin->filter(function ($item) {
            return $item->admin_type == Consts::ADMIN_TYPE['teacher'];
        });
        $this->responseData['teacher'] = isset($teacher) ? $teacher->count() : "0";

        $students = $admin->filter(function ($item) {
            return $item->admin_type == Consts::ADMIN_TYPE['student'];
        });
        // Nhân viên cấp dưới 
        // $list_child = DataPermissionService::getPermissionUsersAndSelfAll(Auth::guard('admin')->user()->id);
        // List permissions student 
        // $student_permissions = DataPermissionService::getPermissionStudents(Auth::guard('admin')->user()->id);
        // Sinh viên học thử
        $trial_student = $students->filter(function ($item) {
            return $item->state == Consts::STUDENT_STATUS['try learning'];
        });
        $this->responseData['trial_student'] = isset($trial_student) ? $trial_student->count() : "0";

        // Sinh viên học chính
        $student = $students->filter(function ($item) {
            return $item->state == Consts::STUDENT_STATUS['main learning'];
        });
        $this->responseData['student'] = isset($student) ? $student->count() : "0";

        //Sinh viên đã thanh lý của bạn

        $student_liquidation_of_admission = $students->filter(function ($item) {
            return $item->status_study == 11;
        });
        $this->responseData['student_liquidation_of_admission'] = isset($student_liquidation_of_admission) ? $student_liquidation_of_admission->count() : "0";

        //khóa học mới khai giảng
        $params_course['order_by'] = ['day_opening' => 'desc'];
        $list_course = Course::getSqlCourse($params_course)->limit(10)->get();
        foreach ($list_course as $course) {
            $course->count_student = Student::where('course_id', $course->id)->count();
        }
        $this->responseData['list_course'] = $list_course;

        //lớp học mới khai giảng
        $list_class = tbClass::getSqlClass()->orderBy('start_date', 'desc')->limit(10)->get();
        $this->responseData['list_class'] = $list_class;
        return $this->responseView($this->viewPart . '.index');
    }
}
