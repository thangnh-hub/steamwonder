<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;

use App\Consts;
use App\Models\Admin;
use App\Models\Topic;
use App\Models\StudentTest;
use App\Models\ExamSessionUser;
use App\Models\ExamSession;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class TestIQController extends Controller
{
    public function testIqStudentIndex()
    {
        if (session()->has('exam_session_iq')) {
            return redirect()->route('test_iq.student.question');
        }
        // $point = 0;
        // $total = 0;
        // $exam_session_users = ExamSessionUser::find('2467');
        // $list_answer = $exam_session_users->json_params->list_answer;
        // if ($list_answer != '') {
        //     foreach ($list_answer as $key => $val) {
        //         //lấy đáp án theo đúng theo key tương ứng
        //         $params['id'] = $key;
        //         $answer_questions = StudentTest::getSqlStudentTest($params)->first();
        //         switch ($answer_questions->topic->type) {
        //             case 'logic':
        //                 $arr_answer = str_split($val);
        //                 $arr_answer_questions = str_split($answer_questions->json_params->answer);
        //                 $total += $answer_questions->point;
        //                 foreach ($arr_answer_questions as $_k => $_v) {
        //                     if (isset($arr_answer[$_k]) && trim($_v) == trim($arr_answer[$_k])) {
        //                         $point += 1 / count($arr_answer_questions) * $answer_questions->point;
        //                     }
        //                 }
        //                 break;
        //             case 'math':
        //                 $total += $answer_questions->point;
        //                 if (trim($answer_questions->json_params->answer) == trim($val)) {
        //                     $point += $answer_questions->point;
        //                 }
        //                 break;
        //             case 'eye_training':
        //                 $total += $answer_questions->point;
        //                 if (trim($answer_questions->json_params->answer) == trim($val)) {
        //                     $point += $answer_questions->point;
        //                 }
        //                 break;
        //             case 'text':
        //                 $total += $answer_questions->point;
        //                 foreach ($answer_questions->json_params->answer as $_key => $_v) {
        //                     if (trim($_key) == trim($val) && isset($_v->boolean) && $_v->boolean == 1) {
        //                         $point += $answer_questions->point;
        //                     }
        //                 }
        //                 break;
        //             case 'order_table':
        //                 $total += $answer_questions->point;
        //                 foreach ($val as $_key => $_v) {
        //                     if (in_array($_v, $answer_questions->json_params->answer)) {
        //                         $point += 1 / count($answer_questions->json_params->answer) * $answer_questions->point;
        //                     }
        //                 }
        //                 break;
        //             case 'connect':
        //                 $total += $answer_questions->point;
        //                 foreach ($answer_questions->json_params->answer->right as $_key => $_v) {
        //                     if (Str::lower(trim($_v)) == Str::lower(trim($val->right[$_key]))) {
        //                         $point += 1 / count($answer_questions->json_params->answer->left) * $answer_questions->point;
        //                     }
        //                 }
        //                 break;
        //             case 'listen':
        //                 $total += $answer_questions->point;
        //                 $check_point = 0;
        //                 foreach ($answer_questions->json_params->answer as $_key => $_v) {
        //                     if (Str::lower(trim($_v)) == Str::lower(trim($val[$_key]))) {
        //                         $check_point++;
        //                     }
        //                 }
        //                 $percent_check_point = $check_point / count($answer_questions->json_params->answer) * 100;
        //                 if (floor($percent_check_point) == 100) {
        //                     $point += $answer_questions->point;
        //                 } elseif (floor($percent_check_point) < 100 && floor($percent_check_point) >= 50) {
        //                     $point += ceil($answer_questions->point / 2);
        //                 }
        //                 break;
        //             case 'fill_words':
        //                 $total += $answer_questions->point;
        //                 $check_point = 0;
        //                 foreach ($answer_questions->json_params->answer as  $item) {
        //                     foreach (explode(' ', $item)  as $_key => $_v) {
        //                         if (Str::lower(trim($_v)) == Str::lower(trim($val[$_key]))) {
        //                             $check_point++;
        //                         }
        //                     }
        //                 }
        //                 $percent_check_point = $check_point / count($val) * 100;
        //                 if (floor($percent_check_point) == 100) {
        //                     $point += $answer_questions->point;
        //                 } elseif (floor($percent_check_point) < 100 && floor($percent_check_point) >= 50) {
        //                     $point += ceil($answer_questions->point / 2);
        //                 }
        //                 break;
        //             default:
        //                 break;
        //         };
        //     };
        //     dd($point);
        //     // $exam_session_users =  Session::get('exam_session_users');
        //     // $json['list_answer'] = $list_answer;
        //     // $student_new = ExamSessionUser::where('id', $exam_session_users->id)
        //     //     ->update([
        //     //         "status" => Consts::STATUS_EXAM_USER['done'],
        //     //         "score" => $point,
        //     //         "json_params" => $json,
        //     //     ]);
        //     // $student = Admin::find($exam_session_users->user_id);
        // }

        return $this->responseView('frontend.pages.test_iq');
    }
    public function testIqStudentPost(Request $request)
    {
        $params['cccd'] = $request->cccd ?? '';
        // lấy thông tin học viên theo cccd
        $student = Admin::where('admin_type', Consts::ADMIN_TYPE['student'])->whereJsonContains('admins.json_params->cccd', $params['cccd'])->first();
        if ($student) {
            // check ngày giờ để vào thi
            $currentDateTime = Carbon::now();
            $currentDateTime->addMinutes(10);
            $currentDate = $currentDateTime->format('Y-m-d');
            $currentTime = $currentDateTime->format('H:i:s');
            // lấy thông tin buổi thi
            $params_ExamSessionUser['user_id'] = $student->id;
            $params_ExamSessionUser['type'] = Consts::TYPE_EXAM_SESSION['test_iq'];
            $params_ExamSessionUser['day_exam'] = $currentDate;
            $exam_session_users = ExamSessionUser::getSqlExamSessionUser($params_ExamSessionUser)->first();

            if ($exam_session_users) {
                $params_ExamSession['id'] = $exam_session_users->exam_id;
                $exam_session = ExamSession::getSqlExamSession($params_ExamSession)->first();

                if ($currentDate != $exam_session->day_exam || $currentTime < $exam_session->time_exam_start) {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Chưa đến giờ vào làm bài thi'
                    );
                }
                if ($currentTime > $exam_session->time_exam_end) {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Ca thi đã kết thúc'
                    );
                }
                if ($exam_session_users->status == Consts::STATUS_EXAM_USER['new']) {
                    $exam_session_users->status = 'is_exam';
                    $exam_session_users->save();
                    Session::put([
                        'student' =>  $student,
                        'exam_session_iq' => $exam_session,
                        'exam_session_users' => $exam_session_users,
                        'currentDate' => $currentDate,
                        'currentTime' => $currentTime,
                    ]);
                    return redirect()->route('test_iq.student.question');
                } else {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Học viên đã đăng nhập làm bài thi!'
                    );
                }
            } else {
                return redirect()->back()->with(
                    'errorMessage',
                    'Học viên chưa có lịch test của ngày hôm nay'
                );
            }
        } else {
            return redirect()->back()->with(
                'errorMessage',
                'CCCD không tồn tại trong hệ thống!'
            );
        }
    }
    public function testIqStudentQuestion()
    {
        $exam_session =  Session::get('exam_session_iq');
        if (!isset($exam_session)) {
            return redirect()->route('test_iq.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
        }
        $student = Session::get('student');
        $exam_session = Session::get('exam_session_iq');
        $currentTime = Session::get('currentTime');
        $currentDate = Session::get('currentDate');
        if ($student) {
            $params_topic['list_topic'] = json_decode($exam_session->list_topic, true);
            $topic = Topic::getSqlTopic($params_topic)->get();
            $questions = StudentTest::getSqlStudentTest($params_topic)->get();
            $this->responseData['TYPE_STUDENT_TEST'] = Consts::TYPE_STUDENT_TEST;
            $this->responseData['questions'] = $questions;
            $this->responseData['topic'] = $topic;
            $this->responseData['student'] = $student;
            $this->responseData['exam_session'] = $exam_session;
            $this->responseData['currentDate'] = $currentDate;
            $this->responseData['currentTime'] = $currentTime;
            return $this->responseView('frontend.pages.test_iq_question');
        } else {
            return redirect()->route('test_iq.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
        }
    }
    public function testIqStudentAnswer(Request $request)
    {
        DB::beginTransaction();
        try {
            $point = 0;
            $total = 0;
            $list_answer = $request->only('answer')['answer'] ?? [];
            $exam_session =  Session::get('exam_session_iq') ?? '';
            // if (!isset($exam_session)) {
            //     return redirect()->route('test_iq.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
            // }

            if ($list_answer != '' && $exam_session != '') {
                foreach ($list_answer as $key => $val) {

                    //lấy đáp án theo đúng theo key tương ứng
                    $params['id'] = $key;
                    $answer_questions = StudentTest::getSqlStudentTest($params)->first();
                    switch ($answer_questions->topic->type) {
                        case 'logic':
                            $arr_answer = str_split($val);
                            $arr_answer_questions = str_split($answer_questions->json_params->answer);
                            $total += $answer_questions->point;
                            foreach ($arr_answer_questions as $_k => $_v) {
                                if (isset($arr_answer[$_k]) && Str::lower(trim($_v)) == Str::lower(trim($arr_answer[$_k]))) {
                                    $point += 1 / count($arr_answer_questions) * $answer_questions->point;
                                }
                            }
                            break;
                        case 'math':
                            $total += $answer_questions->point;
                            if (trim($answer_questions->json_params->answer) == trim($val)) {
                                $point += $answer_questions->point;
                            }
                            break;
                        case 'eye_training':
                            $total += $answer_questions->point;
                            if (Str::lower(trim($answer_questions->json_params->answer)) == Str::lower(trim($val))) {
                                $point += $answer_questions->point;
                            }
                            break;
                        case 'text':
                            $total += $answer_questions->point;
                            foreach ($answer_questions->json_params->answer as $_key => $_v) {
                                if (trim($_key) == trim($val) && isset($_v->boolean) && $_v->boolean == 1) {
                                    $point += $answer_questions->point;
                                }
                            }
                            break;
                        default:
                            break;
                    };
                };

                $exam_session_users =  Session::get('exam_session_users');
                $json['list_answer'] = $list_answer;
                $student_new = ExamSessionUser::where('id', $exam_session_users->id)
                    ->update([
                        "status" => Consts::STATUS_EXAM_USER['done'],
                        "score" => $point,
                        "json_params" => $json,
                    ]);
                $student = Admin::find($exam_session_users->user_id);
            };

            Session::forget('student');
            Session::forget('exam_session_iq');
            Session::forget('exam_session_users');
            Session::forget('currentDate');
            Session::forget('currentTime');

            DB::commit();
            $this->responseData['point'] = $point;
            $this->responseData['total'] = $total;
            $this->responseData['student'] = $student ?? '';
            return $this->responseView('frontend.pages.test_result');
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }



    public function testAcceptanceStudentIndex()
    {
        if (session()->has('exam_session_acc')) {
            return redirect()->route('test_acceptance.student.question');
        }
        return $this->responseView('frontend.pages.test_acceptance');
    }
    public function testAcceptanceStudentPost(Request $request)
    {
        $params['cccd'] = $request->cccd ?? '';
        // lấy thông tin học viên theo cccd
        $student = Admin::where('admin_type', Consts::ADMIN_TYPE['student'])->whereJsonContains('admins.json_params->cccd', $params['cccd'])->first();
        if ($student) {
            // check ngày giờ hiện tại
            $currentDateTime = Carbon::now();
            $currentDateTime->addMinutes(10);
            $currentDate = $currentDateTime->format('Y-m-d');
            $currentTime = $currentDateTime->format('H:i:s');

            // lấy thông tin buổi thi
            $params_ExamSessionUser['user_id'] = $student->id;
            $params_ExamSessionUser['type'] = Consts::TYPE_EXAM_SESSION['test_acceptance'];
            $params_ExamSessionUser['day_exam'] = $currentDate;
            $exam_session_users = ExamSessionUser::getSqlExamSessionUser($params_ExamSessionUser)->first();

            if ($exam_session_users) {
                $params_ExamSession['id'] = $exam_session_users->exam_id;
                $exam_session = ExamSession::getSqlExamSession($params_ExamSession)->first();
                if ($currentTime < $exam_session->time_exam_start) {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Chưa đến giờ vào làm bài thi'
                    );
                }
                if ($currentTime > $exam_session->time_exam_end) {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Ca thi đã kết thúc'
                    );
                }
                if ($exam_session_users->status == Consts::STATUS_EXAM_USER['new']) {
                    $exam_session_users->status = 'is_exam';
                    $exam_session_users->save();
                    Session::put([
                        'student' =>  $student,
                        'exam_session_acc' => $exam_session,
                        'exam_session_users' => $exam_session_users,
                        'currentDate' => $currentDate,
                        'currentTime' => $currentTime,
                    ]);
                    return redirect()->route('test_acceptance.student.question');
                } else {
                    return redirect()->back()->with(
                        'errorMessage',
                        'Học viên đã đăng nhập làm bài thi!'
                    );
                }
            } else {
                return redirect()->back()->with(
                    'errorMessage',
                    'Học viên chưa có lịch test của ngày hôm nay'
                );
            }
        } else {
            return redirect()->back()->with(
                'errorMessage',
                'CCCD không tồn tại trong hệ thống!'
            );
        }
    }
    public function testAcceptanceStudentQuestion()
    {
        $exam_session =  Session::get('exam_session_acc');
        if (!isset($exam_session)) {
            return redirect()->route('test_acceptance.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
        }
        $student = Session::get('student');
        $exam_session = Session::get('exam_session_acc');
        $currentTime = Session::get('currentTime');
        $currentDate = Session::get('currentDate');
        if ($student) {
            $params_topic['list_topic'] = json_decode($exam_session->list_topic, true);
            $topic = Topic::getSqlTopic($params_topic)->get();
            $questions = StudentTest::getSqlStudentTest($params_topic)->get();
            $this->responseData['TYPE_STUDENT_TEST'] = Consts::TYPE_STUDENT_TEST;
            $this->responseData['questions'] = $questions;
            $this->responseData['topic'] = $topic;
            $this->responseData['student'] = $student;
            $this->responseData['exam_session'] = $exam_session;
            $this->responseData['currentDate'] = $currentDate;
            $this->responseData['currentTime'] = $currentTime;
            return $this->responseView('frontend.pages.test_acceptance_question');
        } else {
            return redirect()->route('test_acceptance.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
        }
    }
    public function testAcceptanceStudentAnswer(Request $request)
    {
        DB::beginTransaction();
        try {
            $point = 0;
            $total = 0;
            $list_answer = $request->only('answer')['answer'] ?? [];
            $exam_session =  Session::get('exam_session_acc') ?? '';
            // if (!isset($exam_session)) {
            //     return redirect()->route('test_acceptance.student.index')->with('errorMessage', __('Cần xác thực CCCD!'));
            // }
            if ($list_answer != '' && $exam_session != '') {
                foreach ($list_answer as $key => $val) {
                    //lấy đáp án theo đúng theo key tương ứng
                    $params['id'] = $key;
                    $answer_questions = StudentTest::getSqlStudentTest($params)->first();
                    switch ($answer_questions->topic->type) {
                        case 'logic':
                            $arr_answer = str_split($val);
                            $arr_answer_questions = str_split($answer_questions->json_params->answer);
                            $total += $answer_questions->point;
                            foreach ($arr_answer_questions as $_k => $_v) {
                                if (isset($arr_answer[$_k]) && trim($_v) == trim($arr_answer[$_k])) {
                                    $point += 1 / count($arr_answer_questions) * $answer_questions->point;
                                }
                            }
                            break;
                        case 'math':
                            $total += $answer_questions->point;
                            if (trim($answer_questions->json_params->answer) == trim($val)) {
                                $point += $answer_questions->point;
                            }
                            break;
                        case 'eye_training':
                            $total += $answer_questions->point;
                            if (trim($answer_questions->json_params->answer) == trim($val)) {
                                $point += $answer_questions->point;
                            }
                            break;
                        case 'text':
                            $total += $answer_questions->point;
                            foreach ($answer_questions->json_params->answer as $_key => $_v) {
                                if (trim($_key) == trim($val) && isset($_v->boolean) && $_v->boolean == 1) {
                                    $point += $answer_questions->point;
                                }
                            }
                            break;
                        case 'order_table':
                            $total += $answer_questions->point;
                            foreach ($val as $_key => $_v) {
                                if (in_array($_v, $answer_questions->json_params->answer)) {
                                    $point += 1 / count($answer_questions->json_params->answer) * $answer_questions->point;
                                }
                            }
                            break;
                        case 'connect':
                            $total += $answer_questions->point;
                            foreach ($answer_questions->json_params->answer->right as $_key => $_v) {
                                if (Str::lower(trim($_v)) == Str::lower(trim($val['right'][$_key]))) {
                                    $point += 1 / count($answer_questions->json_params->answer->left) * $answer_questions->point;
                                }
                            }
                            break;
                        case 'listen':
                            $total += $answer_questions->point;
                            $check_point = 0;
                            foreach ($answer_questions->json_params->answer as $_key => $_v) {
                                if (Str::lower(trim($_v)) == Str::lower(trim($val[$_key]))) {
                                    $check_point++;
                                }
                            }
                            $percent_check_point = $check_point / count($answer_questions->json_params->answer) * 100;
                            if (floor($percent_check_point) == 100) {
                                $point += $answer_questions->point;
                            } elseif (floor($percent_check_point) < 100 && floor($percent_check_point) >= 50) {
                                $point += ceil($answer_questions->point / 2);
                            }
                            break;
                        case 'fill_words':
                            $total += $answer_questions->point;
                            $check_point = 0;
                            foreach ($answer_questions->json_params->answer as  $item) {
                                foreach (explode(' ', $item)  as $_key => $_v) {
                                    if (Str::lower(trim($_v)) == Str::lower(trim($val[$_key]))) {
                                        $check_point++;
                                    }
                                }
                            }
                            $percent_check_point = $check_point / count($val) * 100;
                            if (floor($percent_check_point) == 100) {
                                $point += $answer_questions->point;
                            } elseif (floor($percent_check_point) < 100 && floor($percent_check_point) >= 50) {
                                $point += ceil($answer_questions->point / 2);
                            }
                            break;
                        default:
                            break;
                    };
                };
                $exam_session_users =  Session::get('exam_session_users');
                $json['list_answer'] = $list_answer;
                $student_new = ExamSessionUser::where('id', $exam_session_users->id)
                    ->update([
                        "status" => Consts::STATUS_EXAM_USER['done'],
                        "score" => $point,
                        "json_params" => $json,
                    ]);
                $student = Admin::find($exam_session_users->user_id);
            }



            Session::forget('student');
            Session::forget('exam_session_acc');
            Session::forget('exam_session_users');
            Session::forget('currentDate');
            Session::forget('currentTime');

            DB::commit();
            $this->responseData['point'] = $point;
            $this->responseData['total'] = $total;
            $this->responseData['student'] = $student ?? '';
            return $this->responseView('frontend.pages.test_result_acceptance');
            // return view('frontend.pages.test_result_acceptance', compact('point', 'total'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
}
