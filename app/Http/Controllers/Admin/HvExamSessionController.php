<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\HvExamSession;
use App\Models\HvExamSessionUser;
use App\Models\tbClass;
use App\Models\UserClass;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HvExamSessionController extends Controller
{
    protected $arr_lervel;
    public function __construct()
    {
        $this->arr_lervel = [1, 2, 3, 4, 5, 6];
        $this->routeDefault  = 'hv_exam_session';
        $this->viewPart = 'admin.pages.hv_exam_session';
        $this->responseData['module_name'] = __('Quản lý phiên thi');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $rows = HvExamSession::getSqlHvExamSession($params)->orderBy('day_exam', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['list_admins'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params_class['status'] = 'dang_hoc';
        $this->responseData['classs'] = tbClass::where('status', 'dang_hoc')->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        $this->responseData['list_admins'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();

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
            'day_exam' => "required",
            // 'time_exam' => "required",
            'start_time' => "required",
            'end_time' => "required",
            'id_invigilator' => "required",
            'id_grader_exam' => "required",
            'id_level' => "required",
            // 'organization' => "required",
            'skill_test' => "required",
            'student' => "required|array|min:1",
        ]);

        DB::beginTransaction();
        try {
            $params = $request->only(
                'day_exam',
                // 'time_exam',
                'start_time',
                'end_time',
                'id_invigilator',
                'id_grader_exam',
                'id_level',
                'organization',
                'skill_test',
                'json_params'
            );
            $student = $request->only('student')['student'];
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $exam = HvExamSession::create($params);

            $data = [];
            foreach ($student as $id) {
                $user_class = UserClass::where('user_id', $id)->orderBy('id', 'DESC')->first();
                $params_user['id_exam_session'] = $exam->id;
                $params_user['id_user'] = $id;
                $params_user['id_class'] = $user_class->class_id ?? '';
                $params_user['id_grader_exam'] = $exam->id_grader_exam;
                $params_user['id_level'] = $exam->id_level;
                $params_user['skill_test'] = $exam->skill_test;
                $params_user['status'] = Consts::CONTACT_STATUS['new'];
                $params_user['admin_created_id'] = Auth::guard('admin')->user()->id;
                array_push($data, $params_user);
            }
            HvExamSessionUser::insert($data);
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HvExamSession  $hvExamSession
     * @return \Illuminate\Http\Response
     */
    public function show(HvExamSession $hvExamSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HvExamSession  $hvExamSession
     * @return \Illuminate\Http\Response
     */
    public function edit(HvExamSession $hvExamSession)
    {
        $params_class['status'] = 'dang_hoc';
        $this->responseData['classs'] = tbClass::where('status', 'dang_hoc')->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['list_admins'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
        $params_student['id_exam_session'] = $hvExamSession->id;
        $this->responseData['list_student'] = HvExamSessionUser::getSqlHvExamSessionUser($params_student)->get();
        $arr_id_student = $this->responseData['list_student']->map(function ($item) {
            return $item->id_user;
        })->toArray();
        $this->responseData['arr_id_student'] = $arr_id_student;
        $this->responseData['detail'] = $hvExamSession;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HvExamSession  $hvExamSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HvExamSession $hvExamSession)
    {
        if ($hvExamSession->status != Consts::CONTACT_STATUS['new']) {
            return redirect()->back()->with('errorMessage', 'Không thể xóa phiên thi');
        }
        $request->validate([
            'day_exam' => "required",
            // 'time_exam' => "required",
            'start_time' => "required",
            'end_time' => "required",
            'id_invigilator' => "required",
            'id_grader_exam' => "required",
            'id_level' => "required",
            // 'organization' => "required",
            'skill_test' => "required",
            'student' => "required|array|min:1",
        ]);
        $params = $request->only(
            'day_exam',
            // 'time_exam',
            'start_time',
            'end_time',
            'id_invigilator',
            'id_grader_exam',
            'id_level',
            'organization',
            'skill_test',
            'json_params'
        );
        DB::beginTransaction();
        try {
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $hvExamSession->fill($params);
            $hvExamSession->save();
            $student = $request->only('student')['student'];
            HvExamSessionUser::where('id_exam_session', $hvExamSession->id)->where('status', 'new')->delete();
            $data = [];
            foreach ($student as $id) {
                $exists = HvExamSessionUser::where('id_exam_session', $hvExamSession->id)
                    ->where('id_user', $id)
                    ->exists();
                if (!$exists) {
                    $user_class = UserClass::where('user_id', $id)->orderBy('id', 'DESC')->first();
                    $params_user['id_exam_session'] = $hvExamSession->id;
                    $params_user['id_user'] = $id;
                    $params_user['id_class'] = $user_class->class_id ?? '';
                    $params_user['id_grader_exam'] = $hvExamSession->id_grader_exam;
                    $params_user['id_level'] = $hvExamSession->id_level;
                    $params_user['skill_test'] = $hvExamSession->skill_test;
                    $params_user['status'] = Consts::CONTACT_STATUS['new'];
                    $params_user['admin_created_id'] = Auth::guard('admin')->user()->id;
                    array_push($data, $params_user);
                }
            }
            HvExamSessionUser::insert($data);
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
     * @param  \App\Models\HvExamSession  $hvExamSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(HvExamSession $hvExamSession)
    {
        if ($hvExamSession->status != Consts::CONTACT_STATUS['new']) {
            return redirect()->back()->with('errorMessage', 'Không thể xóa phiên thi');
        }
        HvExamSessionUser::where('id_exam_session', $hvExamSession->id)->delete();
        $hvExamSession->delete();
        return redirect()->back()->with('successMessage', __('Xóa phiên thi thành công!'));
    }

    public function searchStudent(Request $request)
    {
        $params = $request->only('keyword', 'class_id', 'different_id');
        $list_student = Student::getSqlStudent($params)->with('level', 'area', 'course', 'classs', 'class_detal')->get();
        if (count($list_student) > 0) {
            return $this->sendResponse($list_student, 'success');
        }
        return $this->sendResponse('', __('No records available!'));
    }
}
