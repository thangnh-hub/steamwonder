<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Jobs;
use App\Models\Major;
use App\Models\Field;
use App\Models\User;
use App\Models\ScheduleTest;
use App\Models\HistoryScheduleTest;
use App\Models\UserAction;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class JobsController extends Controller
{
    protected $is_type;

    public function __construct()
    {
        $this->is_type  = 'study_abroad';
        $this->routeDefault  = 'jobs';
        $this->viewPart = 'admin.pages.cms_jobs';
        $this->responseData['module_name'] = __('Jobs Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $params['is_type'] = $this->is_type;
        // Get list post with filter params
        $rows = Jobs::getSqlCmsJobs($params)->orderBy('id','desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $params_action['status'] = Consts::STATUS['active'];
        $user_action = UserAction::getSqlUserAction($params_action)->get();
        $this->responseData['user_action'] = $user_action;
        $this->responseData['rows'] =  $rows;;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['status'] = Consts::STATUS;
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
        $this->responseData['status'] = Consts::STATUS;

        $params_partner['user_type'] = Consts::USER_TYPE['partner'];
        $partner = User::getSqlUser($params_partner)->get();
        $this->responseData['partner'] = $partner;

        $params_maijor['status'] = Consts::STATUS['active'];
        $this->responseData['major']=Major::getsqlMajor($params_maijor)->get();

        $this->responseData['industry_group'] = Field::getSqlField()->get();
        $this->responseData['gender_job'] = Consts::GENDER_JOB;
        $this->responseData['job_status'] = Consts::JOB_STATUS;
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
            'job_title' => 'required|max:255',
        ]);
        $params = $request->all();
        $params['user_id'] = Auth::guard('admin')->user()->id;
        $params['is_type'] = $this->is_type;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $cmsJobs = Jobs::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Tạo mới thành công!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jobs  $jobs
     * @return \Illuminate\Http\Response
     */
    public function show(Jobs $jobs)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jobs  $jobs
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Jobs::where('id', $id)->first();
        $this->responseData['status'] = Consts::STATUS;

        $params_partner['user_type'] = Consts::USER_TYPE['partner'];
        $partner = User::getSqlUser($params_partner)->get();
        $this->responseData['partner'] = $partner;

        $params_maijor['status'] = Consts::STATUS['active'];
        $this->responseData['major']=Major::getsqlMajor($params_maijor)->get();

        $this->responseData['industry_group'] = Field::getSqlField()->get();
        $this->responseData['gender_job'] = Consts::GENDER_JOB;
        $this->responseData['job_status'] = Consts::JOB_STATUS;
        $this->responseData['detail'] = $job;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jobs  $jobs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'job_title' => 'required|max:255',
        ]);
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $jobs = Jobs::where('id', $id)->first();
        $jobs->fill($params);
        $jobs->save();
        return redirect()->back()->with('successMessage', __('Cập nhật thành công!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jobs  $jobs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobs = Jobs::where('id', $id)->first();
        $jobs->status = Consts::STATUS_DELETE;
        $jobs->save();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Đã xóa tin đăng thành công!'));
    }

    public function detail($id)
    {
        $job = Jobs::where('id', $id)->first();
        $schedule_test = ScheduleTest::getScheduleTestActive()->orderBy('time', 'ASC')->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['schedule_test'] = $schedule_test;
        $this->responseData['type'] = Consts::SCHEDULE_TEST;
        $this->responseData['day_repeat'] = Consts::DAY_REPEAT;
        $this->responseData['type_result'] = Consts::RESULT_INTERVIEW;
        $user_admin = Auth::guard('admin')->user();
        if ($user_admin->admin_type != Consts::ADMIN_TYPE['diplomatic']) {
            $params['action_user_id'] = $user_admin->id;
        }
        $params['job_id'] = $job->id;
        $user_action = UserAction::getSqlUserAction($params)->get();
        $this->responseData['user_actions'] = $user_action;
        $this->responseData['detail'] = $job;
        return $this->responseView($this->viewPart . '.detail');
    }
    public function apply_job(Request $request)
    {
        $request->validate([
            'json_params.name' => 'required|max:255',
        ]);
        try {
            $params = $request->only('json_params');
            $job_id = $request->only('job_id')['job_id'];
            // Lấy thông tin job
            $jobs = Jobs::getSqlCmsJobs(['id', $job_id])->first();
            //Tạo user action
            $params['action_type'] = 'apply';
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $params['status'] = Consts::STATUS['active'];
            $params['job_id'] = $request->only('job_id')['job_id'];
            $params['action_time'] = Carbon::now()->format('Y-m-d H:i:s');
            $params['action_user_id'] = Auth::guard('admin')->user()->id;
            $params['target_user_id'] = $jobs->user_id;
            $user_action = UserAction::create($params);

            // Tạo lịch sử test - luyện
            $params_schedule = $request->only('json_schedule')['json_schedule'];
            $time_test = '';
            $time_training = '';
            foreach ($params_schedule as $id) {
                if ($id != null) {
                    $params_history_schedule['id_user_action'] = $user_action->id;
                    $params_history_schedule['id_schedule_test'] = $id;
                    $params_history_schedule['id_admin'] = Auth::guard('admin')->user()->id;
                    $params_history_schedule['json_params']['note'] = '';
                    $history_achedule = HistoryScheduleTest::create($params_history_schedule);

                    // lấy thời gian để show ra view
                    $detail_schedule_test = ScheduleTest::getSqlScheduleTest(['id' => $id])->first();
                    if ($detail_schedule_test->is_type == 'test') {
                        $time_test = date('d-m-Y', strtotime($detail_schedule_test->time));
                    } elseif ($detail_schedule_test->is_type == 'training') {
                        $time_training = date('d-m-Y', strtotime($detail_schedule_test->time));
                    }
                }
            }
            // lấy lịch test-luyện mới tạo để in ra view
            $params_schedule_test['from_date'] = date('Y-m-d', time());
            $params_schedule_test['count_slot'] = true;
            $schedule_test = ScheduleTest::getScheduleTestActive()->orderBy('time', 'ASC')->get();

            // lấy thông tin ứng tuyển để append ra view
            $view['job']['id'] = $user_action->id;
            $view['job']['name'] =  $params['json_params']['name'] ?? '';
            $view['job']['link_cv'] =  $params['json_params']['link_cv'] ?? 'Chưa cập  nhật';
            $view['job']['time_test'] =  $time_test != '' ? $time_test : 'Chưa cập  nhật';
            $view['job']['time_training'] = $time_training != '' ? $time_training : 'Chưa cập  nhật';
            $view['job']['admin_name'] = Auth::guard('admin')->user()->name;
            $view['schedule_test'] = $schedule_test;
            $messageResult = "Thêm mới thành công !";
            session()->flash('successMessage', __('Submitted successfully!'));
            DB::commit();
            return $this->sendResponse($view, $messageResult);
        } catch (Exception $ex) {
            // throw $ex;
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function actionRessult(Request $request)
    {
        try {
            $params = $request->only('id', 'type', 'note');
            $user_action = UserAction::find($params['id']);
            $admin = Auth::guard('admin')->user();
            if ($params['type'] != null || $params['type'] != '') {
                // if ($user_action->result_interview == null || $user_action->result_interview == '') {
                    $data['result_interview'] = $params['type'];
                    $data['comment_user_action'] = $params['note'];
                    if ($params['type'] == Consts::RESULT_INTERVIEW['pass']) {
                        $data['result_profile'] = 'dang_cho_hop_dong';
                    }
                    $user_action->fill($data);
                    $user_action->save();

                    $messageResult = "Cập nhật thành công !";
                // } else {
                //     $messageResult = "Kết quả đã cập nhật không thể sửa !";
                // }
            } else {
                $messageResult = "Cần chọn kết quả !";
            }

            $data = 'success';
            DB::commit();
            return $this->sendResponse($data, $messageResult);
        } catch (Exception $ex) {
            // throw $ex;
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
}
