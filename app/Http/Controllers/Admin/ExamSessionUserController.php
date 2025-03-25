<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Course;
use App\Models\ExamSessionUser;
use App\Models\tbClass;
use App\Models\Student;
use App\Models\Admin;
use App\Consts;
use App\Exports\ExamResultExport;
use App\Exports\ExamResultStudentExport;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ExamSessionUserController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'exam_session_user';
        $this->viewPart = 'admin.pages.exam_session_user';
        $this->responseData['module_name'] = 'Quản lý danh sách thi';
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = ExamSessionUser::getSqlExamSessionUser($params)->orderBy('exam_id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $this->responseData['course'] = Course::getSqlCourse($params_course)->get();
        $this->responseData['params'] = $params;
        $params_class['type'] = 'lopphu';
        $class = tbClass::getsqlClass($params_class)->get();
        $this->responseData['class'] =  $class;
        $this->responseData['status'] = Consts::STATUS_EXAM_USER;
        $this->responseData['admission'] =Admin::where('status',Consts::STATUS['active'])->where('admin_type','!=',Consts::ADMIN_TYPE['student'])->get();
        return $this->responseView($this->viewPart . '.index');
    }

    public function examResult(Request $request)
    {
        $params = $request->all();
        $params['status'] = 'done';
        $rows = ExamSessionUser::getSqlExamResult($params)->orderBy('exam_id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = 'Quản lý kết quả thi của học viên';
        $params_class['type'] = 'lopphu';
        $class = tbClass::getsqlClass($params_class)->get();
        $this->responseData['class'] =  $class;
        $params_course['order_by'] = ['day_opening' => 'desc', 'id' => 'desc'];
        $this->responseData['course'] = Course::getSqlCourse($params_course)->get();
        $this->responseData['admission'] =Admin::where('status',Consts::STATUS['active'])->where('admin_type','!=',Consts::ADMIN_TYPE['student'])->get();
        return $this->responseView($this->viewPart . '.result');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamSessionUser  $examSessionUser
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSessionUser $examSessionUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExamSession  $examSessionUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamSessionUser $examSessionUser)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamSession  $examSessionUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamSessionUser $examSessionUser)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamSessionUser  $examSessionUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamSessionUser $examSessionUser)
    {
        $examSessionUser->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function exportExamResult(Request $request)
    {
        $params = $request->all();
        return Excel::download(new ExamResultExport($params), 'ExamResult.xlsx');
    }
    public function resetStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'exam_id' => 'required',
        ]);
        $params = $request->all();
        $exam =  ExamSessionUser::getSqlExamSessionUser($params)->first();
        if ($exam) {
            // if($exam->status == Consts::STATUS_EXAM_USER['done']){
            //     session()->flash('errorMessage', 'Không thể làm mới! Học viên '.$exam->student->name.' đã hoàn thành bài thi');
            //     return $this->sendResponse('errorMessage', 'Lỗi cập nhật');
            // }
            $params['status'] = Consts::STATUS_EXAM_USER['new'];
            $params['score'] = null;
            $exam->fill($params);
            $exam->save();
            session()->flash('successMessage', 'Reset trạng thái thành công');
        } else {
            session()->flash('errorMessage', 'Không tìm thấy dữ liệu');
        }
        return $this->sendResponse('success', 'Cập nhật thành công');
    }
    public function resetPoint(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'exam_id' => 'required',
        ]);
        $params = $request->all();
        $exam =  ExamSessionUser::getSqlExamSessionUser($params)->first();
        if ($exam) {
            $params['status'] = Consts::STATUS_EXAM_USER['done'];
            $exam->fill($params);
            $exam->save();
            session()->flash('successMessage', 'Cập nhật điểm thành công');
        } else {
            session()->flash('errorMessage', 'Không tìm thấy dữ liệu');
        }
        return $this->sendResponse('success', 'Cập nhật thành công');
    }

    public function exportExamResultStudent(Request $request)
    {
        $params = $request->all();
        return Excel::download(new ExamResultStudentExport($params), 'ExamResultStudent.xlsx');
    }
}
