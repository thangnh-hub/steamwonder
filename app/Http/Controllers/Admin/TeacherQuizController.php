<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Admin;
use App\Models\TeacherQuiz;
use App\Models\TeacherTest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherQuizController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'teacher_quizs';
    $this->viewPart = 'admin.pages.teacher_quizs';
    $this->responseData['module_name'] = __('Quản lý danh sách câu hỏi TEST-GV');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $rows = TeacherQuiz::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
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
      'question' => 'required',
      'json_params' => "required",
    ]);
    $params = $request->all();
    $params['status'] = 'active';
    $quiz = TeacherQuiz::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\TeacherQuiz  $teacherQuiz
   * @return \Illuminate\Http\Response
   */
  public function show(TeacherQuiz $teacherQuiz)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\TeacherQuiz  $teacherQuiz
   * @return \Illuminate\Http\Response
   */
  public function edit(TeacherQuiz $teacherQuiz)
  {
    $this->responseData['detail'] = $teacherQuiz;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\TeacherQuiz  $teacherQuiz
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, TeacherQuiz $teacherQuiz)
  {
    $request->validate([
      'question' => 'required',
      'json_params' => "required",
    ]);
    $params = $request->all();
    $teacherQuiz->fill($params);
    $teacherQuiz->save();
    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\TeacherQuiz  $teacherQuiz
   * @return \Illuminate\Http\Response
   */
  public function destroy(TeacherQuiz $teacherQuiz)
  {
    try {
      $teacherQuiz->delete();

      return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    } catch (Exception $ex) {
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  // Làm bài thi
  public function testTeacher(Request $request)
  {
    if (empty(session('login_email')) || empty(session('login_password'))) {
      return redirect()->route('admin.login')->with(
        'errorMessage',
        __('Wrong credential! Please try again!')
      );
    }
    $email = session('login_email');
    $user = Admin::where(function ($query) use ($email) {
      $query->where('email', $email)->orWhere('admin_code', $email);
    })->first();
    try {
      // lấy ra 40 câu hỏi
      $rows = TeacherQuiz::where('status', 'active')->inRandomOrder()->limit(Consts::DEFAULT_TEACHER_TEST_LIMIT)->get();
      $params['status'] = Consts::STATUS_EXAM_USER['is_exam'];
      $params['user_id'] = $user->id;
      $teacherTest = TeacherTest::getSqlTeacherTest($params)->first();

      if (!$teacherTest) {
        // Lấy ra danh sách câu hỏi và chuyển thành mảng kèm câu trả lời mặc định là null
        $questionsWithAnswers = $rows->pluck('id')->mapWithKeys(fn($id) => [$id => null])->toArray();
        // Lưu vào TeacherTest
        $teacherTest = TeacherTest::create([
          'status' => Consts::STATUS_EXAM_USER['is_exam'],
          'json_params' => ['questions_with_answers' => $questionsWithAnswers],
          'user_id' => $user->id,
          'total_question' => $rows->count(),
          'current_question' => $rows->first()->id,
          'user_name' => $user->name,
        ]);
      }
      // Câu hỏi thứ bao nhiêu
      $questionsWithAnswers = (array) $teacherTest->json_params->questions_with_answers;
      $questionIds = array_keys($questionsWithAnswers);
      $currentQuestionId = $teacherTest->current_question;
      $position = array_search($currentQuestionId, $questionIds);
      $this->responseData['position'] = $position;
      $currentAnswer = $teacherTest->json_params->questions_with_answers->$currentQuestionId ?? null;
      // Số câu đã trả lời(để tính tiến độ hoàn thành)
      $this->responseData['answeredCount'] = count(array_filter((array)$teacherTest->json_params->questions_with_answers, fn($answer) => $answer !== Null));
      $this->responseData['total_questions'] = count($questionIds);

      $this->responseData['row'] = $teacherTest;
      $this->responseData['currentAnswer'] = $currentAnswer;

      return $this->responseView($this->viewPart . '.test_teacher');
    } catch (Exception $ex) {
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  // Viết lại hàm này
  public function resultTestTeacher(Request $request)
  {
    $email = session('login_email');
    $password = session('login_password');

    $teacherTest = TeacherTest::find($request->test_id);
    if (!$teacherTest) {
      return redirect()->back()->with('errorMessage', 'Không tìm thấy bài test hoặc bài test đã hoàn thành không thể sửa.');
    }
    if ($teacherTest->status == Consts::STATUS_EXAM_USER['done']) {
      return redirect()->back()->with('errorMessage', 'Bài test đã hoàn thành không thể nộp lại. Vui lòng làm lại bài test mới dưới đây');
    }
    // Lấy câu trả lời cho câu hỏi hiện tại từ request
    $currentQuestion = $teacherTest->current_question;
    $userAnswer = json_decode($request->selected_answers, true);
    if ($userAnswer == null) {
      return redirect()->back()->with('errorMessage', 'Vui lòng chọn ít nhất 1 đáp án.');
    }

    // Cập nhật câu trả lời vào câu hỏi tương ứng trong json_params
    $jsonParams = $teacherTest->json_params;
    $jsonParams->questions_with_answers->$currentQuestion = $userAnswer;
    $teacherTest->json_params = $jsonParams;
    // Lấy danh sách đáp án đúng của câu hỏi
    $quizData = TeacherQuiz::where('status', 'active')->get();
    $correctAnswers = $quizData->mapWithKeys(function ($quiz) {
      $quizParams = (array) $quiz->json_params;
      $answers = collect((array) ($quizParams['answer'] ?? []))
        ->filter(fn($answer) => isset($answer->boolean) && $answer->boolean == "1")
        ->keys()
        ->toArray();
      return [$quiz->id => $answers];
    });
    // So sánh đáp án của người dùng với đáp án đúng
    $questionsWithAnswers = collect($teacherTest->json_params->questions_with_answers);
    $result = $questionsWithAnswers->map(function ($userAnswers, $questionId) use ($correctAnswers) {
      $correct = $correctAnswers->get($questionId, []);
      // Chuyển đổi thành Collection, sắp xếp và chuẩn hóa để so sánh chính xác
      $userAnswers = collect($userAnswers)->map(fn($answer) => (string) $answer)->sort()->values()->toArray();
      $correct = collect($correct)->map(fn($answer) => (string) $answer)->sort()->values()->toArray();
      return $userAnswers === $correct ? 'correct' : 'wrong'; // Đúng nếu khớp hoàn toàn
    });
    // Đếm số câu đúng và sai
    $correctCount = $result->filter(fn($status) => $status === 'correct')->count();
    $wrongCount = $result->filter(fn($status) => $status === 'wrong')->count();

    // Cập nhật lại dữ liệu 
    $teacherTest->json_params = $jsonParams;
    $teacherTest->status = Consts::STATUS_EXAM_USER['done'];
    $teacherTest->total_true = $correctCount;
    $teacherTest->total_false = $wrongCount;
    $teacherTest->save();

    if ($correctCount == Consts::DEFAULT_TEACHER_TEST_LIMIT) {
      $user = Admin::find($teacherTest->user_id);
      if ($user) {
        $user->update(['status' => Consts::USER_STATUS['active']]);

        // login
        $url = $request->input('url') ?? route('admin.home');
        $attempt = Auth::guard('admin')->attempt([
          'email' => $email,
          'password' => $password,
          'status' => Consts::USER_STATUS['active']
        ]);

        if ($attempt) {
          return redirect($url);
        } else {
          // Bổ sung thêm phần check nếu đăng nhập bằng admin_code
          $attempt_code = Auth::guard('admin')->attempt([
            'admin_code' => $email,
            'password' => $password,
            'status' => Consts::USER_STATUS['active']
          ]);

          if ($attempt_code) {
            return redirect($url);
          }
        }
      }
    } else {
      //list câu trả lời sai
      $arrIDQuestionFalse = $result->filter(fn($status) => $status === 'wrong')->keys()->toArray();
      $arrQuestionFalse = TeacherQuiz::whereIn('id', $arrIDQuestionFalse)->get();
      $this->responseData['module_name'] = __('Kết quả bài thi test.');
      $this->responseData['row'] = $teacherTest;
      $this->responseData['arrQuestionFalse'] = $arrQuestionFalse;
      return $this->responseView($this->viewPart . '.result');
    }
  }

  public function nextQuestion(Request $request)
  {
    $teacherTest = TeacherTest::find($request->test_id);
    if (!$teacherTest) {
      return redirect()->back()->with('errorMessage', 'Không tìm thấy bài kiểm tra.');
    }
    if ($teacherTest->status == Consts::STATUS_EXAM_USER['done']) {
      return redirect()->back()->with('errorMessage', 'Bài test đã hoàn thành không thể thao tác. Vui lòng làm lại bài test mới dưới đây');
    }
    // Lấy câu trả lời cho câu hỏi hiện tại từ request
    $currentQuestion = $teacherTest->current_question;
    $userAnswer = $request->input("answer.{$currentQuestion}");

    if ($userAnswer == null) {
      return redirect()->back()->with('errorMessage', 'Vui lòng chọn ít nhất 1 đáp án.');
    }

    // Cập nhật câu trả lời vào câu hỏi tương ứng trong json_params
    $jsonParams = $teacherTest->json_params;
    $jsonParams->questions_with_answers->$currentQuestion = $userAnswer;

    // Tìm câu hỏi tiếp theo
    $questions = array_keys((array)$jsonParams->questions_with_answers);
    $currentIndex = array_search($currentQuestion, $questions);
    $nextQuestion = $questions[$currentIndex + 1] ?? null;

    // Cập nhật lại json_params và câu hỏi hiện tại
    $teacherTest->json_params = $jsonParams;
    if (!is_null($nextQuestion)) {
      $teacherTest->current_question = $nextQuestion;
    }
    $teacherTest->save();

    if ($teacherTest->save()) return redirect()->route('test_teacher.test');
    else return redirect()->back()->with('errorMessage', __('Failed to update!'));
  }
  public function previousQuestion(Request $request)
  {
    $teacherTest = TeacherTest::find($request->test_id);
    if (!$teacherTest) {
      return redirect()->back()->with('errorMessage', 'Không tìm thấy bài kiểm tra.');
    }
    if ($teacherTest->status == Consts::STATUS_EXAM_USER['done']) {
      return redirect()->back()->with('errorMessage', 'Bài test đã hoàn thành không thể thao tác. Vui lòng làm lại bài test mới dưới đây');
    }
    $currentQuestionId = $teacherTest->current_question;
    $questionsWithAnswers = array_keys((array) $teacherTest->json_params->questions_with_answers);
    $currentIndex = array_search($currentQuestionId, $questionsWithAnswers);

    if ($currentIndex > 0) {
      $previousQuestionId = $questionsWithAnswers[$currentIndex - 1];
      // Cập nhật current_question về câu hỏi trước
      $teacherTest->update([
        'current_question' => $previousQuestionId,
      ]);
    }
    return redirect()->route('test_teacher.test');
  }
}
