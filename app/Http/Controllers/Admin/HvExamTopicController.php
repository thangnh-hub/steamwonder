<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\HvExamTopic;
use App\Models\HvExamQuestions;
use App\Models\HvExamAnswers;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HvExamTopicImport;

class HvExamTopicController extends Controller
{
    protected $arr_lervel;
    protected $arr_group;
    public function __construct()
    {
        $this->arr_lervel = [1, 2, 3, 4, 5, 6];
        $this->arr_group = ['1', '1a', '1b', '2', '3', '4', '5'];
        $this->routeDefault  = 'hv_exam_topic';
        $this->viewPart = 'admin.pages.hv_exam_topic';
        $this->responseData['module_name'] = __('Quản lý phần thi');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['group'] = $this->arr_group;
        $this->responseData['type'] = Consts::TYPE_EXAM;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['organization'] = Consts::SCORE_TYPE;
        $rows = HvExamTopic::getSqlHvExamTopics($params)->orderBy('id', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
        $this->responseData['organization'] = Consts::SCORE_TYPE;
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['group'] = $this->arr_group;
        $this->responseData['type'] = Consts::TYPE_EXAM;
        $this->responseData['organization'] = Consts::SCORE_TYPE;
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
            'id_level' => 'required',
            'is_type' => "required",
            'skill_test' => "required",
            // 'organization' => "required",
            'content' => "required",
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $topic = HvExamTopic::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HvExamTopic  $hvExamTopic
     * @return \Illuminate\Http\Response
     */
    public function show(HvExamTopic $hvExamTopic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HvExamTopic  $hvExamTopic
     * @return \Illuminate\Http\Response
     */
    public function edit(HvExamTopic $hvExamTopic)
    {
        $this->responseData['detail'] = $hvExamTopic->load('exam_questions', 'exam_questions.exam_answers');
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['group'] = $this->arr_group;
        $this->responseData['type'] = Consts::TYPE_EXAM;
        $this->responseData['organization'] = Consts::SCORE_TYPE;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HvExamTopic  $hvExamTopic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HvExamTopic $hvExamTopic)
    {
        $request->validate([
            'id_level' => 'required',
            'is_type' => "required",
            'skill_test' => "required",
            // 'organization' => "required",
        ]);
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $hvExamTopic->fill($params);
        $hvExamTopic->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HvExamTopic  $hvExamTopic
     * @return \Illuminate\Http\Response
     */
    public function destroy(HvExamTopic $hvExamTopic)
    {
        // Lấy tất cả id của các câu hỏi liên quan đến toppic
        $questionIds = HvExamQuestions::where('id_topic', $hvExamTopic->id)->pluck('id');
        // Xóa tất cả câu trả lời liên quan đến các câu hỏi
        HvExamAnswers::whereIn('id_question', $questionIds)->delete();
        // Xóa tất cả các câu hỏi liên quan đến toppic
        HvExamQuestions::where('id_topic', $hvExamTopic->id)->delete();
        $hvExamTopic->delete();
        return redirect()->back()->with('successMessage', __('Xóa phiên thi thành công!'));
    }

    public function importExamTopic(Request $request)
    {
        $params = $request->all();
        if (isset($params['file'])) {
            if ($this->checkFileImport($params['file']) == false) {
                $_datawith = 'errorMessage';
                $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
                session()->flash($_datawith, $mess);
                return $this->sendResponse($_datawith, $mess);
            }
            $_datawith = 'successMessage';
            $import = new HvExamTopicImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            $data_count = $import->getRowCount();
            $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
            foreach ($data_count['error_mess'] as $val) {
                $mess .= '</br>' . $val;
            };
            if (count($data_count['error_mess']) > 0) {
                $_datawith = 'errorMessage';
            };
            session()->flash($_datawith, $mess);
            return $this->sendResponse($_datawith, $mess);
        }
        session()->flash('errorMessage', __('Cần chọn file để Import!'));
        return $this->sendResponse('warning', __('Cần chọn file để Import!'));
    }
}
