<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\BookDistributionService;
use Carbon\Carbon;
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
use App\Http\Services\HistoryService;
use App\Models\HistoryBookDistribution;
use Exception;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use stdClass;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'classs';
        $this->viewPart = 'admin.pages.classs';
        $this->responseData['module_name'] = 'Class Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $params = $request->all();
        $params['type'] = 'lopchinh';
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        // Get list post with filter params
        $rows = tbClass::getSqlClass($params)->where('tb_schedules.type', '=', 'gv')->orderBy('id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->completeClassFunction();
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $this->responseData['type_normal_special'] = Consts::type_normal_special;

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
        DB::beginTransaction();
        try {

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
            DB::commit();
            return redirect()->route($this->routeDefault . '.edit', $class->id)->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(tbClass $classs)
    {
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
    public function edit(tbClass $classs)
    {

        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        // if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
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

        $this->responseData['type_normal_special'] = Consts::type_normal_special;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_lesson'] = Schedule::where('class_id', $classs->id)->where('type', 'gv')->orderBy('date', 'asc')->get();
        $this->responseData['list_class'] = tbClass::where('id', "!=", $classs->id)->get();
        $param_userclass['class_id'] = $classs->id;
        $this->responseData['student'] = UserClass::getSqlUserClass($param_userclass)->groupBy('user_id')->get();
        $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
        $this->responseData['status_atten'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
        $this->responseData['status_class'] = Consts::CLASS_STATUS;
        $this->responseData['transfer_status'] = Consts::TRANSFER_STATUS;
        return $this->responseView($this->viewPart . '.edit');
    }

    public function editByTeacher(Request $request)
    {
        $classs = tbClass::find($request->id);
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
        $this->responseData['list_lesson'] = Schedule::where('class_id', $classs->id)->where('type', 'gv')->orderBy('date', 'asc')->get();
        $this->responseData['list_class'] = tbClass::where('id', "!=", $classs->id)->get();
        $this->responseData['student'] = UserClass::where('class_id', $classs->id)->groupBy('user_id')->get();
        $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
        $this->responseData['status_atten'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
        $this->responseData['status_class'] = Consts::CLASS_STATUS;
        $this->responseData['transfer_status'] = Consts::TRANSFER_STATUS;
        return $this->responseView($this->viewPart . '.editByTeacher');
    }

    public function updateByteacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $classs = tbClass::find($request->class_id);
            if ($classs) {
                $params_lesson = $request['lesson'];
                if ($params_lesson) {
                    foreach ($params_lesson as $item) {
                        $data_lesson = [];
                        if ((int) $item['id'] > 0) {
                            $lesson = Schedule::find($item['id']);
                            $json = [
                                "note" =>  $item['note'],
                            ];
                            $data_lesson['json_params'] = $json;
                            if ($lesson->status != 'dadiemdanh') {
                                $data_lesson['date'] = $item['date'];
                                $data_lesson['period_id'] = $item['period_id'];
                                $data_lesson['room_id'] = $item['room_id'];
                                $data_lesson['teacher_id'] =  $item['teacher_id'];
                                $data_lesson['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                                $data_lesson['transfer_status'] = $item['transfer_status'] ?? $lesson->transfer_status;
                            }

                            $lesson->fill($data_lesson);
                            $lesson->save();
                        } else {
                            $json = [
                                "note" =>  $item['note'],
                            ];
                            $data_lesson['json_params'] = json_encode($json);
                            $data_lesson['class_id'] = $classs->id;
                            $data_lesson['area_id'] = $classs->area_id;
                            $data_lesson['date'] = $item['date'];
                            $data_lesson['period_id'] = $item['period_id'];
                            $data_lesson['room_id'] = $item['room_id'];
                            $data_lesson['teacher_id'] = $item['teacher_id'];
                            $data_lesson['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                            $data_lesson['is_add_more'] = 1;
                            $data_lesson['transfer_status'] = $item['transfer_status'] ?? '';
                            $lesson = Schedule::insert($data_lesson);
                        }
                    }
                }

                DB::commit();
                return redirect()->back()->with('successMessage', __('Successfully updated!'));
            } else return redirect()->back()->with('errorMessage', __('Không tìm thấy lớp học'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tbClass $classs)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $params = $request->only(['name', 'day_exam', 'area_id', 'room_id', 'json_params', 'status', 'end_date', 'lesson_number', 'syllabus_id', 'type_normal_special', 'assistant_teacher']);
            $arr_insert = $params;
            $classs->fill($arr_insert);
            $classs->save();
            /** Xử lý phần liên quan đến lịch học của lớp học */
            $params_lesson = $request['lesson'];
            if ($params_lesson) {
                foreach ($params_lesson as $item) {
                    $data_lesson = [];
                    if ((int) $item['id'] > 0) {
                        $lesson = Schedule::find($item['id']);
                        $json = [
                            "note" =>  $item['note'],
                        ];
                        $data_lesson['json_params'] = $json;
                        if ($lesson->status != 'dadiemdanh') {
                            $data_lesson['date'] = $item['date'];
                            $data_lesson['period_id'] = $item['period_id'];
                            $data_lesson['room_id'] = $item['room_id'];
                            $data_lesson['teacher_id'] =  $item['teacher_id'];
                            $data_lesson['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                            $data_lesson['transfer_status'] = $item['transfer_status'] ?? $lesson->transfer_status;
                        }

                        $lesson->fill($data_lesson);
                        $lesson->save();
                    } else {
                        $json = [
                            "note" =>  $item['note'],
                        ];
                        $data_lesson['json_params'] = json_encode($json);
                        $data_lesson['class_id'] = $classs->id;
                        $data_lesson['area_id'] = $classs->area_id;
                        $data_lesson['date'] = $item['date'];
                        $data_lesson['period_id'] = $item['period_id'];
                        $data_lesson['room_id'] = $item['room_id'];
                        $data_lesson['teacher_id'] = $item['teacher_id'];
                        $data_lesson['assistant_teacher'] = $item['assistant_teacher'] ?? '';
                        $data_lesson['is_add_more'] = 1;
                        $data_lesson['transfer_status'] = $item['transfer_status'];
                        $lesson = Schedule::insert($data_lesson);
                    }
                }
            }
            /** Xử lý phần liên quan đến danh sách học viên trong lớp */
            $params_student = $request['student'];
            $service = new BookDistributionService();
            if ($params_student) {
                /** Xử lý lấy các mảng id_hocvien */
                $list_id_student_old = UserClass::where('class_id', $classs->id)->get()->pluck('user_id')->toArray(); // Danh sách học viên cũ trong lớp
                $list_id_to_out = array_values(array_diff($list_id_student_old, $params_student)); // Học viên bị cho ra
                $list_id_to_add = array_values(array_diff($params_student, $list_id_student_old)); // Học viên được thêm mới vào
                /** Thực hiện xóa học viên bị cho ra khỏi lớp */
                UserClass::where('class_id', $classs->id)->whereIn('user_id', $list_id_to_out)->delete();
                /**
                 * Xử lý update lịch sử cho các học viên bị cho ra khỏi lớp
                 * - Update trạng thái học viên sang Chờ xếp lớp
                 * - Thêm lịch sử của học viên trạng thái chờ xếp lớp
                 * - Thêm lịch sử của học viên là ra khỏi lớp
                 */
                // Lấy danh sách học viên bị cho ra khỏi lớp và đang ở trạng thái ĐANG HỌC
                $list_student_out = Student::whereIn('id', $list_id_to_out)->where('status_study', 2)->get();
                if ($list_student_out) {
                    foreach ($list_student_out as $key => $student) {
                        // Lịch sử đổi trạng thái
                        $item_add_his_status = [];
                        $item_add_his_status['type'] = Consts::HISTORY_TYPE['change_status_student'];
                        $item_add_his_status['student_id'] = $student->id;
                        $item_add_his_status['status_study_old'] = $student->status_study;
                        $item_add_his_status['status_study_new'] = 3;
                        $item_add_his_status['class_id_old'] = $classs->id;
                        $item_add_his_status['admin_id_update'] = Auth::guard('admin')->user()->id;
                        History::create($item_add_his_status);
                        // Lịch sử ra khỏi lớp
                        $item_add_his_class = [];
                        $item_add_his_class['type'] = Consts::HISTORY_TYPE['out_class'];
                        $item_add_his_class['student_id'] = $student->id;
                        $item_add_his_class['class_id_old'] = $classs->id;
                        $item_add_his_class['admin_id_update'] = Auth::guard('admin')->user()->id;
                        History::create($item_add_his_class);
                        // Xóa phát sách nếu có
                        if (isset($student) && in_array($classs->level_id, [1, 2, 3, 4, 5, 6])) {
                            $params_book['student_id'] = $student->id;
                            $params_book['class_id'] = $classs->id;
                            $params_book['level_id'] = $classs->level_id;
                            $service->deleteHistoryBookDistribution($params_book);
                        }
                    }

                    // Update trạng thái hiện tại cho học viên ĐANG HỌC sang CHỜ XẾP LỚP
                    Student::whereIn('id', $list_id_to_out)->where('status_study', 2)->update(['status_study' => 3]);
                }

                /** Duyệt toàn bộ mảng push lên và update thông tin + lịch sử nếu có */
                foreach ($params_student as $t => $item) {
                    $student = Student::find($item);
                    /**
                     * Nếu là lớp học trình độ từ A1.1 đến B1.2 (level_id = 1~6)
                     * Thực hiện tạo lịch sử phát sách cho học viên nếu học viên chưa đc nhận sách
                     * Học viên đc add vào lớp ở hình thức là hocmoi
                     */
                    if (isset($student) && in_array($classs->level_id, [1, 2, 3, 4, 5, 6]) && $classs->status == 'dang_hoc' && $request['user_class_status'][$t] == 'hocmoi') {
                        $params_book['student_id'] = $item;
                        $params_book['class_id'] = $classs->id;
                        $params_book['level_id'] = $classs->level_id;
                        $service->addHistoryBookDistribution($params_book);
                    }
                    /**
                     * Kiểm tra nếu id học viên thuộc mảng học viên đc thêm mới
                     * => tạo mới vào lớp và tạo mới lịch sử (không cần check lịch sử đã có chưa)
                     */
                    if (in_array($item,  $list_id_to_add)) {
                        $item_to_add_user_class = [];
                        $item_to_add_user_class['class_id'] = $classs->id;
                        $item_to_add_user_class['user_id'] = $item;
                        $item_to_add_user_class['status'] = $request['user_class_status'][$t];
                        $item_to_add_user_class['json_params']['day_in_class'] = $request['day_in_class'][$t];
                        UserClass::create($item_to_add_user_class);

                        if (isset($student)) {
                            $student->state = Consts::STUDENT_STATUS['main learning'];
                            if ($student->day_official == "") {
                                $student->day_official = date('Y-m-d', time());
                            }
                            /** Nếu lớp đang học mà trạng thái HV khác đang học thì cập nhật trạng thái HV là đang học va thêm lịch sử đổi trạng thái */
                            if ($classs->status == 'dang_hoc' && $student->status_study != 2) {
                                // Thêm lịch sử thay đổi trạng thái là đang học
                                $item_add_his_status = [];
                                $item_add_his_status['type'] = Consts::HISTORY_TYPE['change_status_student'];
                                $item_add_his_status['student_id'] = $student->id;
                                $item_add_his_status['status_study_old'] = $student->status_study;
                                $item_add_his_status['status_study_new'] = 2;
                                $item_add_his_status['admin_id_update'] = Auth::guard('admin')->user()->id;
                                History::create($item_add_his_status);
                                // Cập nhật lại trạng thái đang học
                                $student->status_study = 2;
                            }
                            $student->save();
                        }
                        // Lịch sử thêm vào lớp
                        $item_add_his_class = [];
                        $item_add_his_class['student_id'] = $item;
                        $item_add_his_class['class_id_new'] = $classs->id;
                        $item_add_his_class['levels_id_new'] = $classs->level_id;
                        $item_add_his_class['syllabuss_id_new'] = $classs->syllabus_id;
                        $item_add_his_class['courses_id_new'] = $classs->course_id;
                        $item_add_his_class['status_change_class'] = $request['user_class_status'][$t];
                        $item_add_his_class['type'] = Consts::HISTORY_TYPE['change_class'];
                        $item_add_his_class['admin_id_update'] = Auth::guard('admin')->user()->id;
                        $item_add_his_class['json_params']['day_in_class'] = $request['day_in_class'][$t];
                        History::create($item_add_his_class);
                    }

                    /**
                     * Kiểm tra nếu id học viên là đã tồn tại trong lớp rồi (vừa không thuộc mảng thêm mới vừa không thuộc mảng cho ra khỏi lớp)
                     * thì update lại thông tin bản ghi ở danh sách học viên và lịch sử học viên (không tạo mới)
                     */
                    elseif (!in_array($item,  $list_id_to_out)) {

                        UserClass::where('class_id', $classs->id)->where('user_id', $item)->update(['status' => $request['user_class_status'][$t], 'json_params->day_in_class' => $request['day_in_class'][$t]]);
                        $item_add_his_class = [];
                        $item_add_his_class['student_id'] = $item;
                        $item_add_his_class['class_id_new'] = $classs->id;
                        $item_add_his_class['levels_id_new'] = $classs->level_id;
                        $item_add_his_class['syllabuss_id_new'] = $classs->syllabus_id;
                        $item_add_his_class['courses_id_new'] = $classs->course_id;
                        $item_add_his_class['status_change_class'] = $request['user_class_status'][$t];
                        $item_add_his_class['type'] = Consts::HISTORY_TYPE['change_class'];
                        $item_add_his_class['admin_id_update'] = Auth::guard('admin')->user()->id;
                        $item_add_his_class['json_params']['day_in_class'] = $request['day_in_class'][$t];
                        $check_history = History::where('student_id', $item)->where('class_id_new', $classs->id)->where('type', Consts::HISTORY_TYPE['change_class'])->first();
                        if ($check_history) {
                            $check_history->update($item_add_his_class);
                        } else {
                            History::create($item_add_his_class);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
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

    public function destroyLessonAjax(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();

            // Xóa các bản ghi attendance dựa trên schedule_id
            $attendance = Attendance::where('schedule_id', $params['schedule_id'])->delete();

            // Tìm và xóa bản ghi schedule dựa trên schedule_id
            $schedule = Schedule::find($params['schedule_id']);
            if ($schedule) {
                $rows_schedule = $schedule->delete();
            } else {
                $rows_schedule = false;
            }

            if ($rows_schedule) {
                DB::commit();
                return $this->sendResponse('', 'success');
            } else {
                DB::rollBack();
                return $this->sendResponse('', __('No records available!'));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    public function nameclassUnique(Request $request)
    {
        try {
            $name = $request->get('ten');
            $id = $request->get('id'); // Lấy ID của bản ghi hiện tại (nếu có)

            $query = tbClass::where('name', $name);

            if ($id) {
                $query->where('id', '!=', $id); // Loại bỏ bản ghi hiện tại khi chỉnh sửa
            }

            $exists = $query->exists();
            return $this->sendResponse($exists, 'success');
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Auto update status class to hoan_thanh
     * day_end >= 5 days
     */
    public function completeClassFunction()
    {
        DB::beginTransaction();
        try {
            // Get list post with filter params
            $rows = tbClass::getSqlClass()->get();
            $class_to_complete = $rows->filter(function ($item) {
                if (isset($item->day_end) && $item->day_end != "") {
                    $endDate = Carbon::parse($item->day_end);
                    $daysDiff = $endDate->diffInDays(Carbon::now());
                    return $item->day_end < Carbon::now() && $daysDiff >= 5 && $item->status == 'dang_hoc';
                }
                return false;
            });
            foreach ($class_to_complete as $row) {
                $row->status = 'hoan_thanh';
                $row->save();
            }
            // Filter để lấy ra các lớp đã hoàn thành, ở trình độ Ôn thi chuyên sâu và có day_end >= 15
            $list_id_class_completed = $rows->filter(function ($item) {
                if (isset($item->day_end) && $item->day_end != "") {
                    $array_level_to_complete = [14, 15, 16, 20, 25]; // Mảng các lớp sau OTCS: Ôn thi Chuyên sâu, Sau OTCS, OT Nói, OT Viết, Telc OT
                    $endDate = Carbon::parse($item->day_end);
                    $daysDiff = $endDate->diffInDays(Carbon::now());
                    return $item->day_end < Carbon::now() && $daysDiff >= 15 && $item->status == 'hoan_thanh' && in_array($item->level_id, $array_level_to_complete);
                }
                return false;
            })->pluck('id');
            // Lấy ra các học viên thuộc các lớp $list_id_class_completed và ở trạng thái ĐANG HỌC (=2)
            $list_id_student_in_class_completed = UserClass::leftJoin('admins', 'admins.id', '=', 'tb_user_class.user_id')->where('admins.status_study', '=', 2)->whereIn('class_id', $list_id_class_completed)->get()->pluck('user_id');
            // Gọi hàm để update tình trạng học hoàn thành cho các học viên đó
            $this->updateCompleteStatusStudent($list_id_student_in_class_completed);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
        }
    }

    /**
     * Auto update status_study student to hoanthanh (id_status_study = 6) -> fix id status
     * Tất cả các lớp học của học viên này đều phải hoàn thành hoặc hủy (không có lớp đang học)
     */
    public function updateCompleteStatusStudent($list_id_student = [])
    {
        foreach ($list_id_student as $key => $id_student) {
            // Lấy ra các lớp của học viên theo bảng tb_user_class (lớp đang học)
            $check_class_dang_hoc = tbClass::where('tb_classs.status', 'dang_hoc')->join('tb_user_class', 'tb_classs.id', '=', 'tb_user_class.class_id')->where('tb_user_class.user_id', '=', $id_student)->first();
            // Nếu không có lớp đang học thì thực hiện update tình trạng học cho học viên đó
            if ($check_class_dang_hoc == null) {
                $student = Student::find($id_student);
                // Lưu lại lịch sử cập nhật tình trạng học và bảng history
                HistoryService::addHistoryStatusStudy($student->id, $student->status_study, 6, 'auto');
                // Cập nhật trạng thái học viên
                $student->status_study = 6;
                $student->save();
            }
        }
    }

    /**
     * View dành cho GVNN
     */
    public function index_gvnn(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['type'] = 'lopchinh';
        // $params['date'] = $params['date'] ?? Carbon::now()->format('Y-m-d');

        $params['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        // Get list post with filter params
        $rows = tbClass::getSqlClassgvnn($params)->orderBy('tb_classs.status')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        // $this->completeClassFunction();
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
        $this->responseData['list_teacher'] =  Teacher::getSqlTeacher()->get();
        $this->responseData['module_name'] = 'Quản lý lớp GVNN';
        return $this->responseView($this->viewPart . '.index_gvnn');
    }

    /**
     * Xóa lịch học của GVNN (chỉ đào tạo mới thao tác)
     */
    public function destroyLessonAjaxGVNN(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();

            // Xóa các bản ghi attendance dựa trên schedule_id
            $attendance = Attendance::where('schedule_id', $params['schedule_id'])->delete();

            // Tìm và xóa bản ghi schedule dựa trên schedule_id
            $schedule = Schedule::find($params['schedule_id']);
            if ($schedule) {
                $rows_schedule = $schedule->delete();
            } else {
                $rows_schedule = false;
            }

            if ($rows_schedule) {
                DB::commit();
                return $this->sendResponse('', 'success');
            } else {
                DB::rollBack();
                return $this->sendResponse('', __('No records available!'));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
}
