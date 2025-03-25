<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\QuestionAnswer;
use App\Models\Admin;
use App\Models\Student;
use App\Models\HistoryTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use stdClass;

class QuestionAnswerController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'question_answers';
        $this->viewPart = 'admin.pages.question_answers';
        $this->responseData['module_name'] = 'Question answers Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = QuestionAnswer::getsqlQuestionAnswer($params)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;

        return $this->responseView($this->viewPart . '.index');
    }
    public function TestStaff(Request $request)
    {
        if (empty(session('login_email')) || empty(session('login_password'))) {
            return redirect()->route('admin.login')->with(
                'errorMessage',
                __('Wrong credential! Please try again!')
            );
        }
        $params = $request->all();
        $email = $request->input('email');
        $password = $request->input('password');
        $params['status'] = Consts::STATUS['active'];
        // Get list post with filter params
        $rows = QuestionAnswer::getsqlQuestionAnswer($params)->inRandomOrder()->limit(30)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['email'] = $email;
        $this->responseData['password'] = $password;

        return $this->responseView('admin.pages.test_staff');
    }
    public function CheckActiveStaff(Request $request)
    {
        $params = $request->all();
        $questionAnswer = QuestionAnswer::get();
        $allBooleansEqualOne = true;
        $count_true_sentence = 0;
        $count_false_sentence = 0;
        $arrQuestionFalse = [];
        $stt = 0;
        foreach ($questionAnswer as $question) {
            if (isset($params['answer'][$question->id])) {
                $stt++;
                foreach ($question->json_params->answer as $key => $answer) {
                    if ($answer->key == $params['answer'][$question->id]['key']) {
                        if (isset($answer->boolean) && $answer->boolean == 1) {
                            $count_true_sentence = $count_true_sentence + 1;
                        } else {
                            $count_false_sentence = $count_false_sentence + 1;
                            // $allBooleansEqualOne = false;
                            $arrQuestionFalse[$stt] = $question;
                        }
                    }
                }
            }
        }

        if ($count_true_sentence == count($params['answer'])) {
            // Check giới hạn 28/30 câu là ok
            // if ($count_true_sentence >= 28) {
            $user = Admin::where('email', $params['email'])->orWhere('admin_code', $params['email'])->first();
            if ($user) {
                $param_historys['user_id'] = $user->id;
                $param_historys['user_name'] = $user->name;
                $param_historys['total_question'] = count($params['answer']);
                $param_historys['total_true'] = $count_true_sentence;
                $param_historys['total_false'] = $count_false_sentence;

                $history_test = HistoryTest::create($param_historys);

                $user->update(['status' => Consts::USER_STATUS['active']]);

                // login
                $url = $request->input('url') ?? route('admin.home');
                $attempt = Auth::guard('admin')->attempt([
                    'email' => $params['email'],
                    'password' => $params['password'],
                    'status' => Consts::USER_STATUS['active']
                ]);

                if ($attempt) {
                    return redirect($url);
                } else {
                    // Bổ sung thêm phần check nếu đăng nhập bằng admin_code
                    $attempt_code = Auth::guard('admin')->attempt([
                        'admin_code' => $params['email'],
                        'password' => $params['password'],
                        'status' => Consts::USER_STATUS['active']
                    ]);

                    if ($attempt_code) {
                        return redirect($url);
                    }
                }
            }
        } else {
            $user = Admin::where('email', $params['email'])->first();
            if ($user) {
                $param_historys['user_id'] = $user->id;
                $param_historys['user_name'] = $user->name;
                $param_historys['total_question'] = count($params['answer']);
                $param_historys['total_true'] = $count_true_sentence;
                $param_historys['total_false'] = $count_false_sentence;
                $history_test = HistoryTest::create($param_historys);
            }

            $url = $request->input('url') ?? route('admin.home');
            $data_result['count_true_sentence'] = $count_true_sentence;
            $data_result['count_false_sentence'] = $count_false_sentence;
            $data_result['count_total_sentence'] = count($params['answer']);
            $data_result['massage'] = __('Thank you for completing (not enough points to pass)');
            $data_result['url'] = $url;
            $data_result['arrQuestionFalse'] = $arrQuestionFalse;

            return view('admin.pages.test_result', compact('data_result'));
        }
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
    public function store(Request $request, QuestionAnswer $questionAnswer)
    {
        $params = $request->all();
        if (isset($params['item_delete'])) {
            foreach ($params['item_delete'] as $key => $item) {
                if (in_array($item, $questionAnswer->all()->pluck('id')->toArray())) {
                    $questionAnswer = $questionAnswer->find($item);
                    $questionAnswer->delete();
                }
            }
        }
        if (isset($params['list'])) {
            foreach ($params['list'] as $key => $item) {
                // $item['json_params']['answer'][$key]['key'] = $key;
                if (isset($item['id'])) {
                    $questionAnswer = $questionAnswer->find($item['id']);
                    $updateResult =  $questionAnswer->update([
                        'question' => $item['question'],
                        'status' => 'active',
                        'json_params' => $item['json_params'],
                    ]);
                } elseif (!isset($item['id'])) {

                    $questionAnswer_params['question'] = $item['question'];
                    $questionAnswer_params['status'] = Consts::STATUS['active'];
                    $questionAnswer_params['json_params'] = $item['json_params'];

                    $questionAnswer = QuestionAnswer::create($questionAnswer_params);
                }
            }
        }

        return redirect()->back()->with('successMessage', __('Successfully!'));
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
