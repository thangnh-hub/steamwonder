<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Helpers;
use App\Http\Services\ContentService;
use App\Http\Services\UserService;
use App\Models\Course;
use App\Models\Syllabus;
use App\Models\Menu;
use App\Models\Page;
use App\Models\BlockContent;
use App\Models\LessonSylabu;
use App\Models\LessonUser;
use App\Models\Quiz;
use App\Models\Vocabulary;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

use Exception;
use Faker\Extension\Helper;
use PhpParser\ErrorHandler\Collecting;
use Hashids\Hashids;

class CourseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // lấy thông tin page
        $params['route_name'] = Route::getCurrentRoute()->getName();
        $params['status'] = Consts::STATUS['active'];
        $page = Page::getSqlPage($params)->first();
        // lấy block tương ứng
        $pageControll = new PageController;
        $this->responseData = $pageControll->buildPage($page->json_params);
        $this->responseData['page'] = $page;
        // các thông số thẻ meta
        $this->responseData['meta']['seo_title'] = $page->json_params->title->{$this->responseData['locale']} ?? ($page->title ?? $this->responseData['seo_title']);
        $this->responseData['meta']['seo_keyword'] = $page->json_params->seo_keyword->{$this->responseData['locale']} ?? ($page->seo_keyword ?? $this->responseData['seo_keyword']);
        $this->responseData['meta']['seo_description'] = $page->json_params->seo_description->{$this->responseData['locale']} ?? ($page->seo_description ?? $this->responseData['seo_description']);
        $this->responseData['meta']['seo_image'] = $page->image ?? $this->responseData['seo_image'];

        // lấy thông tin khóa học mới cập nhật
        $params_courses['order_by'] =  'updated_at';
        $params_courses['type'] = 'elearning';
        $rows = Syllabus::getSqlSyllabus($params_courses)->paginate(Consts::PAGINATE['product']);
        $this->responseData['rows'] = $rows;

        if (View::exists('frontend.pages.courses.default')) {
            return $this->responseView('frontend.pages.courses.default');
        } else {
            return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
        }
    }
    public function detail(Request $request)
    {
        $url = $request->url();
        $id = Helpers::getIdFromAlias($url);
        $hashids = new Hashids('', 6);

        // lấy thông tin page
        $params['route_name'] = Route::getCurrentRoute()->getName();
        $params['status'] = Consts::STATUS['active'];
        $page = Page::getSqlPage($params)->first();

        // lấy block tương ứng
        $pageControll = new PageController;
        $this->responseData = $pageControll->buildPage($page->json_params);
        $this->responseData['page'] = $page;

        // Lấy thông tin khóa học
        $params_courses['type'] =  Consts::SYLLABUS_TYPE['elearning'];
        $params_courses['id'] =  $id;
        $detail = Syllabus::getSqlSyllabus($params_courses)->first();
        $this->responseData['detail'] = $detail;
        $this->responseData['hashids'] = $hashids;
        // Kiểm tra user đã đăng ký khóc học này chưa
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $params['customer_id'] = $user->id;
            $params['syllabus_id'] = $id;
            $params['is_type'] = Consts::ORDER_TYPE['courses'];
            $params['status'] = true;
            $rows = Order::getOrderCourses($params)->first();
            $this->responseData['order'] =  $rows;
            $lesson_user = LessonUser::where('user_id', $user->id)->get();
            $this->responseData['lesson_user'] =  $lesson_user;
        }
        $helpers = new Helpers;
        $this->responseData['helpers'] =  $helpers;


        // các thông số thẻ meta
        $this->responseData['meta']['seo_title'] = $detail->json_params->name->{$this->responseData['locale']} ?? ($detail->name ?? $this->responseData['seo_title']);
        $this->responseData['meta']['seo_keyword'] = $this->responseData['seo_keyword'] ?? '';
        $this->responseData['meta']['seo_description'] = $this->responseData['seo_description'] ?? '';
        $this->responseData['meta']['seo_image'] = isset($detail->json_params->image) && $detail->json_params->image != '' ? $detail->json_params->image : $this->responseData['seo_image'];
        if (View::exists('frontend.pages.courses.detail')) {
            return $this->responseView('frontend.pages.courses.detail');
        } else {
            return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
        }
    }
    public function lesson(Request $request, $alias)
    {
        $id_syllabus = Helpers::getIdFromAlias($alias);
        $hashids = new Hashids('', 6);
        $tab = $request->tab ?? '';
        $id_lesson = $hashids->decode($request->lesson)[0];
        $user = Auth::guard('web')->user();
        if ($id_syllabus != null && $id_lesson != null) {
            // Check buổi đàu tiên
            if (UserService::CheckLessonFirst($id_lesson) == false) {
                // check đăng ký hay chưa
                $list_syllabus = UserService::getPermisisonSyllabus($user->id, $id_lesson);
                if (!in_array($id_syllabus, $list_syllabus)) {
                    return redirect()->back()->with('warningMessage', __('Bạn chưa đăng ký khóa học này !'));
                }
            }
            $check_lesson = UserService::getPermisisonLesson($id_lesson);
            if ($check_lesson == false) {
                return redirect()->back()->with('warningMessage', __('Bạn chưa đạt yêu cầu học bài này !'));
            }

            $params['route_name'] = Route::getCurrentRoute()->getName();
            $params['status'] = Consts::STATUS['active'];
            $page = Page::getSqlPage($params)->first();
            // lấy block tương ứng
            $pageControll = new PageController;
            $this->responseData = $pageControll->buildPage($page->json_params);
            $this->responseData['page'] = $page;

            // Lấy thông tin khóa học
            $params_syllabus['type'] =  Consts::SYLLABUS_TYPE['elearning'];
            $params_syllabus['id'] =  $id_syllabus;
            $syllabus = Syllabus::getSqlSyllabus($params_syllabus)->first();
            $this->responseData['syllabus'] = $syllabus;

            //check buổi theo khóa học
            $check = LessonSylabu::where('id', $id_lesson)->where('syllabus_id', $syllabus->id)->count();
            if ($check <= 0) {
                return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
            }

            // Lấy tất cả thông tin các buổi học theo chương trình
            $list_lesson = LessonSylabu::where('syllabus_id', $id_syllabus)->orderBy('id', 'ASC')->get();
            $this->responseData['list_lesson'] = $list_lesson;

            // Lấy thông tin buổi học hiện tại
            $lesson = $list_lesson->find($id_lesson);
            $this->responseData['lesson'] = $lesson;
            $this->responseData['list_quizs'] = $lesson->quizs;

            // Lấy từ vựng tương ứng
            if (isset($lesson->json_params->vocabulary) && $lesson->json_params->vocabulary != '') {
                $params_vocabulary['arr_keyword'] = $lesson->json_params->vocabulary ?? '';
                $vocabulary = Vocabulary::getSqlVocabulary($params_vocabulary)->get();
            }
            $this->responseData['vocabulary'] = $vocabulary ?? [];

            // Lấy buổi học trước và sau
            $previous_next_lesson =  UserService::getPreviousNextLesson($list_lesson, $id_lesson, $tab);
            $this->responseData['previous_lesson'] = $previous_next_lesson['previous_lesson'];
            $this->responseData['previous_tab'] = $previous_next_lesson['previous_tab'];
            $this->responseData['next_lesson'] = $previous_next_lesson['next_lesson'];
            $this->responseData['next_tab'] = $previous_next_lesson['next_tab'];

            // lấy tất các buổi học mà user đã hoàn thành
            $list_lesson_user = LessonUser::where('user_id', $user->id)->where('percent_point', '>=', Consts::PERCENT_PASS)->get();
            $arr_id_lesson = [];
            if ($list_lesson_user) {
                foreach ($list_lesson_user as $val) {
                    $arr_id_lesson[] = $val->lesson_id;
                }
            }
            // lọc lấy số buổi học đã hoàn thiện 100% của chương trình hiện tại
            $user_lesson_active = $list_lesson->filter(function ($item, $key) use ($arr_id_lesson) {
                return in_array($item->id, $arr_id_lesson);
            });
            $this->responseData['user_lesson_active'] = $user_lesson_active;
            $this->responseData['hashids'] = $hashids;
            $this->responseData['tab'] = $tab;

            // cập nhật tab action buổi học của user trong json_params
            $lesson_user = UserService::updateTabAction($lesson->id, $tab);

            $helpers = new Helpers;
            $this->responseData['helpers'] = $helpers;
            $this->responseData['percent_pass'] = Consts::PERCENT_PASS;
            $this->responseData['tab_lesson'] = Consts::TAB_LESSON;

            // các thông số thẻ meta
            $this->responseData['meta']['seo_title'] = $lesson->json_params->title->{$this->responseData['locale']} ?? ($lesson->title ?? $this->responseData['seo_title']);
            $this->responseData['meta']['seo_keyword'] = $this->responseData['seo_keyword'] ?? '';
            $this->responseData['meta']['seo_description'] = $this->responseData['seo_description'] ?? '';
            $this->responseData['meta']['seo_image'] = $this->responseData['seo_image'] ?? '';

            if (View::exists('frontend.pages.courses.lesson')) {
                return $this->responseView('frontend.pages.courses.lesson');
            } else {
                return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
            }
        } else {
            return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
        }
    }

    public function activeLessonUser(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Auth::guard('web')->check() == false) {
                return redirect()->route('home')->with('warningMessage', __('Yêu cầu đăng nhập!'));
            }
            $user = Auth::guard('web')->user();
            $lesson_id = $request->only('lesson')['lesson'] ?? '';
            $courses_id = $request->only('courses')['courses'] ?? '';
            $lits_quiz = $request->only('quiz')['quiz'] ?? '';
            $check_courses = UserService::getPermisisonSyllabus(Auth::guard('web')->user()->id);
            if (!in_array($courses_id, $check_courses)) {
                return redirect()->back()->with('errorMessage', 'Bạn chưa đăng ký khóa học này !');
            }
            // thông tin các câu hỏi của buổi học
            $quiz = Quiz::where('id_lesson', $lesson_id)->get();
            // % tiến độ
            $percent_point = 0;
            $count_percent_point = 0;
            if ($lits_quiz != '') {
                foreach ($quiz as $val_quiz) {
                    // lấy đáp án tương ứng vs id quiz
                    $arr_answer = collect($lits_quiz)->first(function ($item, $key) use ($val_quiz) {
                        return $key == $val_quiz->id;
                    });

                    if ($arr_answer) {
                        switch ($val_quiz->type) {
                            case 'choice':
                                $answer = implode(' ', $arr_answer);
                                $answer_correct = collect($val_quiz->json_params->answer)->first(function ($item, $key) {
                                    return isset($item->boolean) && $item->boolean == '1';
                                })->value;
                                if ($answer == $answer_correct) {
                                    $percent_point += 1 / count($lits_quiz) * 100;
                                    $count_percent_point++;
                                }
                                break;
                            case 'connect':
                                $arr_quiz_answer = get_object_vars($val_quiz->json_params->answer);
                                $check_left = array_diff($arr_quiz_answer['left'], $arr_answer['left']);
                                $check_right = array_diff($arr_quiz_answer['right'], $arr_answer['right']);
                                if (empty($check_left) && empty($check_right)) {
                                    $percent_point += 1 / count($lits_quiz) * 100;
                                    $count_percent_point++;
                                }
                                break;

                            default:
                                $answer = implode(' ', $arr_answer);
                                $answer_correct = implode(' ', $val_quiz->json_params->answer);
                                if (trim(strtolower($answer)) == trim(strtolower($answer_correct))) {
                                    $percent_point += 1 / count($lits_quiz) * 100;
                                    $count_percent_point++;
                                }
                                break;
                        }
                    }
                }
                // nếu % mới cao hơn % cũ thì cập nhật
                $lesson_user = LessonUser::where('lesson_id', $lesson_id)->where('user_id', $user->id)->first();
                if ($lesson_user) {
                    if ($percent_point > $lesson_user->percent_point) {
                        $lesson_user->percent_point = $percent_point;
                        $lesson_user->save();
                    }
                } else {
                    $params['lesson_id'] = $lesson_id;
                    $params['user_id'] = $user->id;
                    $params['percent_point'] = $percent_point;
                    LessonUser::create($params);
                }
            }
            $title = 'Tổng số câu trả lời đúng:' . $count_percent_point . '/' . count($quiz);
            if (round($percent_point) >= Consts::PERCENT_PASS) {
                $data = 'successMessage';
                $messageResult = $title . ' ' . __('Chúc mừng bạn đã hoàn thành suất sắc buổi học này !');
            } else {
                $data = 'warningMessage';
                $messageResult =  $title . ' ' . __('Rất tiếc bạn cần cố gắng hơn nữa !');
            }
            DB::commit();
            // return $this->sendResponse($data, $messageResult);
            return redirect()->back()->with($data, $messageResult);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Lỗi kết nối!');
            // abort(422, __($ex->getMessage()));
        }
    }

    // chỉ check loại là nối
    public function checkAnswerQuiz(Request $request)
    {
        $check = false;
        $id_quiz = $request->input('id_quiz');
        $data_left = $request->input('data_left');
        $data_right = $request->input('data_right');

        if ($id_quiz != '') {
            $quiz = Quiz::find($id_quiz);
            $answer_left = $quiz->json_params->answer->left ?? [];
            $answer_right = $quiz->json_params->answer->right ?? [];
            foreach ($answer_left as $key => $val) {
                if ($data_left ==  $val && $answer_right[$key] == $data_right) {
                    $check = true;
                }
            }
        }
        return $check;
    }

    public function checkQuiz(Request $request)
    {
        $params = $request->all();
        $lesson_id = $params['lesson_id'] ?? '';
        $quiz_id = $params['quiz_id'] ?? '';
        // $answer = $params['answer'] ?? [];
        $answer = $request->only('quiz')['quiz'] ?? '';
        $count_percent_point = $params['count_percent_point'] ?? 0;
        // dd($answer);
        // % tiến độ
        $percent_point = 0;
        // Lấy các câu hỏi của bài học
        $list_quiz = Quiz::where('id_lesson', $lesson_id)->orderBy('id', 'ASC')->get();
        // Lấy câu hỏi và đáp án của câu hiện tại
        $quiz_default = $list_quiz->find($quiz_id);
        $result = false;

        // check câu tl so với đáp án
        if ($list_quiz != '' && $answer != '') {
            // lấy đáp án tương ứng vs id quiz
            // $arr_answer = collect($list_quiz)->first(function ($item, $key) use ($quiz_default) {
            //     return $item->id == $quiz_default->id;
            // });

            // if ($arr_answer) {
            switch ($quiz_default->type) {
                case 'choice':
                    $answer = implode(' ', $answer);

                    $answer_correct = collect($quiz_default->json_params->answer)->first(function ($item, $key) {
                        return isset($item->boolean) && $item->boolean == '1';
                    })->value;
                    if ($answer == $answer_correct) {
                        $percent_point += 1 / count($list_quiz) * 100;
                        $count_percent_point++;
                        $result = true;
                    }
                    break;
                case 'connect':
                    $arr_quiz_answer = get_object_vars($quiz_default->json_params->answer);
                    $check_left = array_diff($arr_quiz_answer['left'], $answer['left']);
                    $check_right = array_diff($arr_quiz_answer['right'], $answer['right']);
                    if (empty($check_left) && empty($check_right)) {
                        $percent_point += 1 / count($list_quiz) * 100;
                        $count_percent_point++;
                        $result = true;
                    }
                    break;

                default:
                    $answer = implode(' ', $answer);
                    $answer_correct = implode(' ', $quiz_default->json_params->answer);
                    if (trim(strtolower($answer)) == trim(strtolower($answer_correct))) {
                        $percent_point += 1 / count($list_quiz) * 100;
                        $count_percent_point++;
                        $result = true;
                    }
                    break;
            }
            // }
        }

        $next_quiz =  UserService::getPreviousNextLesson($list_quiz, $quiz_default->id, 'learning');
        $data['result'] = $result;
        $data['next_quiz_id'] = $next_quiz['next_lesson']->id ?? '';
        return $this->sendResponse($data, $count_percent_point);
    }
    public function getViewNextQuiz(Request $request)
    {
        $params = $request->only('id');
        $quiz_default = Quiz::find($params['id']);
        $list_quiz = Quiz::where('id_lesson', $quiz_default->id_lesson)->orderBy('id', 'ASC')->get();
        // Lấy câu hỏi sau
        $previous_next_quiz =  UserService::getPreviousNextLesson($list_quiz, $quiz_default->id, 'learning');
        $this->responseData['items_quizs'] = $previous_next_quiz['default'] ?? '';
        $quiz_type = $previous_next_quiz['default']->type??'';
        if (View::exists('frontend.components.quiz.'.$quiz_type)) {
            return $this->responseView('frontend.components.quiz.'.$quiz_type);
        } else {
            return false;
        }
    }

    public function getVocabulary(Request $request)
    {
        $params = $request->only('keyword');
        $vocabulary = Vocabulary::getSqlVocabulary($params)->first();
        $this->responseData['detail'] = $vocabulary;
        return $this->responseView('frontend.pages.courses.vocabulary');
    }
    public function updatePoint(Request $request)
    {
        DB::beginTransaction();
        try {
            $params['point'] = $request->point;
            $params['lesson'] = $request->id;
            $user = Auth::guard('web')->user();
            // Lấy thông tin buổi học
            $lesson = LessonSylabu::find($params['lesson']);
            // Số câu hỏi
            $percent_point = $params['point'] / count($lesson->quizs) * 100;
            // nếu % mới cao hơn % cũ thì cập nhật
            $lesson_user = LessonUser::where('lesson_id', $params['lesson'])->where('user_id', $user->id)->first();
            if ($lesson_user) {
                if ($percent_point > $lesson_user->percent_point) {
                    $lesson_user->percent_point = $percent_point;
                    $lesson_user->save();
                }
            }
            DB::commit();
            return $this->sendResponse('success', 'Cập nhật thành công. Bạn được ' . $percent_point . ' điểm');
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendResponse('error', 'Lỗi kết nối!');
            // abort(422, __($ex->getMessage()));
        }
    }
}
