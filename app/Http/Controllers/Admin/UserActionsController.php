<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\UserAction;
use App\Models\Jobs;
use App\Models\HistoryScheduleTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActionsController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'user_actions';
        $this->viewPart = 'admin.pages.user_actions';
        $this->responseData['module_name'] = __('User Actions Management');
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
        $user_admin = Auth::guard('admin')->user();
        if ($user_admin->admin_type != 'diplomatic') {
            $params['action_user_id'] = $user_admin->id;
        }

        $rows = UserAction::getSqlUserAction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['status'] =  Consts::STATUS;
        $this->responseData['type_profile'] =  Consts::RESULT_PROFILE;
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
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserAction  $userAction
     * @return \Illuminate\Http\Response
     */
    public function show(UserAction $userAction)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserAction  $userAction
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAction $userAction)
    {
        $this->responseData['detail'] = $userAction;
        $this->responseData['jobs'] = Jobs::getSqlCmsJobs(['id'=>$userAction->job_id])->first();
        $this->responseData['schedule_test'] = HistoryScheduleTest::getSqlScheduleTestUser(['id_user_action'=>$userAction->id])->get();
        $this->responseData['type_schedule_test'] = Consts::SCHEDULE_TEST;
        $this->responseData['type_result'] = Consts::RESULT_INTERVIEW;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserAction  $userAction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAction $userAction)
    {

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $userAction->fill($params);
        $userAction->save();

        return redirect()->back()->with('successMessage', __('Cập nhật thành công!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserAction  $userAction
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAction $userAction)
    {
        $userAction->status = Consts::STATUS_DELETE;
        $userAction->save();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
