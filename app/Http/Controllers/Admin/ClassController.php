<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Room;
use App\Models\TeacherClass;
use App\Models\StudentClass;
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
use App\Models\EducationAges;
use App\Models\EducationPrograms;
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
        $rows = tbClass::orderBy('id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['areas'] =  Area::getsqlArea($paramStatus)->get();
        $this->responseData['rooms'] =  Room::getSqlRoom($paramStatus)->get();
        $this->responseData['ages'] =  EducationAges::getSqlEducationAges($paramStatus)->get();
        $this->responseData['programs'] =  EducationPrograms::getSqlEducationPrograms($paramStatus)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
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
        $paramStatus['status'] = Consts::STATUS['active'];

        $this->responseData['areas'] =  Area::getsqlArea($paramStatus)->get();
        $this->responseData['rooms'] =  Room::getSqlRoom($paramStatus)->get();
        $this->responseData['ages'] =  EducationAges::getSqlEducationAges($paramStatus)->get();
        $this->responseData['programs'] =  EducationPrograms::getSqlEducationPrograms($paramStatus)->get();
        $this->responseData['status'] = Consts::STATUS;
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
        $request->validate([
            'code' => 'required|unique:tb_class',
            'name' => "required",
            'slot' => 'required',
            'room_id' => 'required',
            'education_age_id' => 'required',
            'education_program_id' => 'required',
        ]);
        $params = $request->only([
            'code',
            'name',
            'slot',
            'iorder',
            'area_id',
            'room_id',
            'education_age_id',
            'education_program_id',
            'is_lastyear',
            'status'
        ]);

        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();
            $params['admin_created_id'] = $admin->id;
            $params['admin_updated_id'] = $admin->id;
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
        $detail = $classs;
        $result['view'] = view($this->viewPart . '.show', compact('detail'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(tbClass $classs)
    {

        // $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        // if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['areas'] =  Area::getsqlArea($paramStatus)->get();
        $this->responseData['rooms'] =  Room::getSqlRoom($paramStatus)->get();
        $this->responseData['ages'] =  EducationAges::getSqlEducationAges($paramStatus)->get();
        $this->responseData['programs'] =  EducationPrograms::getSqlEducationPrograms($paramStatus)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type_student'] = Consts::TYPE_CLASS_STUDENT;
        $this->responseData['detail'] = $classs;
        // Danh sách học viên
        $this->responseData['students'] = Student::getsqlStudent($paramStatus)->get();
        // Danh sách giáo viên
        $this->responseData['teachers'] = Teacher::getSqlTeacher($paramStatus)->get();
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
        $admin = Auth::guard('admin')->user();
        DB::beginTransaction();
        try {
            $request->validate([
                'code' => 'required|unique:tb_class,code,' . $classs->id,
                'name' => "required",
                'slot' => 'required',
                'room_id' => 'required',
                'education_age_id' => 'required',
                'education_program_id' => 'required',
            ]);
            $params = $request->only([
                'code',
                'name',
                'slot',
                'iorder',
                'area_id',
                'room_id',
                'education_age_id',
                'education_program_id',
                'is_lastyear',
                'status'
            ]);

            $params['is_lastyear'] = $request->is_lastyear ?? '0';
            $classs->fill($params);
            $classs->save();
            /** Xử lý phần liên quan đến giáo viên trong lớp */
            $teachers = $request->input('teacher');
            if (isset($teachers) && count($teachers) > 0) {
                foreach ($teachers as $id => $val) {
                    // Kiểm tra xem giáo viên đã tồn tại trong lớp chưa
                    $check_teacher = TeacherClass::where('class_id', $classs->id)->where('teacher_id', $id)->first();
                    if ($check_teacher) {
                        // Nếu giáo viên đã tồn tại trong lớp thì update lại thông tin
                        $check_teacher->update([
                            'status' => $val['status'],
                            'start_at' => $val['start_at'] ??  now(),
                            'stop_at' => $val['stop_at'] ?? null,
                            'is_teacher_main' => $val['is_teacher_main'] ?? 0,
                            'admin_updated_id' => $admin->id
                        ]);
                    } else {
                        // Nếu giáo viên chưa tồn tại trong lớp thì tạo mới
                        TeacherClass::create([
                            'class_id' => $classs->id,
                            'teacher_id' => $id,
                            'status' => $val['status'],
                            'start_at' => $val['start_at'] ??  now(),
                            'stop_at' => $val['stop_at'] ?? null,
                            'is_teacher_main' => $val['is_teacher_main'] ?? 0,
                            'admin_created_id' =>  $admin->id,
                            'admin_updated_id' => $admin->id,
                        ]);
                    }
                }
                // Cập nhật status thành 'delete' cho các teacher_id không còn trong danh sách $teachers
                $teacherIds = array_keys($teachers);
                TeacherClass::where('class_id', $classs->id)
                    ->whereNotIn('teacher_id', $teacherIds)
                    ->update(['status' => 'delete']);
            }


            /** Xử lý phần liên quan đến danh sách học viên trong lớp */
            $students = $request->input('student');
            if (isset($students) && count($students) > 0) {
                foreach ($students as $id => $val) {
                    // Kiểm tra xem giáo viên đã tồn tại trong lớp chưa
                    $check_student = StudentClass::where('class_id', $classs->id)->where('student_id', $id)->first();
                    if ($check_student) {
                        // Nếu học viên đã tồn tại trong lớp thì update lại thông tin
                        $check_student->update([
                            'status' => $val['status'],
                            'start_at' => $val['start_at'] ??  now(),
                            'stop_at' => $val['stop_at'] ?? null,
                            'type' => $val['type'] ?? null,
                            'admin_updated_id' => $admin->id
                        ]);
                    } else {
                        // Nếu giáo viên chưa tồn tại trong lớp thì tạo mới
                        StudentClass::create([
                            'class_id' => $classs->id,
                            'student_id' => $id,
                            'status' => $val['status'],
                            'start_at' => $val['start_at'] ??  now(),
                            'stop_at' => $val['stop_at'] ?? null,
                            'type' => $val['type'] ?? null,
                            'admin_created_id' =>  $admin->id,
                            'admin_updated_id' => $admin->id,
                        ]);
                    }
                }
                $studentIds = array_keys($students);
                StudentClass::where('class_id', $classs->id)
                    ->whereNotIn('student_id', $studentIds)
                    ->update(['status' => 'delete']);
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
        // $les = UserClass::where('class_id', $classs->id)->delete();
        // $les = Attendance::where('class_id', $classs->id)->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
