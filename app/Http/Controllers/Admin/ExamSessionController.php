<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Course;
use App\Models\ExamSessionUser;
use App\Models\tbClass;
use App\Models\UserClass;
use App\Models\Student;
use App\Consts;
use Exception;
use Illuminate\Support\Facades\DB;

class ExamSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'exam_session';
        $this->viewPart = 'admin.pages.exam_session';
        $this->responseData['module_name'] = 'Quản lý buổi thi';
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = ExamSession::getSqlExamSession($params)->orderBy('id','desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $this->responseData['course'] = Course::getSqlCourse($params_course)->get();
        $this->responseData['type'] = Consts::TYPE_EXAM_SESSION;
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
        $params_topic['status']=Consts::STATUS['active'];
        $this->responseData['list_topic'] = Topic::getSqlTopic($params_topic)->orderBy('id','desc')->get();

        $params_class['type'] = 'lopphu';
        $this->responseData['trial_class'] = tbClass::getSqlClass($params_class)->get();
        $this->responseData['type'] = Consts::TYPE_EXAM_SESSION;
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $this->responseData['course'] = Course::getSqlCourse($params_course)->get();

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
            'title' => 'required',
            'day_exam' => "required",
            'course_id' => "required",
            'time_exam_start' => "required",
            'time_exam' => "required",
        ]);
        DB::beginTransaction();
        try {
            $params=$request->all();
            $params['list_topic']=json_encode($params['list_topic']);
            $params['list_class']=json_encode($params['list_class']);
            // dd($params['list_class']);
            $exam = ExamSession::create($params);
            // $student=Student::where('course_id',$request->course_id)->where('state',Consts::STUDENT_STATUS['try learning'])->get();
            $params_user_class['array_class_id']=$request->list_class;
            $params_user_class['student_state']=Consts::STUDENT_STATUS['try learning'];
            $student=UserClass::getSqlUserClass($params_user_class)->get();
            $data = [];
            foreach ($student as $item) {
                $params2['exam_id'] =$exam->id;
                $params2['user_id'] =$item['user_id'];
                $params2['status'] = Consts::STATUS_EXAM_USER['new'];
                array_push($data, $params2);
            }
            ExamSessionUser::insert($data);
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSession $examSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamSession $examSession)
    {
        $params_topic['status']=Consts::STATUS['active'];
        $this->responseData['list_topic'] = Topic::getSqlTopic($params_topic)->orderBy('id','desc')->get();

        $params_class['type'] = 'lopphu';
        $this->responseData['trial_class'] = tbClass::getSqlClass($params_class)->get();

        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $this->responseData['course'] = Course::getSqlCourse($params_course)->get();
        $this->responseData['type'] = Consts::TYPE_EXAM_SESSION;
        $params_student['exam_id']=$examSession->id;
        $this->responseData['list_student'] = ExamSessionUser::getSqlExamSessionUser($params_student)->get();
        $this->responseData['detail'] = $examSession;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamSession $examSession)
    {
        $request->validate([
            'title' => 'required',
            'day_exam' => "required",
            'time_exam_start' => "required",
            'time_exam' => "required",
        ]);
        $params=$request->all();
        $examSession->fill($params);
        $examSession->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamSession $examSession)
    {
        DB::beginTransaction();
        try {
            ExamSessionUser::where('exam_id', $examSession->id)->delete();
            $examSession->delete();
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
        }catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    public function classByCourse(Request $request)
    {
        try {
            $params['course_id'] = $request->course_id;
            $params['type'] = 'lopphu';
            $rows = tbClass::getSqlClass($params)->get();
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
