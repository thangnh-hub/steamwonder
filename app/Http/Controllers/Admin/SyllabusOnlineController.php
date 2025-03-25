<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Exception;
use App\Models\Language;
use App\Models\Syllabus;
use App\Models\LessonGrammar;
use App\Models\LessonSylabu;
use App\Models\LessonSylabuQuiz;
use App\Models\Quiz;
use App\Models\Level;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SyllabusOnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'syllabuss_online';
        $this->viewPart = 'admin.pages.syllabuss_online';
        $this->responseData['module_name'] = __('Quản lý chương trình online');
    }
    public function index(Request $request)
    {
        $params = $request->all();
        $params['type'] = Consts::SYLLABUS_TYPE['elearning'];
        // Get list post with filter params
        $rows = Syllabus::getSqlSyllabus($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['approve'] = Consts::APPROVE;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['forms_training'] = Consts::FORMS_TRAINING;
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
            'name' => 'required|unique:tb_syllabuss',
            'level_id' => "required",
        ]);

        $params = $request->all();
        $Syllabus = Syllabus::create($params);
        return redirect()->route($this->routeDefault . '.edit', $Syllabus->id)->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $syllabuss = Syllabus::find($id);
        if (isset($syllabuss)) {
            $this->responseData['detail'] = $syllabuss;
            $lessonSylabus = LessonSylabu::where('syllabus_id', $syllabuss->id)->get();
            $this->responseData['lessonSylabus'] = $lessonSylabus;
            $this->responseData['approve'] = Consts::APPROVE;
            $this->responseData['route_name'] = Consts::ROUTE_NAME;
            $this->responseData['forms_training'] = Consts::FORMS_TRAINING;
            return $this->responseView($this->viewPart . '.edit');
        } else return redirect()->back()->with('errorMessage', __('Không tìm thấy chương trình'));
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
        $syllabuss = Syllabus::find($id);
        if (isset($syllabuss)) {
            $request->validate([
                'name' => 'required|unique:tb_syllabuss,name,' . $syllabuss->id,
                'level_id' => "required",
            ]);

            $params = $request->all();
            $syllabuss->fill($params);
            $syllabuss->save();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } else return redirect()->back()->with('errorMessage', __('Không tìm thấy chương trình'));
    }
    public function formAddLesson(Request $request)
    {
        try {
            $this->responseData['params'] = $request->all();
            return $this->responseView($this->viewPart . '.layout_creat.show');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function saveLession(Request $request)
    {
        try {
            if (isset($request->syllabus_id) && $request->syllabus_id != "") {
                $params = $request->all();
                $syllabus_lesson = LessonSylabu::create($params);
                return redirect()->back()->with('successMessage', __('Successfully added!'));
            }
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function formShowLesson(Request $request)
    {
        try {
            $lesson = LessonSylabu::find($request->id_lesson);
            $grammars = LessonGrammar::where('id_lesson', $request->id_lesson)->where('type', 'grammar')->get();
            $vocabularys = LessonGrammar::where('id_lesson', $request->id_lesson)->where('type', 'vocabulary')->get();
            $this->responseData['vocabularys'] = $vocabularys;
            $this->responseData['grammars'] = $grammars;
            $this->responseData['lesson'] = $lesson;
            return $this->responseView($this->viewPart . '.layout_update.show');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function updateLession(Request $request)
    {
        if (isset($request->lesson_id) && $request->lesson_id != "") {
            $lesson = LessonSylabu::find($request->lesson_id);
            $params = $request->except(['gramma_lesson', 'vocabulary_lesson', 'lesson_id']);
            $lesson->fill($params);
            $lesson->save();

            if ($lesson) {
                $grammars = LessonGrammar::where('id_lesson', $request->lesson_id)->delete();
                // $vocabulary_lesson=$request->vocabulary_lesson;
                // foreach ($vocabulary_lesson as $item) {
                //     $params2['id_lesson'] =$lesson->id;
                //     $params2['type'] =$item['type'];
                //     $params2['content'] =$item['grammar_name'];
                //     $params2['image'] = $item['image'] ?? "";
                //     $params2['audio'] = $item['audio'] ?? "";
                //     LessonGrammar::create($params2);
                // }

                // $gramma_lesson = $request->gramma_lesson;
                // if ($gramma_lesson) {
                //     foreach ($gramma_lesson as $item) {
                //         $params2['id_lesson'] = $lesson->id;
                //         $params2['type'] = $item['type'];
                //         $params2['content'] = $item['grammar_name'];
                //         $params2['image'] = $item['image'] ?? "";
                //         $params2['audio'] = $item['audio'] ?? "";
                //         LessonGrammar::create($params2);
                //     }
                // }
            }
            return redirect()->back()->with('successMessage', __('Cập nhật buổi học thành công!'));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $syllabus_online = Syllabus::find($id)->delete();
        return redirect()->back()->with('successMessage', __('Xóa chương trình online thành công'));
    }


    public function quizLesson(Request $request)
    {
        if (isset($request->lesson_id)) {
            $params = $request->all();
            $lesson = LessonSylabu::find($request->lesson_id);
            if ($lesson) {
                $rows = LessonSylabuQuiz::getSqlLessonSylabuQuiz()->get();
                $this->responseData['lesson'] = $lesson;
                $this->responseData['quizs'] =  $rows;
                $this->responseData['type_quiz'] = Consts::TYPE_QUIZ;
                $this->responseData['style_quiz'] = Consts::STYLE_QUIZ;
                $this->responseData['form_quiz'] = Consts::FORM_QUIZ;
                $this->responseData['quiz_parent'] = LessonSylabuQuiz::where('lesson_id', $lesson->id)->whereNull('parent_id')->get();
                $this->responseData['module_name'] = __('Quản lý câu hỏi của chương trình online');
                return $this->responseView($this->viewPart . '.quiz.index');
            } else return redirect()->back()->with('errorMessage', __('Không tìm thấy thông tin buổi học'));
        } else return redirect()->back()->with('errorMessage', __('Không tìm thấy thông tin buổi học'));
    }

    public function quizStore(Request $request)
    {
        $params = $request->all();
        LessonSylabuQuiz::create($params);
        return redirect()->back()->with('successMessage', __('Thêm mới câu hỏi thành công'));
    }

    public function getLayoutQuestion(Request $request)
    {
        $id = $request->only('id')['id'];
        $quiz = LessonSylabuQuiz::find($id);
        $result['quiz'] = $quiz;
        $result['quiz_parent'] = '';
        $style = $quiz->style;
        $form = $quiz->form;
        $quiz_parent = $quiz;

        if ($quiz->parent_id != null) {
            $quiz_parent = LessonSylabuQuiz::find($quiz->parent_id);
            $style = $quiz_parent->style;
            $form = $quiz_parent->form;
            $result['quiz_parent'] = $quiz_parent;
        }
        $result['view'] = '';
        if (View::exists($this->viewPart . '.quiz.layout_question.' . $style)) {
            $result['view'] = view($this->viewPart . '.quiz.layout_question.' . $style, compact('quiz','quiz_parent', 'form'))->render();
        }

        $result['view_audio'] = '';
        if ($form == 'nghe') {
            $result['view_audio'] = view($this->viewPart . '.quiz.layout_question.' . $form, compact('quiz','quiz_parent', 'form'))->render();
        }
        return $this->sendResponse($result, 'Lấy thông tin thành công');
    }

    // public function getInfoQuestion(Request $request)
    // {
    //     $id = $request->only('id')['id'];
    //     $quiz = LessonSylabuQuiz::find($id);
    //     if (View::exists($this->viewPart . '.quiz.layout_question.' . $quiz->style)) {
    //         $result['view'] = view($this->viewPart . '.quiz.layout_question.' . $quiz->style, compact('quiz'))->render();
    //     } else {
    //         $result['view'] = '';
    //     }
    //     $result['quiz'] = $quiz;
    //     return $this->sendResponse($result, 'Lấy thông tin thành công');
    // }

    public function quizUpdate(Request $request)
    {
        if (isset($request->quiz_id) && $request->quiz_id != "") {
            $quiz = LessonSylabuQuiz::find($request->quiz_id);
            $params = $request->except(['quiz_id']);
            $quiz->fill($params);
            $quiz->save();
            return redirect()->back()->with('successMessage', __('Cập nhật câu hỏi thành công!'));
        }
    }
    public function quizDelete(Request $request)
    {
        $quiz = LessonSylabuQuiz::find($request->id);
        $quiz->delete();
        return redirect()->back()->with('successMessage', __('Xóa câu hỏi thành công'));
    }

    public function formAddQuiz(Request $request)
    {
        try {
            $this->responseData['params'] = $request->all();
            $this->responseData['type_quiz'] = Consts::TYPE_QUIZ;
            return $this->responseView($this->viewPart . '.quiz.create');
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function formEditQuiz(Request $request)
    {
        try {
            $quiz = Quiz::find($request->id);
            if ($quiz) {
                $this->responseData['quiz'] = $quiz;
                $this->responseData['type_quiz'] = Consts::TYPE_QUIZ;
                return $this->responseView($this->viewPart . '.quiz.edit');
            } else return redirect()->back()->with('errorMessage', __('Không tìm thấy thông tin câu hỏi'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
