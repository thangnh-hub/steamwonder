<?php

namespace App\Http\Controllers\Admin;

use App\Models\HvExamQuestions;
use App\Models\HvExamAnswers;
use App\Models\HvExamTopic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HvExamQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'is_type' => 'required',
            'id_topic' => "required",
            'question' => "required",
            'point' => "required",
            'answer' => "required",
        ]);
        DB::beginTransaction();
        try {
            $params = $request->only('id_topic', 'is_type', 'question','point');
            $answer = $request->only('answer')['answer'] ?? '';
            // Lấy thông tin phần thi để lưu kèm thông tin câu hỏi
            $topic = HvExamTopic::find((int)$params['id_topic']);
            if ($topic) {
                $params['admin_created_id'] = Auth::guard('admin')->user()->id;
                $questions = HvExamQuestions::create($params);
                // Lưu vào bảng answer
                $params_answer['id_question'] = $questions->id;
                switch ($params['is_type']) {
                    case 'chon_dap_an':
                        foreach ($answer as $item) {
                            $params_answer['answer'] = $item['value'];
                            $params_answer['correct_answer'] = isset($item['boolean']) && $item['boolean'] == 1 ? $item['boolean'] : 0;
                            $answer = HvExamAnswers::create($params_answer);
                        }
                        break;
                    default:
                        // Loại nhập đáp án đúng
                        $params_answer['answer'] = $answer;
                        $params_answer['correct_answer'] = 1;
                        $answer = HvExamAnswers::create($params_answer);
                        break;
                }
            }
            DB::commit();
            return redirect()->route('hv_exam_topic.edit', $topic->id)->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HvExamQuestions  $hvExamQuestions
     * @return \Illuminate\Http\Response
     */
    public function show(HvExamQuestions $hvExamQuestions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HvExamQuestions  $hvExamQuestions
     * @return \Illuminate\Http\Response
     */
    public function edit(HvExamQuestions $hvExamQuestions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HvExamQuestions  $hvExamQuestions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => "required",
            'point' => "required",
            'answer' => "required",
        ]);
        DB::beginTransaction();
        try {
            $hvExamQuestions = HvExamQuestions::find($id);
            $params = $request->only('question','point');
            $answer = $request->only('answer')['answer'] ?? '';
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $hvExamQuestions->fill($params);
            $hvExamQuestions->save();
            // Cập nhật lại bảng answer
            $params_answer['id_question'] = $hvExamQuestions->id;

            HvExamAnswers::where('id_question', $hvExamQuestions->id)->delete();
            switch ($hvExamQuestions->is_type) {
                case 'chon_dap_an':
                    foreach ($answer as $item) {
                        $params_answer['answer'] = $item['value'];
                        $params_answer['correct_answer'] = isset($item['boolean']) && $item['boolean'] == 1 ? $item['boolean'] : 0;
                        $answer = HvExamAnswers::create($params_answer);
                    }
                    break;
                default:
                    // Loại nhập đáp án đúng
                    $params_answer['answer'] = $answer;
                    $params_answer['correct_answer'] = 1;
                    $answer = HvExamAnswers::create($params_answer);
                    break;
            }

            DB::commit();
            return redirect()->route('hv_exam_topic.edit', $hvExamQuestions->id_topic)->with('successMessage', __('Cập nhật thành công!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HvExamQuestions  $hvExamQuestions
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hvExamQuestions = HvExamQuestions::find($id);
        if($hvExamQuestions){
            //Xóa câu hỏi và danh sách đáp án
            HvExamAnswers::where('id_question', $hvExamQuestions->id)->delete();
            $hvExamQuestions->delete();
        }
        return redirect()->back()->with('successMessage', __('Xóa thông tin thành công!'));
    }
}
