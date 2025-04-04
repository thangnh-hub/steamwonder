<?php

namespace App\Http\Controllers\Admin;

use App\Consts; 
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Room;
use App\Models\Attendance; 
use App\Models\UserClass;
use App\Models\tbClass; 
use App\Models\Area;
use App\Models\Holiday; 
use App\Models\Period;
use App\Models\Course;
use App\Models\History;
use App\Models\StaffAdmission;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService; 
use Exception;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request; 

class ClassController extends Controller
{
  public function __construct()
  {
    parent::__construct();
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
    $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
    // Get list post with filter params
    $rows = tbClass::orderBy('id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
     
    $paramCourse['status'] = Consts::STATUS['active'];
    $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();
    $this->responseData['areas'] =  Area::getsqlArea()->get();
    $this->responseData['rooms'] =  Room::getSqlRoom()->get();
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    return $this->responseView($this->viewPart . '.index');
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
    $this->responseData['course'] = Course::getSqlCourse($paramStatus)->get();
    $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
    $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
    $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
    $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
    $this->responseData['status'] = Consts::STATUS;

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
      $params = $request->all();
      $class = tbClass::create($params);
 
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
    $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
    if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
    $params['class_id'] = $classs->id;
    $this->responseData['this_class'] = $classs;
    $this->responseData['rows'] = Student::getsqlStudent($params)->get();
    $this->responseData['staffs'] = StaffAdmission::getSqlStaffAdmission()->get();
    $this->responseData['teacher'] =  Teacher::getSqlTeacher()->get();
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

    $this->responseData['course'] = Course::getSqlCourse($paramStatus)->get();
    $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
    $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
    $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
    $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
    $this->responseData['detail'] = $classs;

    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_class'] = tbClass::where('id', "!=", $classs->id)->get();
    $param_userclass['class_id'] = $classs->id;
    $this->responseData['student'] = UserClass::getSqlUserClass($param_userclass)->groupBy('user_id')->get();
    $this->responseData['status_atten'] = Consts::ATTENDANCE_STATUS;
    return $this->responseView($this->viewPart . '.edit');
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

      /** Xử lý phần liên quan đến danh sách học viên trong lớp */
      $params_student = $request['student'];
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
          }

          // Update trạng thái hiện tại cho học viên ĐANG HỌC sang CHỜ XẾP LỚP
          Student::whereIn('id', $list_id_to_out)->where('status_study', 2)->update(['status_study' => 3]);
        }

        /** Duyệt toàn bộ mảng push lên và update thông tin + lịch sử nếu có */
        foreach ($params_student as $t => $item) {
          $student = Student::find($item);

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
    $les = UserClass::where('class_id', $classs->id)->delete();
    $les = Attendance::where('class_id', $classs->id)->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
