<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notify;
use App\Models\User_notify;
use App\Consts;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class NotifyController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'notify';
    $this->viewPart = 'admin.pages.notify';
    $this->responseData['module_name'] = __('Notify Management');
  }

  public function index(Request $request)
  {
    $params = $request->all();
    $this->responseData['params'] = $params;
    $rows = Notify::getNotify($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $notify_read = User_notify::where('id_user', Auth::guard('admin')->user()->id)->get();
    $this->responseData['rows'] =  $rows;
    return $this->responseView($this->viewPart . '.index');
  }
  public function getNotify(Request $request)
  {
    $user = Auth::guard('admin')->user();
    $DataPermissionService = new DataPermissionService;
    $list_id_student = $DataPermissionService->getPermissionStudents($user->id);
    $list_id_class = $DataPermissionService->getPermissionClasses($user->id);
    $data_id = array_merge($list_id_student, $list_id_class);
    $params['order_by'] = ['created_at' => 'desc'];
    $params['id_object'] = $data_id;
    // if($user->admin_type == 'admission'){
    //     $params['type'] = 'late';
    // }
    $page = $request->input('page', 1);
    $params['rows'] = Notify::getNotify($params)->paginate(Consts::PAGINATE['notify'], ['*'], 'page', $page);
    $params['user_notify'] = User_notify::where('id_user', $user->id)->pluck('id_notify')->toArray();
    return $this->sendResponse($params, 'success');
  }

  public function destroy(Request $request)
  {
    $params = $request->all();
    $data['id_notify'] = $params['id'];
    $notify = Notify::find($data['id_notify']);
    $notify->status = Consts::STATUS_DELETE;
    $notify->save();
    return $this->sendResponse('success', 'Cập nhật thành công');
  }

  public function activeNotify(Request $request)
  {
    $mess = 'false';
    $params = $request->all();
    $data['id_notify'] = $params['id'];
    $data['id_user'] =  Auth::guard('admin')->user()->id;
    $check = User_notify::where('id_notify', $data['id_notify'])->where('id_user', $data['id_user'])->exists();
    if ($check == null) {
      User_notify::create($data);
      $mess = 'true';
    }
    $detail = Notify::find($data['id_notify']);
    return $this->sendResponse($detail, $mess);
  }
}
