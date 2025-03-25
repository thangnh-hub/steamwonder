<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\HvExamSessionUser;
use App\Models\Level;
use App\Models\Admin;
use App\Models\tbClass;
use App\Models\HvExamTopic;
use App\Models\HvExamOption;
use App\Models\HvExamAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HvExamResultController extends Controller
{
    protected $arr_lervel;
    public function __construct()
    {
        $this->arr_lervel = [1, 2, 3, 4, 5, 6];
        $this->routeDefault  = 'hv_exam_result';
        $this->viewPart = 'admin.pages.hv_exam_result';
        $this->responseData['module_name'] = __('Quản lý kết quả thi');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $rows = HvExamSessionUser::getSqlHvExamSessionUser($params)->orderBy('id', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;

        $this->responseData['list_admins'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
        $this->responseData['class'] =  tbClass::getsqlClass()->get();
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HvExamSession  $hvExamSession
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Lấy phiên thi và kiểm tra tính hợp lệ
        $exam_session_user = HvExamSessionUser::find($id);
        if (!$exam_session_user || empty($exam_session_user->json_params)) {
            return redirect()->back()->with('errorMessage', 'Không tìm thấy dữ liệu!');
        }

        $this->responseData['row'] = $exam_session_user;

        // Lấy thông tin option
        $option = HvExamOption::find($exam_session_user->json_params->option->id_option);
        if (!$option) {
            return redirect()->back()->with('errorMessage', 'Không tìm thấy dữ liệu liên quan đến Option!');
        }
        $this->responseData['option'] = $option;
        $data = [];
        $exam_question_ids = [];
        foreach ($option->json_params->topic as $key => $value) {
            $data[$key]['content_option'] = $value->content;
            // Lấy danh sách ID topic từ JSON params
            $topics = (array) $exam_session_user->json_params->option->topic;
            $id_topic = array_keys($topics);
            // Truy vấn các topic và câu hỏi liên quan
            $toppic = HvExamTopic::whereIn('id', $id_topic)
                ->where('is_type', $key)
                ->with(['exam_questions' => function ($query) use ($topics) {
                    $query->whereIn('id', collect($topics)->flatten());
                }])
                ->get();
            // Xử lý thêm dữ liệu cho từng topic
            $toppic->each(function ($topic) use ($topics, &$exam_question_ids) {
                $topic->exam_questions = $topic->exam_questions->whereIn(
                    'id',
                    (array) ($topics[$topic->id] ?? [])
                );
                // Gộp các ID câu hỏi vào mảng $exam_question_ids
                $exam_question_ids = array_merge($exam_question_ids, $topic->exam_questions->pluck('id')->toArray());
            });
            $data[$key]['topic'] = $toppic;
        }
        $this->responseData['data'] = $data;
        $this->responseData['his_answer'] = $exam_session_user->json_params->answer;
        $this->responseData['arr_correct_answer'] = HvExamAnswers::whereIn('id_question', $exam_question_ids)
            ->where('correct_answer', 1)
            ->pluck('id')
            ->toArray();
        // dd($this->responseData['correct_answer']);
        return $this->responseView($this->viewPart . '.show');
    }
    public function reset(Request $request)
    {
        $id = $request->only('id')['id'];
        $session_user = HvExamSessionUser::find($id);
        if (!$session_user) {
            return $this->sendResponse('error', __('Không tìm thấy dữ liệu!'));
        }
        $session_user
            ->update([
                'score' => null,
                'status' => Consts::STATUS_EXAM_USER['new'],
                'json_params' => null,
                'admin_updated_id' => Auth::guard('admin')->user()->id,
            ]);
        session()->flash('successMessage', 'Reset phiên thi thành công!');
        return $this->sendResponse('success', __('Cập nhật thành công!'));
    }
}
