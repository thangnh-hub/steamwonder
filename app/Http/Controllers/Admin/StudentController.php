<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Role;
use App\Models\Score;
use App\Models\StatusStudent;
use App\Models\tbClass;
use App\Models\Major;
use App\Models\Field;
use App\Models\EntryLevel;
use App\Models\History;
use App\Models\Student;
use App\Models\RankAcademic;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\UserClass;
use App\Models\Syllabus;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\StaffAdmission;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use App\Http\Services\HistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Exports\StudentExport;
use App\Exports\StudentUpdateCbtsExport;
use App\Exports\TrialStudentExport;
use App\Imports\StudentImport;
use App\Imports\Cbts_StudentImport;
use App\Imports\TrialStudentImport;
use App\Imports\UpdateCbtsImport;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Decision;
use App\Models\ExamSessionUser;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use stdClass;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'students';
        $this->viewPart = 'admin.pages.students';
        $this->responseData['module_name'] = 'Students Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // Get list post with filter params
        $rows = Student::getsqlStudentIndex($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->where('status', Consts::STATUS['active'])
            ->get();
        $class = tbClass::getsqlClass()->get();
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $year_offical = range(Carbon::now()->subYears(5)->year, Carbon::now()->addYears(5)->year);

        $this->responseData['rows'] =  $rows;
        $this->responseData['staffs'] =  $staffs;
        $this->responseData['course'] =  $course;
        $this->responseData['class'] =  $class;
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['year_offical'] = $year_offical;
        $this->responseData['field'] = Field::getsqlField()->get();
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.index');
    }
    public function getReserved(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        $params['status_study'] = 7;
        // Get list post with filter params

        $rows = Student::getsqlStudentIndex($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->where('status', Consts::STATUS['active'])
            ->get();
        $class = tbClass::getsqlClass()->get();
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['staffs'] =  $staffs;
        $this->responseData['course'] =  $course;
        $this->responseData['class'] =  $class;
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.reserved');
    }

    public function importStudent()
    {
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['module_name'] = 'Students Import';
        $params['admin_type'] = Consts::ADMIN_TYPE['admission'];
        $rows = StaffAdmission::getsqlStaffAdmission($params)->get();
        $this->responseData['rows'] = $rows;
        $params_area['status'] = Consts::STATUS['active'];
        $area = Area::getsqlArea()->get();
        $this->responseData['area'] =  $area;
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['field'] = Field::getsqlField()->get();
        return $this->responseView($this->viewPart . '.import');
    }
    public function importStudent_CBTS()
    {
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['module_name'] = 'Students Import';
        $rows = StaffAdmission::getsqlStaffAdmission()->get();
        $this->responseData['rows'] = $rows;
        $params_area['status'] = Consts::STATUS['active'];
        $area = Area::getsqlArea()->get();
        $this->responseData['area'] =  $area;
        $params_course['offline'] = true;
        $params_course['status'] = Consts::STATUS['active'];
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $courses = Course::getSqlCourse($params_course)->get();
        $this->responseData['courses'] =  $courses;
        $this->responseData['dormitory'] = Consts::DORMITORY;
        return $this->responseView($this->viewPart . '.import_cbts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $major = Major::getsqlMajor()->get();
        $class = tbClass::getsqlClass()->get();
        $field = Field::getsqlField()->get();
        $entryLevel = EntryLevel::getsqlEntryLevel()->get();
        $params_course['offline'] = true;
        $params_course['status'] = Consts::STATUS['active'];
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $courses = Course::getSqlCourse($params_course)->get();

        $params['status'] = Consts::STATUS['active'];
        $params['admin_type'] = Consts::ADMIN_TYPE['admission'];
        $staffadmissions = StaffAdmission::getSqlStaffAdmission($params)->get();
        // $teacher = Teacher::getsqlTeacher()->get();
        $admission = $staffadmissions;

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $this->responseData['admission'] =  $admission;
        $this->responseData['class'] =  $class;
        $this->responseData['major'] =  $major;
        $this->responseData['field'] =  $field;
        $this->responseData['courses'] =  $courses;
        $this->responseData['entry_level'] =  $entryLevel;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['dormitory'] = Consts::DORMITORY;

        $this->responseData['status'] = StatusStudent::get();
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['forms_training'] = Consts::FORMS_TRAINING;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['contract_performance_status'] = Consts::CONTRACT_PERFORMANCE_STATUS;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
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
        DB::beginTransaction();
        try {
            $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
            $params = $request->all();

            if (isset($params['import']) && isset($params['file'])) {
                if ($this->checkFileImport($params['file']) == false) {
                    return redirect()->back()->with('errorMessage', 'File Import không hợp lệ, có chứa Sheet ẩn !');
                }
                $import = new StudentImport($params);
                Excel::import($import, request()->file('file'));
                if ($import->hasError) {
                    return redirect()->back()->with('errorMessage', $import->errorMessage);
                }
                $data_count = $import->getRowCount();
                $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'];
                DB::commit();
                return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Thực hiện thành công!  ' . $mess));
            }

            if (isset($params['cbts_import']) && isset($params['file'])) {
                $_datawith = 'successMessage';

                if ($this->checkFileImport($params['file']) == false) {
                    return redirect()->back()->with('errorMessage', 'File Import không hợp lệ, có chứ Sheet ẩn !');
                }


                $import = new Cbts_StudentImport($params);
                Excel::import($import, request()->file('file'));
                if ($import->hasError) {
                    return redirect()->back()->with('errorMessage', $import->errorMessage);
                }
                $data_count = $import->getRowCount();
                $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
                foreach ($data_count['error_mess'] as $val) {
                    $mess .= '</br>' . $val;
                };
                if (count($data_count['error_mess']) > 0) {
                    $_datawith = 'errorMessage';
                };
                DB::commit();

                $admin = Auth::guard('admin')->user();
                if ($admin->role == 11) {
                    return redirect()->route('student.cskh')->with($_datawith, $mess);
                }
                return redirect()->route($this->routeDefault . '.index')->with($_datawith, $mess);
            }
            if (isset($params['lang'])) {
                $lang = $params['lang'];
                unset($params['lang']);
            }
            $request->validate(
                [
                    'name' => "required|max:255",
                    'area_id' => "required",
                    'gender' => "required",
                    'course_id' => "required",
                    // 'json_params.cccd' => 'required|unique:admins,json_params->$.cccd',
                ],
                [
                    'name.required' => "Cần nhập họ và tên",
                    'area_id.required' => "Cần chọn khu vực",
                    'gender.required' => "Cần chọn giói tính",
                    'course_id.required' => "Cần chọn khóa học",
                    // 'json_params.cccd.required' => 'CCCD là bắt buộc.',
                    // 'json_params.cccd.unique' => 'CCCD đã tồn tại. Vui lòng nhập một CCCD khác.'
                ]
            );

            if (isset($params['json_params']['cccd']) && $params['json_params']['cccd'] != '') {
                $check_cccd = Student::whereJsonContains('admins.json_params->cccd', $params['json_params']['cccd'])->count();
                if ($check_cccd > 0) {
                    return redirect()->back()->with('errorMessage', __('CCCD đã tồn tại. Vui lòng nhập một CCCD khác.'));
                }
            } else {
                return redirect()->back()->with('errorMessage', __('Cần nhập CCCD!'));
            }

            if (isset($params['admin_code'])) {
                $params['admin_code'] = $params['admin_code'];
            } else {
                // Find the last admin code
                $lastAdmin = Student::orderBy('id', 'desc')->first();
                $lastAdminCode = $lastAdmin->id ?? 0;
                // Extract the numeric part and increment it
                $numericPart = (int)$lastAdminCode;
                // Calculate the number of digits required for the numeric part
                $numDigits = max(4, strlen((string)$numericPart));
                // Add one to the numeric part
                $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
                $params['admin_code'] = 'HT' . $newNumericPart;
            }
            if (isset($params['email'])) {
                $params['email'] = $params['email'];
            } else {
                $uniqueTimestamp = isset($params['admin_code']) ? $params['admin_code'] : microtime(true);
                $email = $uniqueTimestamp . '@tuhoctiengduc.vn';
                $params['email'] = $email;
            }

            // Trạng thái học viên khi thêm mới sẽ mặc định là nhập học
            $params['status_study'] = 1;

            $params['state'] = Consts::STUDENT_STATUS['try learning'];
            $params['admin_type'] = Consts::ADMIN_TYPE['student'];
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $params['json_params']['field_id'] = $request->field_id;
            // $params['name'] = $params['json_params']['last_name'] . ' ' . $params['json_params']['middle_name'] . ' ' . $params['json_params']['first_name'];
            $params['role'] = 0; // add role 0 for students
            $student = Student::create($params);
            // Thêm lịch sử trạng thái cho học viên (Mới đầu mặc định là nhập học)
            $history = HistoryService::addHistoryStatusStudy($student->id, '', 1);

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->bach()->with('errorMessage', $ex->getMessage());
            abort(422, __($ex->getMessage()));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $history_students = History::where('student_id', $student->id)->orderBy('updated_at')->get();
        $this->responseData['history_student'] = $history_students;

        $list_class_attendance = Attendance::selectRaw(('GROUP_CONCAT(DISTINCT tb_attendances.class_id) as list_class'))->where('user_id', $student->id)->first();
        $array_list_class_attendance = [];
        if ($list_class_attendance) {
            $array_list_class_attendance = explode(',', $list_class_attendance->list_class);
        }

        $user_class = UserClass::selectRaw(('GROUP_CONCAT(DISTINCT tb_user_class.class_id) as list_class'))->where('user_id', $student->id)->first();
        $array_list_class_user = [];
        if ($user_class) {
            $array_list_class_user = explode(',', $user_class->list_class);
        }
        $all_class = array_merge($array_list_class_attendance, $array_list_class_user);

        // $list_class = tbClass::whereIn('id', $all_class)->get();
        $params_class['id'] = $all_class;
        $list_class = tbClass::getSqlClass($params_class)->get();
        $this->responseData['detail'] = $student;
        $this->responseData['module_name'] = 'Thông tin sinh viên: ' . $student->name;
        // Chỉ lấy ra các nhận xét đánh giá đã có status là active của học viên đó (giáo viên đã lưu)
        $list_evolution = Evaluation::where('tb_evaluations.status', 'active')->where('student_id', $student->id)->orderBy('tb_evaluations.from_date', 'DESC')->get();
        foreach ($list_class as  $item) {
            $item->lesson_number = $item->syllabus->lesson ?? ($item->lesson_number ?? 0);
            if (isset($item->json_params->teacher)) {
                $item->teacher = Admin::find($item->json_params->teacher)->name ?? "";
            } else $item->teacher = "";

            $check_history = History::where('student_id', $student->id)->where('class_id_new', $item->id)->where('type', Consts::HISTORY_TYPE['change_class'])->first();
            $item->day_in_class = isset($check_history->json_params->day_in_class) ? $check_history->json_params->day_in_class : "";
            $item->status = isset($check_history->status_change_class) ? $check_history->status_change_class : "";

            $params_score['class_id'] = $item->id;
            $params_score['user_id'] = $student->id;
            //Điểm nghe nói đọc viết
            $score = Score::getsqlScore($params_score)->first();
            //xếp loại
            (isset($score) && $score->status != '') ? $status_rank = $score->status : $status_rank = '';
            $item->status_rank = $status_rank;
            //nghe
            (isset($score) && $score->score_listen != '') ? $score_listen = $score->score_listen : $score_listen = '-';
            $item->score_listen = $score_listen;
            //nói
            (isset($score) && $score->score_speak != '') ? $score_speak = $score->score_speak : $score_speak = '-';
            $item->score_speak = $score_speak;
            //đọc
            (isset($score) && $score->score_read != '') ? $score_read = $score->score_read : $score_read = '-';
            $item->score_read = $score_read;
            //viết
            (isset($score) && $score->score_write != '') ? $score_write = $score->score_write : $score_write = '-';
            $item->score_write = $score_write;
            //trung bình
            isset($score->json_params->score_average) && $score->json_params->score_average != '' ? $score_average = $score->json_params->score_average : $score_average = '-';
            $item->score_average = $score_average;
            //Nhận xét điểm
            isset($score->json_params->note) && $score->json_params->note != '' ? $note_score = $score->json_params->note : $note_score = '-';
            $item->note_score = $note_score;
            //điểm danh
            $getsqlAttendance = Attendance::getsqlAttendance($params_score)->get();
            //có điểm danh
            $attendant = $getsqlAttendance->filter(function ($val, $key) {
                return $val->status == Consts::ATTENDANCE_STATUS['attendant'];
            });
            $item->attendant = $attendant->count();
            //vắng
            $absent = $getsqlAttendance->filter(function ($val, $key) {
                return $val->status == Consts::ATTENDANCE_STATUS['absent'];
            });
            $item->absent = $absent->count();
            //ngày vắng
            $params_absent['user_id'] = $student->id;
            $params_absent['class_id'] = $item->id;
            $params_absent['status'] = Consts::ATTENDANCE_STATUS['absent'];
            $list_absent = Attendance::getSqlAttendance($params_absent)->groupBy('class_id')->orderBy('date', 'asc')->get();
            $string_absent = "";
            foreach ($list_absent as $value) {
                $string_absent .= ", " . date('d/m', strtotime($value->date));
            }
            $string_absent = trim($string_absent, ", ");
            $item->string_absent = $string_absent;
            //Vắng có lý do
            $absent_has_reason = $absent->filter(function ($val, $key) {
                return $val->json_params->value == 'there reason';
            });
            isset($absent_has_reason) ? $has_reason = count($absent_has_reason) : $has_reason = 0;
            $item->absent_has_reason = $has_reason;
            //vắng không lý do
            $absent_no_reason = $absent->filter(function ($val, $key) {
                return $val->json_params->value == 'no reason' ;
            });
            isset($absent_no_reason) ? $no_reason = count($absent_no_reason) : $no_reason = 0;
            $item->absent_no_reason = $no_reason;


            //đi muộn
            $late = $getsqlAttendance->filter(function ($val, $key) {
                return $val->status == Consts::ATTENDANCE_STATUS['late'];
            });
            $item->late = $late->count();
            //số phút đi muộn
            $count_late = 0;
            foreach ($late as $value) {
                $count_late += $value->json_params->value;
            }
            $item->count_late = $count_late;
            //có làm bài
            $is_homework_have = $getsqlAttendance->filter(function ($val, $key) {
                return $val->is_homework == 0;
            });
            $item->is_homework_have = $is_homework_have->count();
            //ko làm bài
            $is_homework_not_have = $getsqlAttendance->filter(function ($val, $key) {
                return $val->is_homework == 1;
            });
            $item->is_homework_not_have = $is_homework_not_have->count();
            //làm nhưng k đủ
            $is_homework_did_not_complete = $getsqlAttendance->filter(function ($val, $key) {
                return $val->is_homework == 2;
            });
            $item->is_homework_did_not_complete = $is_homework_did_not_complete->count();
        }
        $this->responseData['list_class'] = $list_class;
        $this->responseData['list_evolution'] = $list_evolution;

        $other_list = [];
        foreach ($list_class as  $item) {
            $other_list[] = $item->id;
        }

        $params_class['type'] = 'lopchinh';
        $params_class['other_list'] = $other_list;
        $all_class = tbClass::getSqlClass($params_class)->get();
        $this->responseData['all_class'] = $all_class;
        $this->responseData['user_class_status'] = Consts::USER_CLASS_STATUS;

        // Get all đơn biến động liên quan tới học viên
        $decisions = Decision::getsqlDecision(['student_id' => $student->id])->get();
        $this->responseData['decisions'] = $decisions;

        return $this->responseView($this->viewPart . '.detail');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $admin_id = Auth::guard('admin')->user()->id;
        $DataPermissionService = new DataPermissionService;
        $list_students = $DataPermissionService->getPermissionStudents($admin_id);
        if (!in_array($student->id, $list_students)) {
            return redirect()->back()->with('errorMessage', __('Bạn không có quyền thao tác với dữ liệu của học viên này!'));
        }
        $major = Major::getsqlMajor()->get();
        $class = tbClass::getsqlClass()->get();
        $field = Field::getsqlField()->get();
        $entryLevel = EntryLevel::getsqlEntryLevel()->get();
        // $staff = Staff::getsqlStaff()->get();
        // $teacher = Teacher::getsqlTeacher()->get();
        // $admission = $staff;
        $params_course['offline'] = true;
        $params_course['status'] = Consts::STATUS['active'];
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $courses = Course::getSqlCourse($params_course)->get();

        $staffadmissions = StaffAdmission::getSqlStaffAdmission(['admin_type' => Consts::ADMIN_TYPE['admission']])->get();
        // $teacher = Teacher::getsqlTeacher()->get();
        $admission = $staffadmissions;

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $this->responseData['admission'] =  $admission;
        $this->responseData['class'] =  $class;
        $this->responseData['major'] =  $major;
        $this->responseData['field'] =  $field;
        $this->responseData['entry_level'] =  $entryLevel;
        $this->responseData['courses'] =  $courses;
        $this->responseData['dormitory'] = Consts::DORMITORY;
        $this->responseData['detail'] = $student;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        // $this->responseData['status'] = Consts::STATUS;
        $this->responseData['status'] = StatusStudent::get();
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['forms_training'] = Consts::FORMS_TRAINING;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['version'] = Consts::VERSION_DEPT;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['contract_performance_status'] = Consts::CONTRACT_PERFORMANCE_STATUS;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate(
            [
                'name' => "required|max:255",
                'email' => "required|email|max:255|unique:admins,email," . $student->id,
                'admin_code' => "required|max:255|unique:admins,admin_code," . $student->id,
                'gender' => "required",
                'area_id' => "required",
                'course_id' => "required",
                // 'json_params.cccd' => "required|unique:admins,json_params->cccd," . $student->id,
            ],
            [
                'name.required' => "Cần nhập họ và tên",
                'gender.required' => "Cần chọn giói tính",
                'area_id.required' => "Cần chọn khu vực",
                'course_id.required' => "Cần chọn khóa học",
                // 'json_params.cccd.required' => 'CCCD là bắt buộc.',
                // 'json_params.cccd.unique' => 'CCCD đã tồn tại. Vui lòng nhập một CCCD khác.'
            ]
        );
        DB::beginTransaction();
        try {
            $arr_lang_code = [];
            $all_lang = Language::where('status', Consts::STATUS['active'])->get();
            foreach ($all_lang as $val) {
                $arr_lang_code[] = $val->lang_code;
            }

            $admin_id = Auth::guard('admin')->user()->id;
            $DataPermissionService = new DataPermissionService;
            $list_students = $DataPermissionService->getPermissionStudents($admin_id);
            if (!in_array($student->id, $list_students)) {
                return redirect()->back()->with('errorMessage', __('Bạn không có quyền thao tác với dữ liệu của học viên này!'));
            }
            $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
            $params = $request->all();
            if (isset($params['lang'])) {
                $lang = $params['lang'];
                unset($params['lang']);
            }


            if (isset($params['json_params']['cccd']) && $params['json_params']['cccd'] != '') {
                $check_cccd = Student::where('id', '!=', $student->id)->whereJsonContains('admins.json_params->cccd', $params['json_params']['cccd'])->count();
                if ($check_cccd > 0) {
                    return redirect()->back()->with('errorMessage', __('CCCD đã tồn tại. Vui lòng nhập một CCCD khác.'));
                }
            } else {
                return redirect()->back()->with('errorMessage', __('Cần nhập CCCD!'));
            }

            $password_new = $request->input('password_new');
            if ($password_new != '') {
                if (strlen($password_new) < 8) {
                    return redirect()->back()->with('errorMessage', __('Password is very short!'));
                }
                $params['password'] = $password_new;
            }
            $params['json_params']['state'] = Consts::STUDENT_STATUS['try learning'];
            $params['admin_type'] = Consts::ADMIN_TYPE['student'];
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $params['json_params']['field_id'] = $request->field_id;

            $arr_insert = $params;
            // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
            if ($student->json_params != "") {
                foreach ($student->json_params as $key => $val) {
                    if (in_array($key, ['field_id'])) {
                        continue;
                    }
                    // if (isset($arr_insert['json_params'][$key])) {
                    //     if ($arr_insert['json_params'][$key] != null) {
                    if (isset($arr_insert['json_params'][$key])) {
                        if (is_array($params['json_params'][$key])) {
                            $key_lang = collect($params['json_params'][$key])->filter(function ($item, $key) use ($arr_lang_code) {
                                return in_array($key, $arr_lang_code);
                            });
                            if (count($key_lang) > 0) {
                                $arr_insert['json_params'][$key] = array_merge((array)$val, $params['json_params'][$key]);
                            } else {
                                $arr_insert['json_params'][$key] = $params['json_params'][$key] ?? $val;
                            }
                        }
                    } else {
                        $arr_insert['json_params'][$key] = $val;
                    }
                    //     }
                    // }
                }
            }

            // Tạo lịch sử thay đổi trạng thái
            HistoryService::addHistoryStatusStudy($student->id, $student->status_study, $request->status_study);
            // $check_history = History::where('student_id', $student->id)->where('status_study_old', $student->status_study)->where('status_study_new', $request->status_study)->where('type', Consts::HISTORY_TYPE['change_status_student'])->first();
            // if ($check_history == null) {
            //     $params_history['student_id'] = $student->id;
            //     $params_history['status_study_old'] = $student->status_study;
            //     $params_history['status_study_new'] = $request->status_study;
            //     $params_history['type'] = Consts::HISTORY_TYPE['change_status_student'];
            //     $params_history['admin_id_update'] = Auth::guard('admin')->user()->id;
            //     $history = History::create($params_history);
            // }


            $student->fill($arr_insert);
            $student->save();
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', $ex->getMessage());
            // abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $admin_id = Auth::guard('admin')->user()->id;
        $DataPermissionService = new DataPermissionService;
        $list_students = $DataPermissionService->getPermissionStudents($admin_id);
        if (!in_array($student->id, $list_students)) {
            return redirect()->back()->with('errorMessage', __('Bạn không có quyền thao tác với dữ liệu của học viên này!'));
        }
        $student->delete();
        UserClass::where('user_id', $student->id)->delete();
        Attendance::where('user_id', $student->id)->delete();
        Evaluation::where('student_id', $student->id)->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'id';
            $admin = Auth::guard('admin')->user();
            $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
            $rows = Student::getSqlStudent($params)->get();
            foreach ($rows as $item) {
                $item->birthday = $item->birthday != "" ? date('d-m-Y', strtotime($item->birthday)) : "";
                $class_to_str = '';
                $list_class = $item->allClassesWithStatus();
                if (isset($list_class)) {
                    $class_to_str .= '<ul>';
                    foreach ($list_class as $i) {
                        $class_to_str .= '<li>';
                        $class_to_str .= $i->name;
                        $class_to_str .=    '(' . __($i->pivot_status ?? '') . ')';
                        $class_to_str .= '</li>';
                    }
                    $class_to_str .= '</ul>';
                }
                $item->class_to_str = $class_to_str;
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
    public function searchEvaluation(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'id';
            // Get list post with filter params
            $rows = Evaluation::getSqlEvaluation($params)->get();

            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function printStudent(Request $request)
    {
        $student = Student::find($request->student_id);
        $this->responseData['list_class'] = UserClass::where('user_id', $student->id)->groupBy('class_id')->get();
        $this->responseData['detail'] = $student;
        $class_recent = UserClass::where('user_id', $request->student_id)->groupBy('class_id')->latest()->first();
        if ($class_recent) $class = tbClass::find($class_recent->class_id);

        $course = $class->course->name ?? "";

        $this->responseData['course'] = $course;
        $this->responseData['class'] = $class;
        return $this->responseView($this->viewPart . '.print');
    }

    public function export(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        return Excel::download(new StudentExport($params), 'Student.xlsx');
    }
    public function additionalClass(Request $request)
    {
        DB::beginTransaction();
        try {
            $list_class = $request['list_class'];
            $user_id = $request->user_id;
            if ($list_class) {
                foreach ($list_class as  $item) {
                    // Xử lý thêm vào lớp
                    $params3['class_id'] = $item['class_id'];
                    $params3['user_id'] = $user_id;
                    $params3['status'] = $item['user_class_status'];
                    $params3['json_params']['day_in_class'] = $item['day_in_class'];
                    $check_user_class = UserClass::where('user_id', $user_id)->where('class_id', $item['class_id'])->first();
                    if ($check_user_class == Null) {
                        UserClass::create($params3);
                        Student::where('id', $user_id)->update(['state' => Consts::STUDENT_STATUS['main learning']]);
                    }

                    $classs = tbClass::find($item['class_id']);
                    // Lịch sử thêm vào lớp
                    $params_history['student_id'] = $user_id;
                    $params_history['class_id_new'] = $item['class_id'];
                    $params_history['levels_id_new'] = $classs->level_id;
                    $params_history['syllabuss_id_new'] = $classs->syllabus_id;
                    $params_history['courses_id_new'] = $classs->course_id;
                    $params_history['status_change_class'] = $item['user_class_status'];
                    $params_history['type'] = Consts::HISTORY_TYPE['change_class'];
                    $params_history['admin_id_update'] = Auth::guard('admin')->user()->id;
                    $params_history['json_params']['day_in_class'] = $item['day_in_class'];

                    $check_history = History::where('student_id', $user_id)->where('class_id_new', $item['class_id'])->where('type', Consts::HISTORY_TYPE['change_class'])->first();

                    if ($check_history == null) {
                        History::create($params_history);
                    } else $check_history->update($params_history);

                    //xử lý nhập điểm + check tồn tại ít nhất 1 trong 4 điểm
                    if ($item['score_listen'] + $item['score_read'] + $item['score_write'] + $item['score_speak'] > 0) {
                        // Lấy ra và tính toán xếp loại theo syllabus
                        $syllabus = Syllabus::find($classs->syllabus_id);

                        $listen_weight = isset($syllabus->json_params->score->listen->weight) ? $syllabus->json_params->score->listen->weight : 25;
                        $speak_weight = isset($syllabus->json_params->score->speak->weight) ? $syllabus->json_params->score->speak->weight : 25;
                        $read_weight = isset($syllabus->json_params->score->read->weight) ? $syllabus->json_params->score->read->weight : 25;
                        $write_weight = isset($syllabus->json_params->score->write->weight) ? $syllabus->json_params->score->write->weight : 25;

                        $status = $score_average = "";
                        //telc
                        if ($syllabus->score_type == 'telc') {
                            //B1.1
                            if ($classs->level_id == 5) {
                                $group_score1 = $item['score_listen'] + $item['score_read'];
                                if ($group_score1 > 108) $status = 'pass_listen_read';
                                $score_average = "Modul Nghe và Đọc: " . $group_score1;
                            }
                            // B1.2
                            else {
                                $group_score1 = $item['score_listen'] + $item['score_read'] + $item['score_write'];
                                $group_score2 = $item['score_speak'];

                                if ($group_score1 > 135) $status = 'pass_write';
                                if ($group_score2 > 45)  $status = 'pass_speak';
                                if ($group_score1 > 135 && $group_score2 > 45) $status = 'pass_full';
                                $score_average = "Modul Viết: " . $group_score1 . ", Modul Nói: " . $group_score2;
                            }
                        }
                        //goethe
                        else {
                            $score_listen_weight = $listen_weight / 100;
                            $score_speak_weight = $speak_weight / 100;
                            $score_read_weight = $read_weight / 100;
                            $score_write_weight = $write_weight / 100;

                            $score_average = (($item['score_listen'] * $score_listen_weight) +
                                ($item['score_speak'] * $score_speak_weight) +
                                ($item['score_read'] * $score_read_weight) +
                                ($item['score_write'] * $score_write_weight));
                            $score_average   = (float)$score_average;

                            $rank = RankAcademic::where('level_id', $classs->level->id ?? "")->where('from_points', '<=', $score_average)->where('to_points', '>=', $score_average)->first();
                            $status = isset($rank) ? $rank->ranks : "";
                        }
                        // Check và xử lý lưu điểm
                        $params_score_item['json_params']['score_average'] = $score_average;
                        $params_score_item['json_params']['note'] = $item['note'];
                        $params_score_item['score_listen'] = $item['score_listen'];
                        $params_score_item['score_speak'] = $item['score_speak'];
                        $params_score_item['score_read'] = $item['score_read'];
                        $params_score_item['score_write'] = $item['score_write'];
                        $params_score_item['status'] = $status;
                        $params_score_item['user_id'] = $user_id;
                        $params_score_item['class_id'] = $item['class_id'];

                        $check_score = Score::where('class_id', $item['class_id'])->where('user_id', $user_id)->first();
                        if ($check_score == null) Score::create($params_score_item);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    public function additionalEvaluation(Request $request)
    {
        DB::beginTransaction();
        try {
            $params_student = $request['list'];
            if ($params_student) {
                foreach ($params_student as $item) {
                    $class = tbClass::find($item['class_id']);

                    $evaluation_params['json_params']['ability'] = $item['ability'];
                    $evaluation_params['json_params']['consciousness'] = $item['consciousness'];
                    $evaluation_params['json_params']['knowledge'] = $item['knowledge'];
                    $evaluation_params['json_params']['skill'] = $item['skill'];
                    $evaluation_params['student_id'] = $request->user_id;
                    $evaluation_params['teacher_id'] = $class->json_params->teacher ?? "";
                    $evaluation_params['class_id'] = $item['class_id'];
                    $evaluation_params['from_date'] = $item['from_date'];
                    $evaluation_params['to_date'] = $item['to_date'];
                    $evaluation_params['status'] = Consts::STATUS['active'];

                    $check_evaluation = Evaluation::where('student_id', $request->user_id)->where('class_id', $item['class_id'])
                        ->where('from_date', $item['from_date'])->where('to_date', $item['to_date'])->first();
                    if ($check_evaluation == Null) Evaluation::create($evaluation_params);
                    else return redirect()->back()->with('errorMessage', __('Đã tồn tại nhân xét sinh viên trong lớp và khoảng thời gian đã chọn'));
                }
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function trialStudent(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        $params['state'] = Consts::STUDENT_STATUS['try learning'];
        $rows = Student::getsqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->where('status', Consts::STATUS['active'])
            ->get();
        $class = tbClass::getsqlClass()->get();
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['staffs'] =  $staffs;
        $this->responseData['course'] =  $course;
        $this->responseData['class'] =  $class;
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        $this->responseData['module_name'] = __('Quản lý học viên học thử');
        return $this->responseView($this->viewPart . '.trial');
    }
    public function changeAdminCode(Request $request)
    {
        $id = $request->only('id')['id'];
        $code = $request->only('code')['code'];
        $student = Student::find($id);
        // kiểm tra admin code không trùng nhau
        $check = Student::where('admin_code', $code)->where('id', '!=', $id)->count();
        if ($check > 0) {
            return $this->sendResponse('warning', 'Mã học viên đã tồn tại!');
        }
        if ($student) {
            Student::where('id', $id)
                ->update([
                    "admin_code" => $code,
                    "json_params->trial_code" => $student->json_params->trial_code ?? $student->admin_code,
                ]);
            return $this->sendResponse('success', 'Cập nhật thành công!');
        }
    }

    public function exportTrialStudent(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        $params['state'] = Consts::STUDENT_STATUS['try learning'];
        return Excel::download(new TrialStudentExport($params), 'Trial_Student.xlsx');
    }
    public function importTrialStudent(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            if (isset($params['file'])) {
                $_datawith = 'successMessage';
                $import = new TrialStudentImport($params);
                Excel::import($import, request()->file('file'));
                if ($import->hasError) {
                    return redirect()->back()->with('errorMessage', $import->errorMessage);
                }
                $data_count = $import->getRowCount();
                $mess =  __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
                foreach ($data_count['error_mess'] as $val) {
                    $mess .= '</br>' . $val;
                };
                if (count($data_count['error_mess']) > 0) {
                    $_datawith = 'errorMessage';
                };
                DB::commit();
                return redirect()->route('trial_student.index')->with($_datawith, $mess);
            }
            return redirect()->back()->with('errorMessage', 'Cần nhập file đề Import!');
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    public function AjaxgetHistoryStatus(Request $request)
    {
        try {
            $student_id = $request->only('student_id')['student_id'];
            $type = $request->only('type')['type'] ?? '';
            $rows = History::where('student_id', $student_id)->where('type', Consts::HISTORY_TYPE['change_status_student'])->get();
            foreach ($rows as $row) {
                $row->updated_at_new = $row->updated_at != "" ? Carbon::parse($row->updated_at)->format('Y-m-d') : "";
            }
            $rows_end = $rows->last();
            $student = Student::find($student_id);
            $status_student = StatusStudent::getSqlStatusStudent()->get();
            $result['html'] = view($this->viewPart . '.view_history_status_study', compact('type', 'rows', 'rows_end', 'student', 'status_student'))->render();
            return $this->sendResponse($result, 'success');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function getTableHistory(Request $request)
    {
        try {
            $id = $request->only('id')['id'];
            $result['html'] = '';
            $history = History::find($id);
            if ($history) {
                $user_class_status = Consts::USER_CLASS_STATUS;
                $status_student = StatusStudent::getSqlStatusStudent()->get();
                $class = tbClass::getsqlClass()->get();
                $result['html'] = view($this->viewPart . '.view_table_edit_history', compact('history', 'user_class_status', 'status_student', 'class'))->render();
            }
            return $this->sendResponse($result, 'success');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function updateHistoryStatusStudy(Request $request)
    {
        try {
            $id = $request->only('id')['id'];
            $params = $request->except(['id']);
            $history = History::find($id);
            $history->fill($params);
            $history->save();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }




    public function addHistoryStatusStudy(Request $request)
    {
        try {
            $params = $request->all();
            if ($params['status_study_old'] != $params['status_study_new']) {
                $params_history['student_id'] = $params['student_id'];
                $params_history['status_study_old'] = $params['status_study_old'];
                $params_history['status_study_new'] = $params['status_study_new'];
                $params_history['type'] = Consts::HISTORY_TYPE['change_status_student'];
                $params_history['admin_id_update'] = Auth::guard('admin')->user()->id;
                $params_history['json_params']['note_status_study'] = $params['note_status_study'];
                $params_history['updated_at'] = $params['updated_at'];
                $history = History::create($params_history);
                if ($history) {
                    $student = Student::find($history->student_id);
                    $student->status_study = $history->status_study_new;
                    $student->save();
                }
            }

            return $this->sendResponse('success', 'Thêm mới thành công');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function deleteHistoryStatusStudy(Request $request)
    {
        try {
            $id = $request->only('id')['id'];
            History::where('id', $id)->delete();

            return $this->sendResponse('success', 'Xóa thông tin thành công!');
        } catch (Exception $ex) {
            return $this->sendResponse('warning', $ex->getMessage());
        }
    }

    /** Cập nhật lại thông tin History status */
    public function AjaxUpdateDayChangeStatus(Request $request)
    {
        try {
            $history_students = History::find($request->id);
            if (isset($history_students)) {
                $arr_data['updated_at'] = $request->date;
                $arr_data['status_study_old'] = $request->status_old;
                $arr_data['status_study_new'] = $request->status_new;
                $arr_data['admin_id_update'] = Auth::guard('admin')->user()->id;
                $arr_data['json_params']['note_status_study'] = $request->note;
                $history_students->update($arr_data);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }

    public function getDeleteStudentCBTS(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['state'] = Consts::STUDENT_STATUS['try learning'];
        $params['admission_id'] = $admin->id;
        $rows = Student::getsqlStudentIndex($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['admin_id'] =  $admin->id;
        $this->responseData['course'] =  $course;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = 'Xóa học viên học thử CBTS';
        return $this->responseView($this->viewPart . '.cbts_delete');
    }

    public function postDeleteStudentCBTS(Request $request)
    {
        $params = $request->all();
        DB::beginTransaction();
        try {
            $delete = Student::whereIn("id", $params['id'])->where('admission_id', Auth::guard('admin')->user()->id)->delete();
            if ($delete) {
                Attendance::whereIn('user_id', $params['id'])->delete();
                Evaluation::whereIn('student_id', $params['id'])->delete();
                ExamSessionUser::whereIn('user_id', $params['id'])->delete();
            }
            session()->flash('successMessage', 'Xóa học viên thành công!');
            DB::commit();
            return $this->sendResponse('successMessage', 'Xóa học viên thành công!');
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function listStudentCSKH(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // Get list post with filter params

        $rows = Student::getsqlStudentAccounting($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->where('status', Consts::STATUS['active'])
            ->get();
        $class = tbClass::getsqlClass()->get();
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['staffs'] =  $staffs;
        $this->responseData['course'] =  $course;
        $this->responseData['class'] =  $class;
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['field'] = Field::getsqlField()->get();
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.index_cskh');
    }
    public function cskhUpdateStudent(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->except('id');
            $id = $request->id ?? '';
            if ($id != '') {
                $student = Student::find($id);
                $arr_data['json_params'] = (array) $student->json_params;
                foreach ($params['json_params'] as $key => $value) {
                    $arr_data['json_params'][$key] = $value;
                }
                $student->update($arr_data);
            }

            DB::commit();
            return $this->sendResponse('success', 'Cập nhật thành công!');
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendResponse('warning', $ex->getMessage());
        }
    }

    public function listUpdateCBTS(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_admission_id'] = DataPermissionService::getPermissionUsersAndSelfAll($admin->id);
        // Get list post with filter params
        $rows = Student::getSqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        $this->responseData['module_name'] = 'Danh sách học viên trong hệ thống của bạn';
        return $this->responseView($this->viewPart . '.list_update_cbts');
    }

    public function exportListUpdateCBTS(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        return Excel::download(new StudentUpdateCbtsExport($params), 'student.xlsx');
    }

    public function importListUpdateCBTS(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            if (isset($params['file'])) {
                if ($this->checkFileImport($params['file']) == false) {
                    $_datawith = 'errorMessage';
                    $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
                    session()->flash($_datawith, $mess);
                    return $this->sendResponse($_datawith, $mess);
                }
                $_datawith = 'successMessage';
                $import = new UpdateCbtsImport($params);
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
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    // Delete history student
    public function deleteHistory($id)
    {
        try {
            History::where('id', $id)->delete();
            return redirect()->back()->with('successMessage', 'Xóa lịch sử thành công!');
        } catch (Exception $ex) {
            return redirect()->back()->with('errorMessage', 'Xóa lịch sử không thành công!');
        }
    }
}
