<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Attendance;
use App\Models\Period;
use App\Models\tbClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Area;
use App\Models\Admin;
use App\Models\Level;
use Exception;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\UserClass;
use App\Http\Services\DataPermissionService;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Http\Services\NotifyService;
use App\Models\Course;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'attendances';
        $this->viewPart = 'admin.pages.attendances';
        $this->responseData['module_name'] = 'Attendances Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        DB::beginTransaction();
        try {
            $auth_admin = Auth::guard('admin')->user();
            $this->responseData['auth_admin'] =  $auth_admin;
            $params = $request->all();
            $attendances = Attendance::getsqlAttendance($params)->get();
            $schedule = Schedule::find($params['schedule_id']);
            $class = tbClass::find($schedule->class_id);
            // dd($class->students);
            $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
            if (!in_array($class->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp vừa nhập'));
            if ($attendances->isEmpty()) {
                foreach ($class->students as $key => $item) {
                    $attendance_params['date'] = $schedule->date;
                    $attendance_params['schedule_id'] = $schedule->id;
                    $attendance_params['user_id'] = $item->id;
                    $attendance_params['class_id'] = $class->id;
                    $attendance_params['status'] = Consts::ATTENDANCE_STATUS['attendant'];
                    if ($item->getOriginal('pivot_json_params')) {
                        $day_in_class = json_decode($item->getOriginal('pivot_json_params'))->day_in_class ?? null;
                        if ($day_in_class != null) {
                            $date_attendance = Carbon::parse($schedule->date);
                            $date_in_class = Carbon::parse($day_in_class);

                            // Check nếu ngày điểm danh nhỏ hơn ngày vào lớp thì bỏ qua
                            if ($date_attendance->lt($date_in_class)) {
                                continue; // Bỏ qua k tạo mới vì học viên sẽ vào lớp sau ngày điểm danh
                            }
                        }
                    }
                    $attendances = Attendance::create($attendance_params);
                }
            } else {
                if ($schedule->status != Consts::SCHEDULE_STATUS['dadiemdanh']) {
                    $user_ids = $attendances->pluck('user_id')->toArray();
                    $student_ids = $class->students->pluck('id')->toArray();
                    $differentIds = array_values(array_diff($student_ids, $user_ids));
                    $idsToDelete = array_values(array_diff($user_ids, $student_ids));

                    if (!empty($differentIds)) {
                        foreach ($class->students as $key => $item) {
                            if (in_array($item->id, $differentIds)) {
                                $attendance_params['date'] = $schedule->date;
                                $attendance_params['schedule_id'] = $schedule->id;
                                $attendance_params['user_id'] = $item->id;
                                $attendance_params['class_id'] = $class->id;
                                $attendance_params['status'] = Consts::ATTENDANCE_STATUS['attendant'];
                                if ($item->getOriginal('pivot_json_params')) {
                                    $day_in_class = json_decode($item->getOriginal('pivot_json_params'))->day_in_class ?? null;
                                    if ($day_in_class != null) {
                                        $date_attendance = Carbon::parse($schedule->date);
                                        $date_in_class = Carbon::parse($day_in_class);

                                        if ($date_attendance->lt($date_in_class)) {
                                            continue; // Bỏ qua k tạo mới vì học viên sẽ vào lớp sau ngày điểm danh
                                        }
                                    }
                                }
                                $attendances = Attendance::create($attendance_params);
                            }
                        }
                    }
                    if (!empty($idsToDelete)) {
                        // Xóa đi các bản ghi của học viên bị cho ra khỏi lớp và chưa có tgian điểm danh
                        Attendance::whereIn('user_id', $idsToDelete)->where('schedule_id', $schedule->id)->where('attendance_time', NULL)->delete();
                    }
                }
            }
            $rows = Attendance::getsqlAttendance($params)->get();

            $paramsSchedule['class_id'] = $schedule->class_id;
            $schedules = Schedule::getsqlSchedule($paramsSchedule)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['schedules'] =  $schedules;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['this_class'] =  $class;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['route_name'] = Consts::ROUTE_NAME;
            $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
            $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
            $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
            $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
            $this->responseData['params'] = $params;
            $this->responseData['transfer_status'] = Consts::TRANSFER_STATUS;
            $this->responseData['mess'] = 'Chỉ thực hiện điểm danh cho các buổi học trong ngày hiện tại!';
            DB::commit();
            return $this->responseView($this->viewPart . '.index');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    public function show_attendance(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            $attendances = Attendance::getsqlAttendance($params)->get();
            $schedule = Schedule::find($params['schedule_id']);
            $class = tbClass::find($schedule->class_id);

            // Khóa lại phần thay đổi danh sách học viên điểm danh khi sử dụng chức năng sửa điểm danh
            // $user_ids = $attendances->pluck('user_id')->toArray();
            // $student_ids = $class->students->pluck('id')->toArray();
            // $differentIds = array_values(array_diff($student_ids, $user_ids));
            // $idsToDelete = array_values(array_diff($user_ids, $student_ids));
            // if (!empty($differentIds)) {
            //     foreach ($differentIds as $key => $item) {
            //         $attendance_params['date'] = $schedule->date;
            //         $attendance_params['schedule_id'] = $schedule->id;
            //         $attendance_params['user_id'] = $item;
            //         $attendance_params['class_id'] = $class->id;
            //         $attendance_params['status'] = Consts::ATTENDANCE_STATUS['attendant'];

            //         $attendances = Attendance::create($attendance_params);
            //     }
            // }
            $rows = Attendance::getsqlAttendance($params)->get();

            $paramsSchedule['class_id'] = $schedule->class_id;
            $schedules = Schedule::getsqlSchedule($paramsSchedule)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['schedules'] =  $schedules;
            $this->responseData['this_class'] =  $class;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['route_name'] = Consts::ROUTE_NAME;
            $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
            $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
            $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
            $this->responseData['params'] = $params;
            $this->responseData['transfer_status'] = Consts::TRANSFER_STATUS;
            $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
            DB::commit();
            return $this->responseView($this->viewPart . '.edit');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }
    public function AttendanceClass(Request $request)
    {

        $params = $request->all();
        // $params['teacher_id'] = Auth::guard('admin')->user()->id;

        // Get list post with filter params
        $rows = tbClass::getsqlClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['areas'] = Area::where('status', Consts::STATUS['active'])->get();
        $this->responseData['levels'] = Level::get();

        $paramClass['type'] = 'lopchinh';
        $this->responseData['class'] = tbClass::getSqlClass($paramClass)->get();

        return $this->responseView($this->viewPart . '.attendance_class');
    }

    public function ScheduleClass(Request $request)
    {
        $params = $request->all();
        if (isset($params['class_id'])) {
            $params['class_id'] = $params['class_id'];
        } else {
            $params['class_id'] = 0;
        }

        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        if (!in_array($params['class_id'], $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));

        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $param_this_class['id'] = $params['class_id'];
        $this->responseData['this_class'] = tbClass::getsqlClass($param_this_class)->first();

        // Get list post with filter params
        $rows = Schedule::getsqlSchedule($params)->where('type', 'gv')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $class = tbClass::getsqlClass($params)->get();
        $student = Student::getsqlStudent($params)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['class'] =  $class;
        $this->responseData['student'] =  $student;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['postStatus'] = Consts::STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['transfer_status'] = Consts::TRANSFER_STATUS;
        return $this->responseView($this->viewPart . '.schedule_class');
    }

    public function SaveAttendance(Request $request)
    {
        DB::beginTransaction();
        try {
            $attendance = new Attendance();
            $schedule = new Schedule();
            $params = $request->all();
            $schedule_id = $params['schedule'] ?? 0;
            $schedule =  $schedule->find($schedule_id);
            if ($schedule) {
                // check ngày điểm danh
                $check = $this->check_day_schedule($schedule);
                if ($check->check == false) {
                    return redirect()->back()->with('errorMessage', $check->mess);
                }

                foreach ($params['list'] as $key => $item) {
                    $attendance = $attendance->find($item['id']);
                    $updateResult =  $attendance->update([
                        'is_homework' => $item['is_homework'],
                        'date' => $schedule->date,
                        'status' => $item['status'],
                        'json_params' => $item['json_params'],
                        // 'note' => $item['note'],
                        'note_teacher' => $item['note_teacher'],
                        'attendance_time' => Carbon::now(),

                    ]);
                    // check hiển thị thông báo đi muộn trong tháng
                    $month = Carbon::parse($attendance->date)->month;
                    $params_attendance['month'] = (int)$month;
                    $params_attendance['status'] = Consts::ATTENDANCE_STATUS['late'];
                    $params_attendance['user_id'] = (int)$attendance->user_id;
                    $params_attendance['class_id'] = (int)$attendance->class_id;
                    $check_attendance = Attendance::getSqlAttendance($params_attendance)->get();
                    if (count($check_attendance) >= 2) {
                        $user = Admin::find($attendance->user_id);
                        $notify_title = '[Đi muộn] Học viên ' . $user->name . '[' . $user->admin_code . '] đã đi muộn ' . count($check_attendance) . ' buổi trong tháng ' . Carbon::parse($attendance->date)->month;
                        $link = route('attendances.index', ['schedule_id' => $attendance->schedule_id]);
                        NotifyService::add_notify($notify_title, Consts::TYPE_NOTIFY['late'], $link, $user->id, '');
                    }
                }
                if ($attendance) {
                    $schedule->update([
                        'status' => Consts::SCHEDULE_STATUS['dadiemdanh'],
                        'attendance_time' => Carbon::now(),
                        'type_schedule' => $request->type_schedule,
                        'transfer_status' => $request->transfer_status,
                        'json_params->note' => $request->schedule_note,
                    ]);
                    if (Auth::guard('admin')->user()->admin_type == "teacher") {
                        $schedule->update([
                            'teacher_id' => Auth::guard('admin')->user()->id,
                        ]);
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

    public function update(Request $request)
    {
        $attendance = new Attendance();
        $schedule = new Schedule();
        $params = $request->all();

        foreach ($params['list'] as $key => $item) {
            $attendance = $attendance->find($item['id']);
            $updateResult =  $attendance->update([
                'is_homework' => $item['is_homework'],
                'status' => $item['status'],
                'json_params' => $item['json_params'],
                // 'note' => $item['note'],
                // 'attendance_time' => Carbon::now(),
            ]);
        }
        if ($attendance) {
            $schedule = $schedule->find($attendance->schedule_id);
            $schedule->update([
                'status' => Consts::SCHEDULE_STATUS['dadiemdanh'],
                'attendance_time' => Carbon::now(),
                'type_schedule' => $request->type_schedule,
                'transfer_status' => $request->transfer_status,
                'json_params->note' => $request->schedule_note,
                // 'attendance_time' => Carbon::now(),
            ]);
        }
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function classByArea(Request $request)
    {
        try {
            $id = $request->id;
            $rows = tbClass::where('area_id', $id)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function ajaxUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $attendance = Attendance::find($request->id);
            if (isset($attendance)) {
                $json = [
                    "is_contact_to_parents" =>  $request->is_contact_to_parents,
                    "parents_method" =>  $request->parents_method,
                    "value" =>  isset($attendance->json_params->value) ? $attendance->json_params->value : "",
                ];
                $params['json_params'] = $json;
                $params['note'] = $request->note;
                $attendance->fill($params);
                $attendance->save();

                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }

    public function ajaxUpdateNoteTeacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $attendance = Attendance::find($request->id);

            if (isset($attendance)) {
                $params['note_teacher'] = $request->note;
                $attendance->fill($params);
                $attendance->save();
                DB::commit();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }
    public function exportAttendance(Request $request)
    {
        $params = $request->all();
        return Excel::download(new AttendanceExport($params), 'DIEM DANH.xlsx');
    }
    public function check_day_schedule($schedule)
    {
        $result = new \stdClass();
        $result->check = true;
        $result->mess = '';
        // Ngày điểm danh khác ngày hiện tại
        if ($schedule->date > date('Y-m-d', time())) {
            $result->check = false;
            $result->mess = 'Bạn không thể thực hiện điểm danh cho buổi học trước lịch học!';
        }
        // Không được điểm danh lại
        if ($schedule->status == consts::SCHEDULE_STATUS['dadiemdanh']) {
            $result->check = false;
            $result->mess = 'Buổi học này đã được điểm danh!';
        }

        // điều kiện thêm...
        return $result;
    }

    public function scheduleClassGVNN(Request $request)
    {
        $params = $request->all();
        if (isset($params['class_id'])) {
            $params['class_id'] = $params['class_id'];
        } else {
            $params['class_id'] = 0;
        }

        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        if (!in_array($params['class_id'], $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));

        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $param_this_class['id'] = $params['class_id'];
        $this->responseData['this_class'] = tbClass::getSqlClassgvnn($param_this_class)->first();

        // Get list post with filter params
        $rows = Schedule::getsqlSchedule($params)->where('type', 'gvnn')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $class = tbClass::getsqlClass($params)->get();
        $student = Student::getsqlStudent($params)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['class'] =  $class;
        $this->responseData['student'] =  $student;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['postStatus'] = Consts::STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = 'Quản lý điểm danh theo buổi học GVNN';
        return $this->responseView($this->viewPart . '.schedule_class_gvnn');
    }

    public function indexGVNN(Request $request)
    {
        try {
            $auth_admin = Auth::guard('admin')->user();
            $this->responseData['auth_admin'] =  $auth_admin;
            $params = $request->all();
            $attendances = Attendance::getsqlAttendance($params)->get();
            $schedule = Schedule::find($params['schedule_id']);
            $class = tbClass::find($schedule->class_id);
            $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
            if (!in_array($class->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp vừa nhập'));
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
                if ($schedule->status != Consts::SCHEDULE_STATUS['dadiemdanh']) {
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
                    }
                }
                $rows = Attendance::getsqlAttendance($params)->get();
            }

            $paramsSchedule['class_id'] = $schedule->class_id;
            $schedules = Schedule::getsqlSchedule($paramsSchedule)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['schedules'] =  $schedules;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['this_class'] =  $class;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['route_name'] = Consts::ROUTE_NAME;
            $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
            $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
            $this->responseData['teacher_type'] = Consts::TEACHER_TYPE;
            $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
            $this->responseData['params'] = $params;
            $this->responseData['mess'] = 'Chỉ thực hiện điểm danh cho các buổi học trong ngày hiện tại!';
            $this->responseData['module_name'] = 'Quản lý điểm danh theo buổi học GVNN';
            DB::commit();
            return $this->responseView($this->viewPart . '.index_gvnn');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    public function showAttendanceGVNN(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            $attendances = Attendance::getsqlAttendance($params)->get();
            $schedule = Schedule::find($params['schedule_id']);
            $class = tbClass::find($schedule->class_id);

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
            }
            $rows = Attendance::getsqlAttendance($params)->get();
            $paramsSchedule['class_id'] = $schedule->class_id;
            $schedules = Schedule::getsqlSchedule($paramsSchedule)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['schedules'] =  $schedules;
            $this->responseData['this_class'] =  $class;
            $this->responseData['schedule'] =  $schedule;
            $this->responseData['route_name'] = Consts::ROUTE_NAME;
            $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
            $this->responseData['is_homework'] = Consts::IS_HOMEWORK;
            $this->responseData['option_absent'] = Consts::OPTION_ABSENT;
            $this->responseData['params'] = $params;
            $this->responseData['module_name'] = 'Quản lý điểm danh theo buổi học GVNN';
            DB::commit();
            return $this->responseView($this->viewPart . '.edit_gvnn');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }
    public function SaveAttendanceGVNN(Request $request)
    {
        DB::beginTransaction();
        try {
            $attendance = new Attendance();
            $schedule = new Schedule();
            $params = $request->all();
            $schedule_id = $params['schedule'] ?? 0;
            $schedule =  $schedule->find($schedule_id);
            if ($schedule) {
                // check ngày điểm danh
                $check = $this->check_day_schedule($schedule);
                if ($check->check == false) {
                    return redirect()->back()->with('errorMessage', $check->mess);
                }

                foreach ($params['list'] as $key => $item) {
                    $attendance = $attendance->find($item['id']);
                    $updateResult =  $attendance->update([
                        'is_homework' => $item['is_homework'],
                        'status' => $item['status'],
                        'json_params' => $item['json_params'],
                        // 'note' => $item['note'],
                        'note_teacher' => $item['note_teacher'],
                        'attendance_time' => Carbon::now(),

                    ]);
                    // check hiển thị thông báo đi muộn trong tháng
                    $month = Carbon::parse($attendance->date)->month;
                    $params_attendance['month'] = (int)$month;
                    $params_attendance['status'] = Consts::ATTENDANCE_STATUS['late'];
                    $params_attendance['user_id'] = (int)$attendance->user_id;
                    $params_attendance['class_id'] = (int)$attendance->class_id;
                    $check_attendance = Attendance::getSqlAttendance($params_attendance)->get();
                    if (count($check_attendance) >= 2) {
                        $user = Admin::find($attendance->user_id);
                        $notify_title = '[Đi muộn] Học viên ' . $user->name . '[' . $user->admin_code . '] đã đi muộn ' . count($check_attendance) . ' buổi trong tháng ' . Carbon::parse($attendance->date)->month;
                        $link = route('attendances.index', ['schedule_id' => $attendance->schedule_id]);
                        NotifyService::add_notify($notify_title, Consts::TYPE_NOTIFY['late'], $link, $user->id, '');
                    }
                }
                if ($attendance) {
                    $schedule->update([
                        'status' => Consts::SCHEDULE_STATUS['dadiemdanh'],
                        'attendance_time' => Carbon::now(),
                        'type_schedule' => $request->type_schedule,
                    ]);
                    if (Auth::guard('admin')->user()->admin_type == "teacher") {
                        $schedule->update([
                            'teacher_id' => Auth::guard('admin')->user()->id,
                        ]);
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
    public function updateGVNN(Request $request)
    {
        $attendance = new Attendance();
        $schedule = new Schedule();
        $params = $request->all();

        foreach ($params['list'] as $key => $item) {
            $attendance = $attendance->find($item['id']);
            $updateResult =  $attendance->update([
                'is_homework' => $item['is_homework'],
                'status' => $item['status'],
                'json_params' => $item['json_params'],
                // 'note' => $item['note'],
                // 'attendance_time' => Carbon::now(),

            ]);
        }
        if ($attendance) {
            $schedule = $schedule->find($attendance->schedule_id);
            $schedule->update([
                'status' => Consts::SCHEDULE_STATUS['dadiemdanh'],
                // 'attendance_time' => Carbon::now(),
            ]);
        }
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function updateSchedule(Request $request)
    {
        $schedule_id = $request->only('schedule')['schedule'];
        $params = $request->except('schedule');
        $schedule = Schedule::find($schedule_id);
        if ($schedule) {
            $schedule->update($params);
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        }
        return redirect()->back()->with('errorMessage', __('Không tìm thấy buổi học!'));
    }
}
