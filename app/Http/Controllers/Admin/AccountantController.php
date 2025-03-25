<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\UserAction;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountantController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'accountant';
        $this->viewPart = 'admin.pages.accountant';
        $this->responseData['module_name'] = __('Accountant Management');
    }
    public function index(Request $request)
    {
        $params = $request->all();
        $params['result_profile'] = 'da_co_visa';
        $rows = UserAction::getSqlUserAction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type_profile'] = Consts::RESULT_PROFILE;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.index');
    }
    public function show()
    {
        return redirect()->back();
    }
    public function edit($id)
    {
        $user_action = UserAction::find($id);
        $this->responseData['jobs'] = Jobs::getSqlCmsJobs(['id' => $user_action->job_id])->first();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type_profile'] = Consts::RESULT_PROFILE;
        $this->responseData['type_result'] = Consts::RESULT_INTERVIEW;
        $this->responseData['detail'] = $user_action;

        return $this->responseView($this->viewPart . '.edit');
    }
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $user_action = UserAction::find($id);
        if ($user_action->json_params != '') {
            foreach ($user_action->json_params as $key => $val) {
                $params['json_params'][$key] = $val;
            }
        }
        $user_action->fill($params);
        $user_action->save();
        return redirect()->back()->with('successMessage', __('Cập nhật thành công!'));
    }
}
