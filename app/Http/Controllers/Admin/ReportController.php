<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\StatusStudent;
use App\Models\UserClass;
use App\Models\Area;
use App\Models\Admin;
use App\Models\TimekeepingTeacher;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\tbClass;
use App\Models\Period;
use App\Http\Services\DataPermissionService;
use App\Http\Services\KpiService;
use App\Http\Services\TimekeepingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Syllabus;
use App\Models\KpiTeacher;
use App\Models\Course;
use App\Models\Room;
use App\Models\Score;
use App\Models\Dormitory;
use App\Exports\DeptExportVersion1;
use App\Exports\DeptExportVersion2;
use App\Exports\ReportClassUpB1ByMonth;
use App\Models\Dormitory_user;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentDept;
use App\Models\Certificate;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    protected $lervel_a;
    protected $lervel_b;
    protected $lervel;
    public function __construct()
    {
        $this->routeDefault  = 'reports';
        $this->viewPart = 'admin.pages.reports';
        $this->responseData['module_name'] = 'Report Management';
        $this->lervel_a = [1, 2, 3, 4];
        $this->lervel_b = [5, 6];
        $this->lervel = [1, 2, 3, 4, 5, 6];
    }

    public function reportStudentStatus(Request $request)
    {
        $admin =  Auth::guard('admin')->user()->id;
        $status_study = $request->status_study;
        $area_id = $request->area_id;
        if ($area_id > 0) {
            $params_class['area_id'] = $area_id;
            $params_class['permission'] = DataPermissionService::getPermissionClasses($admin);
            $class = tbClass::getSqlClass($params_class)->get();
            $params['area_id'] = $area_id;
            $params['status'] = Consts::STATUS['active'];
            $params['keyword'] = $request->keyword;
            $params['class_id'] = $request->class_id;

            if ($status_study > 0) {
                $params['status_study'] = $status_study;
            } else {
                $params['status_study_null'] = true;
            }
            $list_student = Student::getSqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

            $result['html'] = view($this->viewPart . '.area_view_ajax', compact('list_student', 'class', 'params'))->render();
            $result['pagination'] = (string) $list_student->links();
            // return $this->responseView($this->viewPart . '.area');
            return $this->sendResponse($result, 'Thấy thông tin thành công');
        }

        $this->responseData['module_name'] = __('Thống kê trạng thái học viên theo khu vực');
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['admin'] =  $admin;

        // Lấy list id khu vực được quản lý
        $service  = new DataPermissionService;
        $list_area = $service->getPermisisonAreas($admin);

        $this->responseData['list_area'] = DB::table('tb_areas')
            ->select('tb_areas.*')
            ->selectRaw('COUNT(admins.id) AS total_student')
            ->leftJoin('admins', function ($join) {
                $join->on('tb_areas.id', '=', 'admins.area_id')
                    ->where('admins.admin_type', Consts::ADMIN_TYPE['student']);
            })
            ->whereIn('tb_areas.id', $list_area)
            ->groupBy('tb_areas.id')
            ->get();
        // Lấy danh sách trạng thái
        $this->responseData['list_status']  = StatusStudent::getSqlStatusStudent()->get();
        // Lấy danh sách học viên
        $params_student['status'] = Consts::STATUS['active'];
        $this->responseData['list_student']  = Student::getSqlStudent($params_student)->get();

        return $this->responseView($this->viewPart . '.area');
    }

    public function reportStudentLevels(Request $request)
    {
        $level_id = $request->level_id;
        if ($level_id > 0) {
            // Lấy danh sách tổng họp học viên theo lớp
            $params['level_id'] = $level_id;
            $params['status'] = Consts::STATUS['active'];
            $list_student = Student::getSqlStudent($params)->get();

            return $this->sendResponse($list_student);
        }

        $this->responseData['module_name'] = __('Thống kê học viên theo trình độ');
        $list_level = Level::getSqlLevel()->get();
        $this->responseData['list_level'] = $list_level;
        // Lấy danh sách học viên
        $params_student['status'] = Consts::STATUS['active'];
        $this->responseData['list_student']  = Student::getSqlStudent($params_student)->get();
        return $this->responseView($this->viewPart . '.level');
    }

    public function reportTeacher(Request $request)
    {
        $teacher_type = $request->teacher_type;
        if ($teacher_type) {
            // Lấy danh sách tổng họp học viên theo lớp
            $params['teacher_type'] = $teacher_type;
            $params['status'] = Consts::STATUS['active'];
            $list_teacher = Teacher::getSqlTeacher($params)->get();
            return $this->sendResponse($list_teacher);
        }
        $params_teacher['status'] = Consts::STATUS['active'];
        $list_teacher = Teacher::getSqlTeacher($params_teacher)->get();
        $this->responseData['module_name'] = __('Thống kê danh sách giáo viên');
        $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
        $this->responseData['list_teacher'] = $list_teacher;
        // Lấy danh sách học viên
        return $this->responseView($this->viewPart . '.teacher');
    }

    public function reportClassExceeds(Request $request)
    {
        $params = $request->all();
        if (isset($params['from_date']) || isset($params['to_date'])) {
            if ($params['from_date'] == '' || $params['to_date'] == '') {
                return redirect()->back()->with('errorMessage', __('Cần nhập đầy đủ ngày bắt đầu và ngày kết thúc!'));
            }
        }
        $params['search_from_date']  =  $params['from_date'] ?? '';
        $params['search_to_date']  =  $params['to_date'] ?? '';
        unset($params['from_date']);
        unset($params['to_date']);
        $this->responseData['class_status'] =  Consts::CLASS_STATUS;
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher()->get();
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $paramCourse['status'] = Consts::STATUS['active'];
        $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
        $this->responseData['areas'] =  Area::getsqlArea()->get();
        $this->responseData['rooms'] =  Room::getSqlRoom()->get();

        // Không đếm buổi học GVNN
        // $params['type']  =  'gv';
        $rows = tbClass::getSqlClass($params)->where('tb_schedules.type', '=', 'gv')->withCount('schedules')
            // ->having('schedules_count', '>', DB::raw('lesson_number'))
            ->havingRaw('schedules_count > CASE WHEN lesson = 0 THEN lesson_number ELSE lesson END')
            ->get();
        if ($params['search_from_date'] != '' || $params['search_to_date'] != '') {
            $rows = $rows->filter(function ($item) use ($params) {
                // từ ngày nằm trong khoảng bắt đầu đến kết thúc
                // Hoặc đến ngày nằm trong khoảng bắt đầu đến kết thúc
                if (($item->day_start <= $params['search_from_date'] && $item->day_end >= $params['search_from_date']) ||
                    ($item->day_start <= $params['search_to_date'] && $item->day_end >= $params['search_to_date'])
                ) {
                    return $item;
                }
            });
        }
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Thống kê lớp học quá buổi');
        return $this->responseView($this->viewPart . '.class_exceeds');
    }
    public function indexReportClassEnding(Request $request)
    {
        $params = $request->all();
        $params['level_id'] = 4;
        $rows = tbClass::getSqlClassEnding($params)->havingRaw('total_schedules - total_attendance <= 15')->havingRaw('total_schedules - total_attendance > 0')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['class_status'] =  Consts::CLASS_STATUS;
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher()->get();
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $paramCourse['status'] = Consts::STATUS['active'];
        $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
        $this->responseData['areas'] =  Area::getsqlArea()->get();
        $this->responseData['rooms'] =  Room::getSqlRoom()->get();
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Thống kê lớp học sắp kết thúc');
        return $this->responseView($this->viewPart . '.class_ending');
    }
    public function indexReportAttendanceByDay(Request $request)
    {
        $params = $request->all();
        $params['date'] = isset($params['date']) ? $params['date'] : date('Y-m-d', time());
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $schedule = Schedule::getSqlSchedule($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        foreach ($schedule as $val) {
            $val->quantity_student = UserClass::where('class_id', $val->class_id)->get()->count();
        }
        $this->responseData['rows'] = $schedule;
        $this->responseData['params'] = $params;

        $class = tbClass::getsqlClass($params['permission'])->get();
        $this->responseData['class'] = $class;

        $teacher = Teacher::getsqlTeacher()->get();
        $this->responseData['teacher'] = $teacher;

        $area = Area::getsqlArea()->get();
        $this->responseData['area'] = $area;

        $this->responseData['status'] = Consts::SCHEDULE_STATUS;

        $this->responseData['module_name'] = __('Thống kê điểm danh theo ngày');
        return $this->responseView($this->viewPart . '.attendacebyday');
    }

    public function indexReportAllAttendanceByDay(Request $request)
    {
        $params = $request->all();
        $params['date'] = isset($params['date']) ? $params['date'] : date('Y-m-d', time());
        $params['permission'] =  DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $params['not_attendance'] = true;

        $rows = Attendance::getSqlAttendance($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        $class = tbClass::getsqlClass($params['permission'])->get();
        $this->responseData['class'] = $class;

        $area = Area::getsqlArea()->get();
        $this->responseData['area'] = $area;

        $this->responseData['status'] = Consts::ATTENDANCE_STATUS;

        $this->responseData['module_name'] = __('Thống kê vắng mặt - học muộn theo ngày');
        return $this->responseView($this->viewPart . '.allattendacebyday');
    }


    public function indexReportAttendanceByMonth(Request $request)
    {
        $params = $request->all();
        $params['month'] = isset($params['month']) ? $params['month'] : date('Y-m', time());
        $monthYear = explode('-', $params['month']);
        $params['year'] = $monthYear[0];
        $params['months'] = $monthYear[1];
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $params['list_id'] = DataPermissionService::getPermissionStudents(Auth::guard('admin')->user()->id);
        $params['late_absent'] = true;
        $attendance = Attendance::getReportSqlAttendanceByMonth($params)->get();
        $this->responseData['rows'] = $attendance;
        $this->responseData['params'] = $params;
        // dd($attendance);
        $params_class['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $class = tbClass::getsqlClass($params_class['permission'])->get();
        $this->responseData['class'] = $class;

        $area = Area::getsqlArea()->get();
        $this->responseData['area'] = $area;
        $this->responseData['module_name'] = __('Thống kê điểm danh theo tháng');
        return $this->responseView($this->viewPart . '.attendacebymonth');
    }

    /**
     * Báo cáo chấm công full
     */
    public function indexReportTimekeepingTeacher(Request $request)
    {
        $params = $request->all();
        $params['month'] = isset($params['month']) ? $params['month'] : date('Y-m', time());
        // Check nếu đăng nhập là GV sẽ chỉ xem của chính GV đó
        $params['teacher_id'] = Auth::guard('admin')->user()->admin_type == 'teacher' ? Auth::guard('admin')->user()->id : null;
        $TimekeepingService = new TimekeepingService($params['month'], $params['teacher_id'], $params['keyword'] ?? '');
        $this->responseData['teachers'] = $TimekeepingService->reportTimekeeping();
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Báo cáo chấm công giáo viên trong tháng ' . date('m-Y', strtotime($params['month'])));
        return $this->responseView($this->viewPart . '.timekeepingteacher');
    }
    /** Báo cáo chấm công giáo viên theo dạng lịch  */
    public function indexReportTimekeepingCalender(Request $request)
    {
        set_time_limit(120);
        $params = $request->all();
        $params['month'] = isset($params['month']) ? $params['month'] : date('Y-m', time());
        // Check nếu đăng nhập là GV sẽ chỉ xem của chính GV đó
        $params['teacher_id'] = Auth::guard('admin')->user()->admin_type == 'teacher' ? Auth::guard('admin')->user()->id : null;
        $TimekeepingService = new TimekeepingService($params['month'], $params['teacher_id'], $params['keyword'] ?? '');
        $this->responseData['day_in_month'] = $TimekeepingService->getDayInMonth($params['month']);
        $this->responseData['teachers'] = $TimekeepingService->reportTimekeepingCalender();
        $this->responseData['params'] = $params;
        $this->responseData['periods'] = Period::getSqlPeriod()->get();
        $this->responseData['day_week_mini'] = Consts::DAY_WEEK_MINI;
        $this->responseData['module_name'] = __('Báo cáo chấm công giáo viên trong tháng ' . date('m-Y', strtotime($params['month'])));
        return $this->responseView($this->viewPart . '.timekeepingcalender');
    }

    public function detailReportTimekeepingTeacher(Request $request)
    {
        $params = $request->all();
        if (isset($params['teacher_id']) && isset($params['month']) && (Auth::guard('admin')->user()->admin_type != 'teacher' || (Auth::guard('admin')->user()->admin_type == 'teacher' && Auth::guard('admin')->user()->id == $params['teacher_id']))) {

            $monthYear = explode('-', $params['month']);
            $params['year'] = $monthYear[0];
            $params['months'] = $monthYear[1];
            $teacher = Teacher::find($params['teacher_id']);
            $schedule = Schedule::where('teacher_id', $params['teacher_id'])->whereMonth('tb_schedules.date', $params['months'])->whereYear('tb_schedules.date', $params['year'])->orderBy('date')->orderBy('tb_schedules.id')->get();
            $this->responseData['rows'] = $schedule;
            $this->responseData['module_name'] = __('Chi tiết chấm công giáo viên ' . ($teacher->name ?? "") . ' trong tháng ' . date('m-Y', strtotime($params['month'])));
            return $this->responseView($this->viewPart . '.detail_timekeepingteacher');
        } else return redirect()->back()->with('errorMessage', __('Bạn không truy cập được danh sách buổi học của giáo viên này'));
    }

    public function indexReportScoreStudent(Request $request)
    {
        $params = $request->all();
        $list_class = tbClass::getsqlClass()->orderBy('id', 'DESC')->get();
        $this->responseData['list_class'] = $list_class;
        $this->responseData['list_course'] = Course::getSqlCourse()->get();
        $this->responseData['list_level'] = Level::getSqlLevel()->get();
        $this->responseData['module_name'] = 'Tổng hợp danh sách học viên trượt và thi lại';
        $this->responseData['params'] = $params;
        $this->responseData['rank'] = Consts::ranked_academic;
        $params['list_id'] = DataPermissionService::getPermissionStudents(Auth::guard('admin')->user()->id);
        $rows = Score::getsqlScore($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        return $this->responseView($this->viewPart . '.scoreStudent');
    }

    public function studenLearnAgain(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $list_id = DataPermissionService::getPermissionStudents($admin->id);
        $params['list_id'] = $list_id;
        $params['status'] = "hoclai";
        $students = UserClass::getSqlUserClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $students;
        $this->responseData['module_name'] = __('Thống kê học viên học lại');
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.student');
    }
    public function classEnd(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $list_id = DataPermissionService::getPermissionStudents($admin->id);
        $params['list_id'] = $list_id;
        $params['status'] = "hoclai";
        $students = UserClass::getSqlUserClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $students;
        $this->responseData['module_name'] = __('Thống kê học viên học lại');
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.student');
    }

    public function ajaxReportAttendanceByDay(Request $request)
    {
        try {
            $params = $request->all();
            $rows = Attendance::getSqlAttendance($params)->groupBy('class_id')->get();
            foreach ($rows as $row) {
                $row->status = __($row->status ?? "");
                $row->resson = __($row->json_params->value ?? "");
                $row->is_contact_to_parents = __($row->json_params->is_contact_to_parents ?? "");
                $row->parents_method = __($row->json_params->parents_method ?? "");
                $row->note = $row->note ?? "";
                $row->date = $row->date != "" ? date('d-m-Y', strtotime($row->date)) : "";
                $row->link_student = route('students.show', $row->user_id);
                $row->link_class = route('classs.edit', $row->class_id);
                $row->link_attendance = route('attendances.index', ['schedule_id' => $row->schedule_id]);
            }
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function reportClassAttendanceEmpty(Request $request)
    {
        $params = $request->all();
        $class = tbClass::getSqlClassAtendanceEmpty($params)->get();
        $this->responseData['class'] = $class;
        $area = Area::getsqlArea()->get();
        $this->responseData['area'] = $area;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Thống kê lớp học thiếu điểm danh');
        return $this->responseView($this->viewPart . '.attendace_empty');
    }

    public function ajaxReportStudentlearnAgain(Request $request)
    {
        try {
            $params = $request->all();
            $rows = UserClass::where('status', 'hoclai')->where('user_id', $params['user_id'])->get();
            foreach ($rows as $row) {
                $row->status = Consts::USER_CLASS_STATUS[$row->status];
                $row->link_student = route('students.show', $row->user_id);
                $row->link_class = route('classs.edit', $row->class_id);
            }
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function ajaxConfirmStudent(Request $request)
    {
        try {
            $params = $request->all();
            $student = Student::find($request->id);
            if (isset($student)) {
                $student->update(['ketoan_xacnhan' =>  $request->confirm]);
            }

            if ($student) {
                return $this->sendResponse($student, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function indexReportStudentDept(Request $request)
    {
        $params = $request->all();
        $params['ketoan_xacnhan'] = isset($params['ketoan_xacnhan']) ? $params['ketoan_xacnhan'] : 'unpaid';

        $this->responseData['module_name'] = __('Công nợ - học viên sắp đến hạn đóng tiền');
        $this->responseData['course'] =  Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $this->responseData['class'] =  tbClass::getsqlClass()->get();
        $this->responseData['ketoan'] = Consts::KETOAN_XACNHAN;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        $this->responseData['version_dept'] =  Consts::VERSION_DEPT;
        $this->responseData['contract_type'] =  Consts::CONTRACT_TYPE;

        if (isset($params['version']) && $params['version'] == "version1") {
            $params_class['level_id'] = 4;
            $params['array_class_id'] = tbClass::getSqlClassEnding($params_class)
                ->havingRaw('total_schedules - total_attendance <= 15')
                ->havingRaw('total_schedules - total_attendance > 0')
                ->get()->pluck('id')->toArray();
            $rows = UserClass::getSqlUserClassDept($params)->orderBy('class_id', "desc")->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
            $this->responseData['rows'] =  $rows;
        }

        if (isset($params['version']) && $params['version'] == "version2") {
            $params['day_official'] = true;
            $rows = Student::getsqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
            $this->responseData['rows'] =  $rows;
        }

        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.student_dept');
    }
    public function storeStudentDeptImport(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
        $path = $request->file('file');
        $param = $request->except('file');
        $import = new StudentDept($param);
        Excel::import($import, $path);
        $errorMessages = $import->getErrorMessages();
        $mess = "";
        foreach ($errorMessages as $val) {
            $mess .= '</br>' . $val;
        };
        return redirect()->route('report.student.is.debt', ['version' => $request->version])->with('successMessage', __('Thực hiện thành công!  ' . $mess));
    }
    public function exportStudentDeptVersion1(Request $request)
    {
        $params = $request->all();
        return Excel::download(new DeptExportVersion1($params), 'Báo cáo công nợ-' . date("d-m-Y", time()) . '.xlsx');
    }
    public function exportStudentDeptVersion2(Request $request)
    {
        $params = $request->all();
        return Excel::download(new DeptExportVersion2($params), 'Báo cáo công nợ-' . date("d-m-Y", time()) . '.xlsx');
    }
    public function ajaxUpdateNoteKeepingTeacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $schedule = Schedule::find($request->id);
            if (isset($schedule)) {
                $updateResult =  $schedule->update([
                    'json_params->note_keeping_teacher' => $request->note,
                ]);
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }

    public function ajaxUpdateExceed(Request $request)
    {
        try {
            $classs = tbClass::find($request->id);
            if (isset($classs)) {
                $update = $classs->update([
                    'json_params->note_exceed' => $request->note,
                ]);
            }
            if ($update) return $this->sendResponse($update, 'success');
            else return $this->sendResponse("", 'error');
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }

    public function kpiTeacher(Request $request)
    {
        $teachers = Teacher::getsqlTeacher()->get();
        $this->responseData['teacher'] =  $teachers;

        $year = $request->year;
        $teacher_id = $request->teacher_id;
        $this->responseData['params'] =  $request->all();
        // Màn hình chi tiết KPI 1 GV
        if ($year > 0 && $teacher_id > 0) {
            try {
                $name_teacher = Teacher::find($request->teacher_id);
                $this->responseData['name_teacher'] = isset($name_teacher) ? $name_teacher->name : "";

                $kpi_detail = KpiTeacher::where('teacher_id', $request->teacher_id)->where('kpi_year', $request->year)->first();
                $this->responseData['kpi_detail'] = $kpi_detail;
                /**
                 * lớp kết thúc trong năm và tính KPI tiến độ đào tạo
                 */
                $getClassInYear = KpiService::getClassInYear($teacher_id, $year);
                if ($getClassInYear) {
                    //Tính tổng sô học viên của lớp giáo viên dạy trong năm
                    $uniqueStudents = $getClassInYear
                        ->map(function ($item) {
                            if (in_array($item->level_id, [5, 6, 20, 25])) { // Chỉ lấy B1,OTCS và Sau OTCS
                                return $item->students->pluck('id'); // Lấy danh sách id học viên của từng lớp
                            }
                            return collect(); // Trả về collection rỗng nếu không hợp lệ
                        })
                        ->flatten() // Gộp tất cả các danh sách id lại
                        ->unique(); // Loại bỏ id trùng lặp
                    $totalUniqueStudentsInYear = Student::whereIn('id', $uniqueStudents)->get();
                    $totalUniqueStudentsInYear = $totalUniqueStudentsInYear->map(function ($row) {
                        $list_class_attendance = Attendance::selectRaw(('GROUP_CONCAT(DISTINCT tb_attendances.class_id) as list_class'))->where('user_id', $row->id)->first();
                        $array_list_class_attendance = [];
                        if ($list_class_attendance) {
                            $array_list_class_attendance = explode(',', $list_class_attendance->list_class);
                        }
                        $user_class = UserClass::selectRaw(('GROUP_CONCAT(DISTINCT tb_user_class.class_id) as list_class'))->where('user_id', $row->id)->first();
                        $array_list_class_user = [];
                        if ($user_class) {
                            $array_list_class_user = explode(',', $user_class->list_class);
                        }
                        $all_class = array_merge($array_list_class_attendance, $array_list_class_user);
                        $row->list_class = tbClass::whereIn('id', $all_class)->get();
                        return $row;
                    });
                    $this->responseData['totalUniqueStudentsInYear'] = $totalUniqueStudentsInYear;
                    //end tính tổng sô học viên của lớp giáo viên dạy trong năm

                    $total_schedules = 0;
                    $lesson_number = 0;
                    foreach ($getClassInYear as $item) {
                        if ($item->lesson_number > 0) {
                            $item->delay = round(($item->total_schedules - $item->lesson_number) / $item->lesson_number, 3);
                        } else {
                            $item->delay = 0;
                        }
                        $total_schedules += $item->total_schedules;
                        $lesson_number += $item->lesson_number;
                    }
                    $delay_ratio = $lesson_number > 0 ? ($total_schedules - $lesson_number) / $lesson_number : 1; //trung bình tiếnđộ
                    $kpi_process = KpiService::calculatorKpiLearnProcess($delay_ratio);

                    $this->responseData['getClassInYear'] =  $getClassInYear;
                    $this->responseData['total_schedules'] =  $total_schedules;
                    $this->responseData['lesson_number'] =  $lesson_number;
                    $this->responseData['kpi_process'] =  round($kpi_process * 100, 2);
                    $this->responseData['delay_ratio'] =  round($delay_ratio * 100, 2);
                }

                /**
                 * lớp có điểm trong năm và tính KPI kết quả đào tạo
                 */
                $getClassHasScoreInYear = KpiService::getClassHasScoreInYear($teacher_id, $year);

                //tổng KPI các trình độ (để tính trung bình)
                $total_kpi_a1 = 0;
                $total_kpi_a2 = 0;
                $total_kpi_b1 = 0;

                //đếm lớp A1
                $class_a1 = $getClassHasScoreInYear->filter(function ($it) {
                    return  $it->level_id == 1 || $it->level_id == 2;
                })->count();
                //Lớp A2
                $class_a2 = $getClassHasScoreInYear->filter(function ($it) {
                    return  $it->level_id == 3 || $it->level_id == 4;
                })->count();
                //Lớp B1
                $class_b1 = $getClassHasScoreInYear->filter(function ($it) {
                    return  $it->level_id == 5 || $it->level_id == 6;
                })->count();

                foreach ($getClassHasScoreInYear as $item) {
                    $item->kpi = KpiService::redirectKpiLearnScore($item);
                    $item->density = Consts::KPI_CONFIG['learn_score'][$item->level->name]['density'];
                    if ($item->level_id == 1 || $item->level_id == 2) $total_kpi_a1 += 100 * ($item->kpi['percent_receive']);
                    if ($item->level_id == 3 || $item->level_id == 4) $total_kpi_a2 += 100 * ($item->kpi['percent_receive']);
                    if ($item->level_id == 5 || $item->level_id == 6) $total_kpi_b1 += 100 * ($item->kpi['percent_receive']);
                }

                $avager_kpi_a1 = $class_a1 > 0 ? $total_kpi_a1 / $class_a1 : 0;
                $avager_kpi_a2 = $class_a2 > 0 ? $total_kpi_a2 / $class_a2 : 0;
                $avager_kpi_b1 = $class_b1 > 0 ? $total_kpi_b1 / $class_b1 : 0;

                $total_kpi = ($avager_kpi_a1 + $avager_kpi_a2 + $avager_kpi_b1) * Consts::KPI_CONFIG['learn_score']['total_percent_kpi'];

                $this->responseData['getClassHasScoreInYear'] =  $getClassHasScoreInYear;
                $this->responseData['avager_kpi_a1'] =  $avager_kpi_a1;
                $this->responseData['avager_kpi_a2'] =  $avager_kpi_a2;
                $this->responseData['avager_kpi_b1'] =  $avager_kpi_b1;
                $this->responseData['total_kpi'] =  $total_kpi;

                /**
                 * Lấy danh sách điểm thi chứng chỉ B1 theo GV
                 */
                $certificates = Certificate::getSqlCertificate()
                    ->where('tb_certificate.teacher_id', $teacher_id)
                    ->where(function ($where) use ($year) {
                        return $where->orWhereYear('tb_certificate.day_score_listen', $year)
                            ->orWhereYear('tb_certificate.day_score_speak', $year)
                            ->orWhereYear('tb_certificate.day_score_read', $year)
                            ->orWhereYear('tb_certificate.day_score_write', $year);
                    })
                    ->get();
                $this->responseData['certificates'] =  $certificates;

                return $this->responseView($this->viewPart . '.kpi_teacher');
            } catch (Exception $ex) {
                session()->flash('errorMessage', $ex->getMessage());
                return $this->responseView($this->viewPart . '.kpi_teacher');
            }
        }
        // Màn hình danh sách KPI toàn bộ GV
        else if ($year > 0) {
            try {
                foreach ($teachers as $key => $teacher) {
                    $kpi_detail = KpiTeacher::where('teacher_id', $teacher->id)->where('kpi_year', $request->year)->first();
                    $teacher['kpi_year'] = $kpi_detail->kpi_year ?? '';
                    $teacher['kpi_class'] = $kpi_detail->kpi_class ?? 6;
                    $teacher['kpi_behavior'] = $kpi_detail->kpi_behavior ?? 4;
                    /**
                     * lớp kết thúc trong năm và tính KPI tiến độ đào tạo
                     */
                    $getClassInYear = KpiService::getClassInYear($teacher->id, $year);
                    if ($getClassInYear) {
                        $total_schedules = 0;
                        $lesson_number = 0;
                        foreach ($getClassInYear as $item) {
                            if ($item->lesson_number > 0) {
                                $item->delay = round(($item->total_schedules - $item->lesson_number) / $item->lesson_number, 3);
                            } else {
                                $item->delay = "";
                            }
                            $total_schedules += $item->total_schedules;
                            $lesson_number += $item->lesson_number;
                        }
                        $delay_ratio = $lesson_number > 0 ? ($total_schedules - $lesson_number) / $lesson_number : 1;
                        $kpi_process = KpiService::calculatorKpiLearnProcess($delay_ratio);

                        $teacher['total_schedules'] =  $total_schedules;
                        $teacher['lesson_number'] =  $lesson_number;
                        $teacher['kpi_process'] =  round($kpi_process * 100, 2);
                        $teacher['delay_ratio'] =  round($delay_ratio * 100, 2);
                    }

                    /**
                     * lớp có điểm trong năm và tính KPI kết quả đào tạo
                     */
                    $getClassHasScoreInYear = KpiService::getClassHasScoreInYear($teacher->id, $year);

                    //tổng KPI các trình độ (để tính trung bình)
                    $total_kpi_a1 = 0;
                    $total_kpi_a2 = 0;
                    $total_kpi_b1 = 0;

                    //đếm lớp A1
                    $class_a1 = $getClassHasScoreInYear->filter(function ($it) {
                        return  $it->level_id == 1 || $it->level_id == 2;
                    })->count();
                    //Lớp A2
                    $class_a2 = $getClassHasScoreInYear->filter(function ($it) {
                        return  $it->level_id == 3 || $it->level_id == 4;
                    })->count();
                    //Lớp B1
                    $class_b1 = $getClassHasScoreInYear->filter(function ($it) {
                        return  $it->level_id == 5 || $it->level_id == 6;
                    })->count();

                    foreach ($getClassHasScoreInYear as $item) {
                        $item->kpi = KpiService::redirectKpiLearnScore($item);
                        $item->density = Consts::KPI_CONFIG['learn_score'][$item->level->name]['density'];
                        if ($item->level_id == 1 || $item->level_id == 2) $total_kpi_a1 += 100 * ($item->kpi['percent_receive']);
                        if ($item->level_id == 3 || $item->level_id == 4) $total_kpi_a2 += 100 * ($item->kpi['percent_receive']);
                        if ($item->level_id == 5 || $item->level_id == 6) $total_kpi_b1 += 100 * ($item->kpi['percent_receive']);
                    }

                    $avager_kpi_a1 = $class_a1 > 0 ? $total_kpi_a1 / $class_a1 : 0;
                    $avager_kpi_a2 = $class_a2 > 0 ? $total_kpi_a2 / $class_a2 : 0;
                    $avager_kpi_b1 = $class_b1 > 0 ? $total_kpi_b1 / $class_b1 : 0;

                    $total_kpi = ($avager_kpi_a1 + $avager_kpi_a2 + $avager_kpi_b1) * Consts::KPI_CONFIG['learn_score']['total_percent_kpi'];

                    $teacher['avager_kpi_a1'] =  $avager_kpi_a1;
                    $teacher['avager_kpi_a2'] =  $avager_kpi_a2;
                    $teacher['avager_kpi_b1'] =  $avager_kpi_b1;
                    $teacher['total_kpi'] =  $total_kpi;
                }

                $this->responseData['teachers'] =  $teachers;

                return $this->responseView($this->viewPart . '.kpi_teacher_list');
            } catch (Exception $ex) {
                session()->flash('errorMessage', $ex->getMessage());
                return $this->responseView($this->viewPart . '.kpi_teacher');
            }
        }

        return $this->responseView($this->viewPart . '.kpi_teacher');
    }

    public function AjaxkpiTeacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $update = KpiTeacher::updateOrCreate(
                [
                    'teacher_id' => $request->teacher_id,
                    'kpi_year' => $request->kpi_year
                ],
                [
                    'kpi_class' => $request->kpi_class,
                    'kpi_behavior' => $request->kpi_behavior,
                    'kpi_total' => $request->kpi_total,
                    'time_report' => $request->time_report,
                ]
            );
            if ($update) {
                DB::commit();
                return $this->sendResponse($update, 'success');
            } else {
                DB::rollBack();
                return $this->sendResponse("", 'error');
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }

    // Báo cáo xếp loại lớp theo trình độ
    public function reportRankingLevelClass(Request $request)
    {
        $params = $request->all();
        // Lấy danh sách lớp đã chấm điểm
        $params['list_level'] = $this->lervel;
        $list_class = tbClass::getSqlClass($params)->whereNotNull('is_score')->get();

        // Tổng hợp các trình độ của các lớp đã có điểm
        $list_level = $list_class->map(function ($class) {
            return $class->level;
        })->unique()->sortBy(function ($level) {
            return $level->id;
        });

        // tính tỉ lệ điểm của mỗi lớp
        foreach ($list_class as $class) {
            $score = Score::where('class_id', $class->id)->get();
            $class->person_fail = round($score->where('status', 'fail')->count() / count($score) * 100, 2);
            $class->person_pass = round($score->where('status', 'pass')->count() / count($score) * 100, 2);
            $class->person_level_up = round($score->where('status', 'level_up')->count() / count($score) * 100, 2);
            $class->person_need_try = round($score->where('status', 'need_try')->count() / count($score) * 100, 2);
            $class->person_pass_write = round($score->where('status', 'pass_write')->count() / count($score) * 100, 2);
            $class->person_pass_speak = round($score->where('status', 'pass_speak')->count() / count($score) * 100, 2);
            $class->person_pass_full = round($score->where('status', 'pass_full')->count() / count($score) * 100, 2);
            $class->total_student = count($score);
        }

        // tính  % theo trình độ A
        foreach ($list_level as $level) {
            $level->class = $list_class->where('level_id', $level->id);
            $level->total_person_fail = round($level->class->sum('person_fail') / count($level->class), 2);
            $level->total_person_pass = round($level->class->sum('person_pass') / count($level->class), 2);
            $level->total_person_level_up = round($level->class->sum('person_level_up') / count($level->class), 2);
            $level->total_person_need_try = round($level->class->sum('person_need_try') / count($level->class), 2);
            $level->total_person_pass_write = round($level->class->sum('person_pass_write') / count($level->class), 2);
            $level->total_person_pass_speak = round($level->class->sum('person_pass_speak') / count($level->class), 2);
            $level->total_person_pass_full = round($level->class->sum('person_pass_full') / count($level->class), 2);
        }
        // Tách riêng trình độ B để hiển thị ra view
        [$list_level_B, $list_level] = $list_level->partition(function ($item) {
            return in_array($item->id, $this->lervel_b);
        });

        $this->responseData['list_level'] =  $list_level;
        $this->responseData['list_level_B'] =  $list_level_B;
        $this->responseData['areas'] =  Area::getsqlArea()->get();
        $this->responseData['teachers'] = Teacher::getsqlTeacher()->get();;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = 'Báo cáo tỉ lệ xếp loại lớp theo trình độ';
        return $this->responseView($this->viewPart . '.ranking_level_class');
    }

    // Báo cáo DANH SÁCH HỌC VIÊN DỰ KIẾN LÊN TRÌNH B1 trong tháng
    public function reportClassUpB1ByMonth(Request $request)
    {
        $this->responseData['levels'] =  Level::getSqlLevel()->whereIn('id', [1, 2, 3, 4, 5, 6])->get();
        $params['level_id'] = $request['level_id'] ?? 4;
        // Lấy danh sách lớp đang học trình độ A2.2 (level_id = 4) => bổ sung điều kiện lọc cả level
        $list_class_all = tbClass::select('tb_classs.*')
            ->selectRaw('MIN(tb_schedules.date) AS day_start, MAX(tb_schedules.date) AS day_end')
            ->selectRaw('MAX(CASE WHEN tb_schedules.is_add_more IS NULL THEN tb_schedules.date END) AS day_end_expected')
            ->where('tb_classs.level_id', '=', $params['level_id'])
            ->where('tb_classs.type', '!=', 'elearning') // k lấy lớp học elearning
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->groupBy('tb_classs.id')
            ->orderBy('day_end')
            ->get();
        // Tổng hợp các lớp trong tháng hiện tại (mặc định)
        $startOfMonth = Carbon::now()->startOfMonth(); // Ngày đầu tiên của tháng hiện tại
        $endOfMonth = Carbon::now()->endOfMonth(); // Ngày cuối cùng của tháng hiện tại
        // Các tham số filter
        $params['class_id'] = $request['class_id'] ?? null;
        $params['keyword'] = $request['keyword'] ?? null;
        $params['from_date'] = !empty($request['from_date']) ? Carbon::parse($request['from_date']) : $startOfMonth;
        $params['to_date'] = !empty($request['to_date']) ? Carbon::parse($request['to_date']) : $endOfMonth;
        // Lọc ra các lớp có ngày kết thúc hoặc dự kiến trong tháng
        $list_class = $list_class_all->filter(function ($class) use ($params) {
            $day_end = Carbon::parse($class['day_end'])->addDay(); // Lấy thêm mỗi ngày kết thúc + 1 ngày
            if ($params['class_id'] > 0) {
                return $day_end >= $params['from_date'] && $day_end <= $params['to_date'] && $class->id == $params['class_id'];
            }
            return $day_end >= $params['from_date'] && $day_end <= $params['to_date'];
        });
        // Duyệt để lọc ra danh sách học viên theo lớp và bổ sung dữ liệu theo học viên
        $list_class = $list_class->map(function ($class) use ($params) {
            $class['day_start'] = Carbon::parse($class['day_start'])->format('d/m/Y');
            $class['day_end_level'] = Carbon::parse($class['day_end'])->addDay()->format('d/m/Y');
            $class['day_end_level_expected'] = Carbon::parse($class['day_end_expected'])->addDay()->format('d/m/Y');
            // Lọc các học viên nếu có điều kiện tìm kiếm
            $students = $class->students->filter(function ($student) use ($params) {
                if ($params['keyword'] != null) {
                    return Str::contains($student->admin_code, $params['keyword']) || Str::contains($student->name, $params['keyword']);
                }
                return $student;
            });
            // Duyệt điểm theo từng học viên trong lớp
            $students = $students->map(function ($student) use ($class) {
                $score = $class->scores->first(function ($score) use ($student) {
                    return $score->user_id == $student->id;
                }, null);
                $student['xep_loai'] = $score->status ?? null;
                return $student;
            });
            $class->students = $students;
            return $class;
        });
        $this->responseData['params'] = $params;
        $this->responseData['list_class_all'] = tbClass::whereIn('tb_classs.level_id', [1, 2, 3, 4, 5, 6])
            ->where('tb_classs.type', '!=', 'elearning')->get(); // Danh sách lớp phục vụ cho filter bên ngoài
        $this->responseData['list_class'] = $list_class;
        $this->responseData['module_name'] = 'DANH SÁCH THEO DÕI HỌC VIÊN LÊN TRÌNH';
        return $this->responseView($this->viewPart . '.up_b1_by_month');
    }
    public function exportReportClassUpB1ByMonth(Request $request)
    {
        $params = $request->all();
        return Excel::download(new ReportClassUpB1ByMonth($params), 'Danh_sach_hoc_vien.xlsx');
    }
}
