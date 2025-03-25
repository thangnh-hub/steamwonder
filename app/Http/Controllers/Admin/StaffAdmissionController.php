<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Role;
use App\Models\tbClass;
use App\Models\Major;
use App\Models\Field;
use App\Models\EntryLevel;
use App\Models\Student;
use App\Models\UserClass;
use App\Models\Score;
use App\Models\StatusStudent;
use App\Models\Evaluation;
use App\Models\Attendance;
use App\Models\Area;
use App\Models\Course;
use App\Models\Staff;
use App\Models\StaffAdmission;
use App\Models\Teacher;
use App\Models\Admin;
use App\Http\Services\DataPermissionService;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class StaffAdmissionController extends Controller
{
  private $staffAdmission;
  public function __construct(StaffAdmission $staffAdmission)
  {
    $this->staffAdmission = $staffAdmission;
    $this->routeDefault  = 'staffadmissions';
    $this->viewPart = 'admin.pages.staffadmissions';
    $this->responseData['module_name'] = 'Staff Admissions Management';
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index(Request $request)
  {
    return redirect()->route($this->routeDefault . '.dashboard');

    $params = $request->all();
    $parent_id = Auth::guard('admin')->user()->id;
    $params['parent_ids'] = $this->staffAdmission->getAllStaffAdmissionChildrenAndSelf($parent_id);

    // Get list post with filter params
    $rows = StaffAdmission::getsqlStaffAdmission($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;

    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $class = tbClass::getsqlClass()->get();

    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $this->responseData['roles'] = $roles;
    $this->responseData['class'] =  $class;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['gender'] = Consts::GENDER;
    $this->responseData['my_info'] = Auth::guard('admin')->user()->id;
    $admin = Auth::guard('admin')->user();
    $DataPermissionService = new DataPermissionService;
    $arr_id = $DataPermissionService->getPermissionUsersAndSelf($admin->id);
    $this->responseData['direct_manager'] =  StaffAdmission::whereIn('id', $arr_id)->get();
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
    $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
    $params = $request->all();

    if (isset($params['lang'])) {
      $lang = $params['lang'];
      unset($params['lang']);
    }
    $request->validate([
      // 'name' => 'required',
      // 'admin_code' => 'required|unique:admins',
      'email' => "required|email|max:255|unique:admins",
      'password' => "required|min:8|max:255",
    ]);
    if (isset($params['admin_code'])) {
      $params['admin_code'] = $params['admin_code'];
    } else {
      // Find the last admin code
      $lastAdmin = StaffAdmission::orderBy('id', 'desc')->first();
      $lastAdminCode = $lastAdmin->id ?? 0;
      // Extract the numeric part and increment it
      $numericPart = (int)$lastAdminCode;
      // Calculate the number of digits required for the numeric part
      $numDigits = max(4, strlen((string)$numericPart));
      // Add one to the numeric part
      $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);
      $params['admin_code'] = 'TS' . $newNumericPart;
    }

    $params['role'] = 9;
    $params['status'] = Consts::STATUS['deactive'];
    $params['admin_type'] = Consts::ADMIN_TYPE['admission'];
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $params['name'] = $params['json_params']['last_name'] . ' ' . $params['json_params']['middle_name'] . ' ' . $params['json_params']['first_name'];

    $staffadmission = StaffAdmission::create($params);
    return redirect()->route($this->routeDefault . '.dashboard')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(StaffAdmission $staffadmission)
  {
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(StaffAdmission $staffadmission)
  {
    return redirect()->route($this->routeDefault . '.dashboard');
    $class = tbClass::getsqlClass()->get();
    $staffadmissions = StaffAdmission::getsqlStaffAdmission()->get();
    $teachers = Teacher::getsqlTeacher()->get();
    $direct_manager = $staffadmissions;

    $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $this->responseData['roles'] = $roles;
    $this->responseData['direct_manager'] =  $direct_manager;
    $this->responseData['class'] =  $class;
    $this->responseData['detail'] = $staffadmission;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['gender'] = Consts::GENDER;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, StaffAdmission $staffadmission)
  {
    return redirect()->route($this->routeDefault . '.dashboard');
    $arr_lang_code = [];
    $all_lang = Language::where('status', Consts::STATUS['active'])->get();
    foreach ($all_lang as $val) {
      $arr_lang_code[] = $val->lang_code;
    }

    $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
    $params = $request->all();
    if (isset($params['lang'])) {
      $lang = $params['lang'];
      unset($params['lang']);
    }
    $request->validate([
      // 'name' => 'required',
      'email' => "required|email|max:255|unique:admins,email," . $staffadmission->id,
    ]);
    $password_new = $request->input('password_new');
    if ($password_new != '') {
      if (strlen($password_new) < 8) {
        return redirect()->back()->with('errorMessage', __('Password is very short!'));
      }
      $params['password'] = $password_new;
    }
    if (isset($params['admin_code'])) {
      $params['admin_code'] = $params['admin_code'];
    } else {
      // Find the last admin code
      $lastAdmin = StaffAdmission::where('admin_type', Consts::ADMIN_TYPE['admission'])->find($staffadmission->id);
      $lastAdminCode = $lastAdmin->id ?? 0;
      // Extract the numeric part and increment it
      $numericPart = (int)$lastAdminCode;
      // Calculate the number of digits required for the numeric part
      $numDigits = max(4, strlen((string)$numericPart));
      // Add one to the numeric part
      $newNumericPart = str_pad($numericPart, $numDigits, '0', STR_PAD_LEFT);

      $params['admin_code'] = 'TS' . $newNumericPart;
    }

    $params['role'] = 9;
    $params['status'] = Consts::STATUS['deactive'];
    $params['admin_type'] = Consts::ADMIN_TYPE['admission'];
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $params['name'] = $params['json_params']['last_name'] . ' ' . $params['json_params']['middle_name'] . ' ' . $params['json_params']['first_name'];

    $arr_insert = $params;
    // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
    if ($staffadmission->json_params != "") {
      foreach ($staffadmission->json_params as $key => $val) {
        if (in_array($key, ['field_id'])) {
          continue;
        }
        if (isset($arr_insert['json_params'][$key])) {
          if ($arr_insert['json_params'][$key] != null) {
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
          }
        }
      }
    }
    // dd($arr_insert);
    $staffadmission->fill($arr_insert);
    $staffadmission->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(StaffAdmission $staffadmission)
  {
    return redirect()->route($this->routeDefault . '.dashboard');
    $staffadmission->delete();

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }

  public function area(Request $request)
  {
    /**
     * Nếu có id lớp học sẽ trả về json danh sách sinh viên trong lớp học đó
     */
    $class_id = $request->class_id;
    if ($class_id > 0) {
      // Lấy danh sách tổng họp học viên theo lớp
      $params['class_id'] = $class_id;
      $params['status'] = Consts::STATUS['active'];
      $list_student = Student::getSqlStudent($params)->get();
      return $this->sendResponse($list_student);
    }

    $this->responseData['module_name'] = __('Quản lý học viên theo khu vực');
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['status_class'] = Consts::CLASS_STATUS;
    $admin =  Auth::guard('admin')->user()->id;

    // Lấy list id khu vực được quản lý
    $service  = new DataPermissionService;
    $list_area = $service->getPermisisonAreas($admin);

    $this->responseData['list_area'] = DB::table('tb_areas')
      ->select('tb_areas.*')
      ->selectRaw('COUNT(admins.id) AS total_student')
      ->leftJoin('admins', function ($join) {
        $join->on('tb_areas.id', '=', 'admins.area_id')
          ->where('admins.admin_type', Consts::ADMIN_TYPE['student'])
          ->where('admins.status', Consts::STATUS['active']);
      })
      ->whereIn('tb_areas.id', $list_area)
      ->groupBy('tb_areas.id')
      ->get();
    // Lấy danh sách lớp học trong khu vực
    $list_class = $service->getPermissionClasses($admin);
    $this->responseData['list_class'] = DB::table('tb_classs')
      ->select('tb_classs.*')
      ->selectRaw('COUNT(tb_user_class.id) AS total_student')
      ->leftJoin('tb_user_class', 'tb_classs.id', '=', 'tb_user_class.class_id')
      ->whereIn('tb_classs.id', $list_class)
      ->groupBy('tb_classs.id')
      ->get();

    return $this->responseView($this->viewPart . '.area');
  }

  public function dashboard()
  {
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;
    $admin =  Auth::guard('admin')->user();
    $this->responseData['admin'] =  $admin->id;
    // Lấy list id cấp dưới và chính nó
    $permission  = new DataPermissionService;
    $list_permission = $permission->getPermissionUsersAndSelfAll($admin->id);
    // lấy tất cả học viên thuộc list id
    // $params['status'] = Consts::STATUS['active'];
    $params['list_admission_id'] = $list_permission;
    $rows_student = Student::whereIn('admins.admission_id', $params['list_admission_id'])->get();
    $this->responseData['rows_student']  = $rows_student;
    // lấy thông tin cán bộ cấp dưới
    $params['arr_id'] = $list_permission;
    $rows_permission = StaffAdmission::getsqlStaffAdmission($params)->get();
    $this->responseData['rows_permission']  = $rows_permission;

    // lấy data cấp 0
    $detail = $rows_permission->first(function ($item, $key) use ($admin) {
      return $item->id == $admin->id;
    });
    $this->responseData['detail'] =  $detail ?? '';
    $student_childs = $rows_student->filter(function ($item, $key) use ($admin) {
      return $item->admission_id == $admin->id;
    });
    $this->responseData['student_childs'] =  $student_childs ?? '';
    $class = tbClass::getsqlClass()->get();
    $this->responseData['class'] =  $class;


    // Lấy view đệ quy cấp dưới
    $recursive_view = $this->recursive_view_admission($admin->id, $rows_student, $rows_permission);
    $this->responseData['view'] =  $recursive_view;

    return $this->responseView($this->viewPart . '.dashboard');
  }

  public function get_student(Request $request)
  {
    $params = $request->all();
    $id = $params['id'] ?? '';
    $page = $params['page'] ?? 1;
    $admin = Admin::find($id);
    $params_student['status'] = Consts::STATUS['active'];
    $params_student['admission_id'] = $admin->id;
    $params_student['keyword'] = $params['keyword'] ?? '';
    $params_student['class_id'] = $params['class_id'] ?? '';
    $rows_student = Student::getsqlStudent($params_student)->paginate(Consts::DEFAULT_PAGINATE_LIMIT, ['*'], 'page', $page);
    $this->responseData['rows'] =  $rows_student;
    $class = tbClass::getsqlClass()->get();
    $this->responseData['class'] =  $class;
    $this->responseData['admin'] =  $admin;
    return $this->responseView('admin.pages.staffadmissions.list_student');
  }

  protected $processedIds = [];

  public function recursive_view_admission($id, $data_student = null, $data_permission = null)
  {
    $str = '';

    foreach ($data_permission as $items) {
      if ($items->parent_id == $id && !in_array($items->id, $this->processedIds)) {
        $this->processedIds[] = $items->id; // Đánh dấu đã duyệt

        $student_childs = $data_student->filter(fn($item) => $item->admission_id == $items->id);
        $permission = $data_permission->filter(fn($item) => $item->parent_id == $items->id);

        $str .= '
                <li>
                    <details>
                        <summary>' . $items->admin_code . ' - ' . $items->name . '
                        (' . $permission->count() . ' Cán bộ cấp dưới - ' . $student_childs->count() . ' Học viên)
                        (' . __($items->status) . ')
                        </summary>
                        <ul>';

        if ($student_childs->count() > 0) {
          $str .= '
                            <li>
                                <details>
                                    <summary class="list_study" data-id="' . $items->id . '">' . __('Danh sách học viên tuyển sinh trực tiếp') . ' (' . $student_childs->count() . ' Học viên)</summary>
                                </details>
                            </li>';
        }

        $str .= $this->recursive_view_admission($items->id, $data_student, $data_permission);

        $str .= '
                        </ul>
                    </details>
                </li>
            ';
      }
    }

    return $str;
  }


  public function admissions_student(Request $request)
  {
    $params = $request->all();
    $admin = Auth::guard('admin')->user();
    $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
    $params['status'] = Consts::STATUS['active'];
    $rows = Student::getsqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;


    $this->responseData['module_name'] = __('Thống kê học viên theo tuyển sinh');
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['admin'] =  $admin;

    $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
      ->where('status', Consts::STATUS['active'])
      ->get();
    $class = tbClass::getsqlClass()->get();
    $status_student = StatusStudent::getSqlStatusStudent()->get();
    $this->responseData['staffs'] =  $staffs;
    $this->responseData['class'] =  $class;
    $this->responseData['status_study'] =  $status_student;
    $this->responseData['route_name'] = Consts::ROUTE_NAME;
    $this->responseData['status'] = Consts::STUDENT_STATUS;
    $this->responseData['params'] = $params;
    $paramCourse['status'] = Consts::STATUS['active'];
    $this->responseData['course'] = Course::getSqlCourse($paramCourse)->get();

    return $this->responseView($this->viewPart . '.student');
  }

  public function view_student(Request $request)
  {
    $params = $request->all();
    $id = $params['student'] ?? '';
    $student = Student::find($id);
    $list_class = UserClass::where('user_id', $student->id)->groupBy('class_id')->get();
    $this->responseData['detail'] = $student;
    $this->responseData['module_name'] = 'Thông tin sinh viên: ' . $student->name;
    $list_evolution = Evaluation::where('student_id', $student->id)->get();
    foreach ($list_class as  $item) {

      if (isset($item->class->json_params->teacher)) {
        $item->teacher = Admin::find($item->class->json_params->teacher)->name ?? "";
      } else $item->teacher = "";

      $params_score['class_id'] = $item->class_id;
      $params_score['user_id'] = $item->user_id;
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
      //Vắng có lý do
      $absent_has_reason = $absent->filter(function ($val, $key) {
        return $val->json_params->value == 'there reason';
      });
      isset($absent_has_reason) ? $has_reason = count($absent_has_reason) : $has_reason = 0;
      $item->absent_has_reason = $has_reason;
      //vắng không lý do
      $absent_no_reason = $absent->filter(function ($val, $key) {
        return $val->json_params->value == 'no reason';
      });
      isset($absent_no_reason) ? $no_reason = count($absent_no_reason) : $no_reason = 0;
      $item->no_reason = $no_reason;
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
    return $this->responseView('admin.pages.staffadmissions.view_student');
  }

  public function ScoreByStaff(Request $request)
  {
    $params = $request->all();
    $list_class = tbClass::getsqlClass(['type' => 'lopchinh'])->get();
    $this->responseData['list_class'] =  $list_class;

    $list_admission = StaffAdmission::getsqlStaffAdmission(['admin_type' => 'admission'])->get();
    $this->responseData['list_admission'] =  $list_admission;

    $params['staff_permission'] = isset($params['admission_id']) ? DataPermissionService::getPermissionUsersAndSelfAll($params['admission_id']) : [];
    $this->responseData['module_name'] = __('Lấy bảng điểm theo lớp');

    if (isset($params['class_id'])) {
      $this_class = tbClass::find($params['class_id']);
      $this->responseData['this_class'] = $this_class;

      $admin = Auth::guard('admin')->user();
      $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
      // Lấy danh sách điểm với danh sách học viên
      $rows = Score::getsqlScore($params)
        ->with(['student' => function ($query) use ($this_class) {
          $query->with(['userClasses' => function ($query) use ($this_class) {
            $query->whereHas('class', function ($subQuery) use ($this_class) {
              $subQuery->where('level_id', $this_class->level_id);
            });
          }]);
        }])
        ->get();

      // Gán điểm vào danh sách lớp của học viên
      foreach ($rows as $row) {
        $row->userClasses = $row->student->userClasses->map(function ($userClass) {
          $userClass->score = $userClass->class
            ? $userClass->class->scores->firstWhere('user_id', $userClass->user_id)
            : null;

          return $userClass;
        });
      }

      $this->responseData['rows'] =  $rows;
      $this->responseData['params'] = $params;
    }

    return $this->responseView($this->viewPart . '.score_by_staff');
  }
}
