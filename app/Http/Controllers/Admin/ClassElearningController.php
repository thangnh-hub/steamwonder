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
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;

class ClassElearningController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'classs_elearning';
        $this->viewPart = 'admin.pages.classs_elearning';
        $this->responseData['module_name'] = 'Quản lý lớp học Elearning';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $params = $request->all();
        $params['type'] = 'elearning';
        // Get list post with filter params
        $rows = tbClass::getSqlClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
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
        $paramcourse['status'] = Consts::STATUS['active'];
        $paramcourse['type'] = Consts::SYLLABUS_TYPE['elearning'];

        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->where('type',Consts::SYLLABUS_TYPE['elearning'])->get();
        $this->responseData['course'] = Course::getSqlCourse($paramcourse)->get();
        $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
        $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);

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
        
        $request->validate([
            'name' => 'required',
            'level_id' => "required",
            'syllabus_id' => "required",
            'course_id' => "required",
            'area_id' => "required",
        ]);
        $params = $request->all();
        $params['period_id'] = 0;
        $params['room_id'] = 0;
        $class = tbClass::create($params);
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
        $classs=tbClass::find($id);
        $params['class_id'] = $classs->id;
        $this->responseData['this_class'] = $classs;
        $this->responseData['rows'] = Student::getsqlStudent($params)->get();
        $this->responseData['staffs'] = StaffAdmission::getSqlStaffAdmission()->get();
        $this->responseData['teacher'] =  Teacher::getSqlTeacher()->get();
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
       
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
        $classs=tbClass::find($id);
        $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        if (!in_array($classs->id, $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
        $paramSyllabus['approve'] = Consts::APPROVE[1];
        $paramSyllabus['type'] = Consts::SYLLABUS_TYPE['elearning'];
        $paramStatus['status'] = Consts::STATUS['active'];
        $paramcourse['status'] = Consts::STATUS['active'];
        $paramcourse['type'] = Consts::SYLLABUS_TYPE['elearning'];

        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->get();
        $this->responseData['course'] = Course::getSqlCourse($paramcourse)->get();
        $this->responseData['area'] = Area::getSqlArea($paramStatus)->get();
        $this->responseData['detail'] = $classs;

        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['area_user'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_class'] = tbClass::where('id', "!=", $classs->id)->get();
        $param_userclass['class_id']=$classs->id;
        $this->responseData['student'] = UserClass::getSqlUserClass($param_userclass)->groupBy('user_id')->get();
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
        $classs=tbClass::find($id);
        $request->validate([
            'name' => 'required',
        ]);
        $params = $request->only(['name','day_exam','area_id','room_id','json_params','status','end_date']);
        $arr_insert = $params;
        $classs->fill($arr_insert);
        $classs->save();

        if ($classs->save()) {
            $params_student = $request['student'];
            if ($params_student) {
                UserClass::where('class_id', $classs->id)->delete();
                foreach ($params_student as $t => $item) {
                    $params3['class_id'] = $classs->id;
                    $params3['user_id'] = $item;
                    $params3['status'] = $request['user_class_status'][$t];
                    $params3['json_params']['day_in_class'] = $request['day_in_class'][$t];
                    UserClass::create($params3);
                }
            } else
            UserClass::where('class_id', $classs->id)->delete();
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
    public function syllabusOnlineBylevel(Request $request)
    {
        try {
            $params['type'] = Consts::SYLLABUS_TYPE['elearning'];
            $params['level_id'] = $request->id;
            $params['is_flag'] = $request->is_flag ?? '';
            $rows = Syllabus::getSqlSyllabus($params)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
