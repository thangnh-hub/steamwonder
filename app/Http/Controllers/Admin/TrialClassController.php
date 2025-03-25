<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Room;
use App\Models\Attendance;
use App\Models\Score;
use App\Models\UserClass;
use App\Models\tbClass;
use App\Models\Schedule;
use App\Models\Level;
use App\Models\Area;
use App\Models\Holiday;
use App\Models\Syllabus;
use App\Models\LessonSylabu;
use App\Models\Period;
use App\Models\Course;
use App\Models\History;
use App\Models\StaffAdmission;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;
use Exception;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;


class TrialClassController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'trial_classs';
        $this->viewPart = 'admin.pages.trialclass';
        $this->responseData['module_name'] = 'Quản lý lớp học thử';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $params = $request->all();
        $params['type'] = 'lopphu';
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        // Get list post with filter params
        $rows = tbClass::getSqlClass($params)->orderBy('id','desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramCourse['status'] = Consts::STATUS['active'];
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
        $this->responseData['areas'] =  Area::getsqlArea()->get();
        $this->responseData['rooms'] =  Room::getSqlRoom()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status_class'] = Consts::CLASS_STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SummaryClass(Request $request)
    {
        $params = $request->all();
        $areas = Area::getsqlArea()->get();
        $teachers = Teacher::getsqlTeacher()->get();
        // Get list post with filter params

        $rows = tbClass::getSqlClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramCourse['status'] = Consts::STATUS['active'];
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['areas'] =  $areas;
        $this->responseData['teachers'] =  $teachers;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['module_name'] = 'Class summary report';

        return $this->responseView($this->viewPart . '.summary_class');
    }
    public function SummaryClassAttendance(Request $request)
    {
        $params = $request->all();
        // $params['teacher_id'] = Auth::guard('admin')->user()->id;
        $areas = Area::getsqlArea()->get();
        $teachers = Teacher::getsqlTeacher()->get();
        // Get list post with filter params
        if (isset($params['school_day'])) {
            $params['school_day'] = $params['school_day'];
        } else {
            $params['school_day'] = 0;
        }
        $rows = tbClass::getSqlClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramCourse['status'] = Consts::STATUS['active'];
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['areas'] =  $areas;
        $this->responseData['teachers'] =  $teachers;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['module_name'] = 'Class attendance report';

        return $this->responseView($this->viewPart . '.summary_class_attendance');
    }
    public function create()
    {
        $paramSyllabus['approve'] = Consts::APPROVE[1];
        $paramStatus['status'] = Consts::STATUS['active'];

        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->get();
        $this->responseData['course'] = Course::getSqlCourse($paramStatus)->get();
        $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
        $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
        $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['status_class'] = Consts::CLASS_STATUS;

        // dd($this->responseData);
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
        $params_holiday['status'] = Consts::STATUS['active'];
        $holidays = Holiday::getsqlHoliday($params_holiday)->get();
        $arr_holiday = [];
        foreach ($holidays as $key => $holiday) {
            $params_holi      = strtotime($holiday->date);
            array_push($arr_holiday, $params_holi);
        }
        $request->validate([
            'name' => 'required',
            // 'name' => 'required|unique:tb_classs',
            'start_date' => "required",
            'period_id' => "required",
            'level_id' => "required",
            'syllabus_id' => "required",
            'course_id' => "required",
            'area_id' => "required",
            'room_id' => "required",
            'json_params.teacher' => "required",
            'json_params.day_repeat' => "required",
        ]);
        $schedule = LessonSylabu::where('syllabus_id', $request->syllabus_id)->get();
        $scheduleCount = $schedule->count();
        $params = $request->all();
        $class = tbClass::create($params);

        if ($class && isset($schedule) && $scheduleCount > 0) {
            //Ngày học của buổi
            $time_bd = strtotime($request->start_date);
            $list = array();
            $day = date('d', $time_bd);
            $month = date('m', $time_bd);
            $year = date('Y', $time_bd);

            $buoi = 0;
            $arr_day_repeat = $request->json_params['day_repeat'];
            for ($thang = $month; $thang <= 12; $thang++) {
                for ($d = 1; $d <= 31; $d++) {
                    $time = strtotime($year . '-' . $thang . '-' . $d);
                    if (date('m', $time) == $thang && $time >= $time_bd && in_array(date('w', $time), $arr_day_repeat) && !in_array($time, $arr_holiday)) {
                        $list[] = date('Y-m-d', $time);
                        $buoi++;
                        if ($buoi == $scheduleCount) break;
                    }
                }
                if ($buoi == $scheduleCount) break;
            }

            if ($buoi < $scheduleCount) {
                $month = 1;
                $year += 1;
                for ($thang = $month; $thang <= 12; $thang++) {
                    for ($d = 1; $d <= 31; $d++) {
                        $time = strtotime($year . '-' . $thang . '-' . $d);
                        if (date('m', $time) == $thang && $time >= $time_bd && in_array(date('w', $time), $arr_day_repeat) && !in_array($time, $arr_holiday)) {
                            $list[] = date('Y-m-d', $time);
                            $buoi++;
                            if ($buoi == $scheduleCount) break;
                        }
                    }
                    if ($buoi == $scheduleCount) break;
                }
            }
            $data = [];
            foreach ($schedule as $key => $item) {
                $file = json_encode($item->json_params->file);
                $params2['date']      = $list[$key];
                $params2['period_id'] = $request->period_id;
                $params2['room_id']   = $request->room_id;
                $params2['class_id']  = $class->id;
                $params2['area_id']   = $request->area_id;
                $params2['teacher_id'] = $request->json_params['teacher'];
                $params2['status'] = 'chuahoc';
                $params2['file'] = $file;

                array_push($data, $params2);
            }
            $lesson = Schedule::insert($data);
        }

        // return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        return redirect()->route($this->routeDefault . '.edit', $class->id)->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classs = tbClass::find($id);
        $day_end = Schedule::where('class_id', $classs->id)->max('date');
        $day_end_expected  = Schedule::where('class_id', $classs->id)->where('is_add_more', Null)->max('date');
        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
        $params['class_id'] = $classs->id;
        $this->responseData['this_class'] = $classs;
        $this->responseData['rows'] = Student::getsqlStudent($params)->get();
        $this->responseData['staffs'] = StaffAdmission::getSqlStaffAdmission()->get();
        $this->responseData['teacher'] =  Teacher::getSqlTeacher()->get();
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['day_end'] =  $day_end;
        $this->responseData['day_end_expected'] =  $day_end_expected;
        return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classs = tbClass::find($id);
        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
        $paramSyllabus['approve'] = Consts::APPROVE[1];
        $paramStatus['status'] = Consts::STATUS['active'];

        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->get();
        $this->responseData['course'] = Course::getSqlCourse($paramStatus)->get();
        $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
        $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
        $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
        $this->responseData['detail'] = $classs;

        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_lesson'] = Schedule::where('class_id', $classs->id)->orderBy('date', 'asc')->get();
        $this->responseData['list_class'] = tbClass::where('id', "!=", $classs->id)->where('type', 'lopphu')->get();
        $this->responseData['student'] = UserClass::where('class_id', $classs->id)->groupBy('user_id')->get();
        $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
        $this->responseData['status_atten'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
        $this->responseData['status_class'] = Consts::CLASS_STATUS;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $classs = tbClass::find($id);
        $request->validate([
            'name' => 'required',
            // 'name' => 'required|unique:tb_classs,name,' . $classs->id,
        ]);
        $params = $request->only(['name', 'day_exam', 'area_id', 'room_id', 'json_params', 'status']);
        $arr_insert = $params;
        $classs->fill($arr_insert);
        $classs->save();

        if ($classs->save()) {
            $params_lesson = $request['lesson'];

            if ($params_lesson) {
                $uniqueAssistantTeachers = [];
                foreach ($params_lesson as $item) {

                    if ($item['id'] != Null || $item['id'] != "") {
                        $lesson = Schedule::find($item['id']);

                        $params2['date'] = $item['date'];
                        $params2['period_id'] = $item['period_id'];
                        $params2['room_id'] = $item['room_id'];
                        if ($lesson->status != 'dadiemdanh') {
                            $params2['teacher_id'] =  $item['teacher_id'];
                        }
                        $params2['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                        $lesson->fill($params2);
                        $lesson->save();
                    } else {
                        $params2['class_id'] = $classs->id;
                        $params2['area_id'] = $classs->area_id;
                        $params2['date'] = $item['date'];
                        $params2['period_id'] = $item['period_id'];
                        $params2['room_id'] = $item['room_id'];
                        $params2['teacher_id'] = $item['teacher_id'];
                        $params2['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                        // $params2['file'] = json_encode($item['file']);
                        $params2['is_add_more'] = 1;
                        $lesson = Schedule::insert($params2);
                    }
                    if (isset($item['assistant_teacher'])) {
                        foreach ($item['assistant_teacher'] as $assistantTeacher) {
                            // Nếu giáo viên trợ giảng chưa tồn tại trong mảng $uniqueAssistantTeachers, thêm vào
                            if (!in_array($assistantTeacher, $uniqueAssistantTeachers)) {
                                $uniqueAssistantTeachers[] = $assistantTeacher;
                            }
                        }
                    }
                }
            }
            $param_classs = $request->only(['assistant_teacher']);
            if (isset($param_classs['assistant_teacher'])) {
                $updateResult =  $classs->update([
                    'assistant_teacher' => $param_classs['assistant_teacher'],
                ]);
            } else {
                $updateResult =  $classs->update([
                    'assistant_teacher' => $uniqueAssistantTeachers ?? '',
                ]);
            }

            $params_student = $request['student'];

            UserClass::where('class_id', $classs->id)->delete();
            if ($params_student) {
                foreach ($params_student as $t => $item) {
                    $params3['class_id'] = $classs->id;
                    $params3['user_id'] = $item;
                    $params3['status'] = $request['user_class_status'][$t];
                    UserClass::create($params3);
              

                    $params_history['student_id'] = $item;
                    $params_history['class_id_new'] = $classs->id;
                    $params_history['levels_id_new'] = $classs->level_id;
                    $params_history['syllabuss_id_new'] = $classs->syllabus_id;
                    $params_history['courses_id_new'] = $classs->course_id;
                    $params_history['status_change_class'] = $request['user_class_status'][$t];
                    $params_history['type'] = Consts::HISTORY_TYPE['change_class'];
                    $params_history['admin_id_update'] = Auth::guard('admin')->user()->id;
                    $check_history = History::where('student_id', $item)->where('class_id_new', $classs->id)->where('type', Consts::HISTORY_TYPE['change_class'])->first();
                    if ($check_history == null) {
                        History::create($params_history);
                    } else $check_history->update($params_history);
                }
            } 
        }
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(tbClass $classs)
    {
        $classs->delete();
        $les = Schedule::where('class_id', $classs->id)->delete();
        $les = UserClass::where('class_id', $classs->id)->delete();
        $les = Attendance::where('class_id', $classs->id)->delete();
        $les = Score::where('class_id', $classs->id)->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function getAttendance(Request $request)
    {

        try {
            $params = $request->all();
            $attendances = Attendance::getsqlAttendance($params)->get();
            $schedule = Schedule::find($params['schedule_id']);
            $class = tbClass::find($schedule->class_id);
            if ($attendances->isEmpty()) {
                foreach ($class->students as $key => $item) {
                    $attendance_params['date'] = $schedule->date;
                    $attendance_params['schedule_id'] = $schedule->id;
                    $attendance_params['user_id'] = $item->id;
                    $attendance_params['class_id'] = $class->id;
                    $attendance_params['status'] = Consts::ATTENDANCE_STATUS['attendant'];

                    $attendances = Attendance::create($attendance_params);
                }
                $rows = Attendance::getsqlAttendance($params)->get();
            } else {
                $user_ids = $attendances->pluck('user_id')->toArray();
                $student_ids = $class->students->pluck('id')->toArray();
                $differentIds = array_values(array_diff($student_ids, $user_ids));
                $idsToDelete = array_values(array_diff($user_ids, $student_ids));
                if (!empty($differentIds)) {
                    foreach ($differentIds as $key => $item) {
                        $attendance_params['date'] = $schedule->date;
                        $attendance_params['schedule_id'] = $schedule->id;
                        $attendance_params['user_id'] = $item;
                        $attendance_params['class_id'] = $class->id;
                        $attendance_params['status'] = Consts::ATTENDANCE_STATUS['attendant'];

                        $attendances = Attendance::create($attendance_params);
                    }
                } elseif (!empty($idsToDelete)) {
                    foreach ($idsToDelete as $id) {
                        $attendance = Attendance::where('user_id', $id)->delete();
                    }
                }
                $rows = Attendance::getsqlAttendance($params)->get();
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

    public function scheduleTrialClass(Request $request)
    {
        $params = $request->only('class_id');
        if (isset($params['class_id'])) {
            $this->responseData['list_lesson'] = Schedule::where('class_id', $params['class_id'])->orderBy('date', 'asc')->get();
            $this->responseData['this_class'] = tbClass::find($params['class_id']);
        }
        $params_class['type'] = 'lopphu';
        $list_class = tbClass::getSqlClass($params_class)->get();
        $this->responseData['list_class'] = $list_class;
        $this->responseData['module_name'] = 'Lịch học lớp học thử';
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.schedule');
    }
    public function attendancesTrialClass(Request $request)
    {
        $params = $request->only('schedule_id');
        if (isset($params['schedule_id'])) {
            $schedule = Schedule::find($params['schedule_id']);
            if ($schedule) {
                $params['list_user'] = DataPermissionService::getPermissionStudents(Auth::guard('admin')->user()->id);;
                $rows = Attendance::getsqlAttendance($params)->get();

                $params_class['type'] = 'lopphu';
                $list_class = tbClass::getSqlClass($params_class)->get();
                $this->responseData['list_class'] =  $list_class;

                $params_student['state'] = 'try learning';
                $students = Student::getsqlStudent($params_student)->get();
                $this->responseData['students'] =  $students;

                $class = tbClass::find($schedule->class_id);
                $this->responseData['this_class'] =  $class;
                $this->responseData['schedule'] =  $schedule;
                $this->responseData['rows'] =  $rows;
                $this->responseData['params'] = $params;
                $this->responseData['route_name'] = Consts::ROUTE_NAME;
                $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
                $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
                $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
                $this->responseData['module_name'] = 'Danh sách điểm danh theo buổi học';
                return $this->responseView($this->viewPart . '.attendances');
            }
        }
        return redirect()->back()->with('errorMessage', __('Lịch học không tồn tại'));
    }
}
