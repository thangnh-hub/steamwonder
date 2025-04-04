<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Services\LeaveBalanceService;
use App\Http\Services\DataPermissionService;

class LeaveController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->viewPart = 'admin.pages.leaves';
    $this->responseData['module_name'] = __('Quản lý đăng ký ngày nghỉ');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function indexLeaveRequest(Request $request)
  {
    $params = $request->all();
    LeaveBalanceService::addLeaveBalance();
    // Thêm quyền xem dữ liệu
    $admin = Auth::guard('admin')->user();
    // nếu là tk Yến nhân sự hoặc super_admin -> xem hết
    if ($admin->id == 19 || $admin->id == 1) {
      $rows = LeaveRequest::getSqlLeaveRequest($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    } else {
      $rows = LeaveRequest::getSqlLeaveRequest($params)->where(function ($where) use ($admin) {
        return $where->where('tb_leave_requests.user_id', $admin->id)
          ->orWhere('tb_leave_requests.parent_id', $admin->id)
          ->orWhere('tb_leave_requests.approver_id', $admin->id);
      })->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    }
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;
    $this->responseData['status'] = Consts::STATUS_LEAVE_REQUESTS;
    return $this->responseView($this->viewPart . '.request_index');
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function createLeaveRequest()
  {
    // Người duyệt mặc định là tài khoản GĐ Thành
    $this->responseData['approver_user'] = Admin::find(3893);
    return $this->responseView($this->viewPart . '.request_create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeLeaveRequest(Request $request)
  {
    $request->validate([
      'approver_id' => 'required',
      'is_type' => 'required',
      'start_date' => 'required',
      'end_date' => 'required',
      'total_days' => 'required',
      'reason' => 'required',
    ]);
    DB::beginTransaction();
    try {
      $admin = Auth::guard('admin')->user();
      $params = $request->only('approver_id', 'is_type', 'start_date', 'end_date', 'total_days', 'reason', 'note', 'json_params');
      $params['user_id'] = $admin->id;
      $params['admin_created_id'] = $admin->id;
      $params['parent_id'] = $admin->parent_id ?? null;
      $params['status'] = Consts::STATUS_LEAVE_REQUESTS['pending_confirmation'];
      LeaveRequest::create($params);
      DB::commit();
      return redirect()->route('leave.request.index')->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function showLeaveRequest($id)
  {
    $leaveRequest = LeaveRequest::find($id);
    $this->responseData['detail'] = $leaveRequest;
    $params['user_id'] =  $leaveRequest->user_id;
    $params['year'] =  Carbon::parse($leaveRequest->created_at)->year;
    $leaveBalance = LeaveBalance::getSqlLeaveBalance($params)->first();
    $this->responseData['balancy'] = $leaveBalance;
    $this->responseData['status'] = Consts::STATUS_LEAVE_REQUESTS;

    $this->responseData['module_name'] = __('Chi tiết đơn xin nghỉ');
    return $this->responseView($this->viewPart . '.request_show');
  }

  public function editLeaveRequest($id)
  {
    $leaveRequest = LeaveRequest::find($id);
    $admin = Auth::guard('admin')->user();
    if ($leaveRequest->status != 'pending_confirmation') {
      return redirect()->back()->with('errorMessage', __('Đơn xin nghỉ không thể sửa!'));
    }
    if ($leaveRequest->user_id != $admin->id) {
      return redirect()->back()->with('errorMessage', __('Chỉ được sửa đơn do mình tạo!'));
    }

    $this->responseData['detail'] = $leaveRequest;
    $this->responseData['approver_user'] = Admin::find($leaveRequest->approver_id);

    return $this->responseView($this->viewPart . '.request_edit');
  }

  public function updateLeaveRequest(Request $request, $id)
  {
    $request->validate([
      'approver_id' => 'required',
      'is_type' => 'required',
      'start_date' => 'required',
      'end_date' => 'required',
      'total_days' => 'required',
      'reason' => 'required',
    ]);
    DB::beginTransaction();
    try {
      $leaveRequest = LeaveRequest::find($id);
      $params = $request->only('approver_id', 'is_type', 'start_date', 'end_date', 'total_days', 'reason', 'note');
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
      $leaveRequest->fill($params);
      $leaveRequest->save();
      DB::commit();
      return redirect()->route('leave.request.index')->with('successMessage', __('Cập nhật thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function destroyLeaveRequest($id)
  {
    $admin = Auth::guard('admin')->user();
    $leaveRequest = LeaveRequest::find($id);
    if ($leaveRequest->status != 'pending_confirmation') {
      return redirect()->back()->with('errorMessage', __('Đơn xin nghỉ không thể xóa'));
    }
    if ($leaveRequest->user_id != $admin->id) {
      return redirect()->back()->with('errorMessage', __('Chỉ được xóa đơn do mình tạo!'));
    }

    $leaveRequest->delete();
    return redirect()->back()->with('success', __('Xóa thành công!'));
  }

  public function approveLeaveRequest(Request $request)
  {
    DB::beginTransaction();
    try {
      $admin = Auth::guard('admin')->user();
      $id = $request->id;
      $type = $request->type;
      $leaveRequest = LeaveRequest::find($id);

      if ($type == 'parent') {
        // Check chỉ QL trực tiếp mới được xác nhận
        if ($admin->id != $leaveRequest->parent_id) {
          return $this->sendResponse('error', "Chỉ quản lý trực tiếp mới được quyền xác nhận!");
        }
        // Đổi trạng thái sang đã xác nhận và chờ duyệt
        $leaveRequest->status = 'pending_approval';
      }

      if ($type == 'approve') {
        if ($leaveRequest->status != Consts::STATUS_LEAVE_REQUESTS['pending_approval']) {
          return $this->sendResponse('error', "Cần xác thực từ Quản lý trước khi duyệt");
        }
        // Check chỉ QL trực tiếp mới được xác nhận
        if ($admin->id != $leaveRequest->approver_id) {
          return $this->sendResponse('error', "Bạn không có quyền duyệt đơn xin nghỉ này!");
        }
        // Đổi trạng thái sang đã duyệt
        $leaveRequest->status = 'approved';
        // Nếu là loại nghỉ có lương thì cộng thêm phép đã dùng trong năm hiện tại
        if ($leaveRequest->is_type == 'paid') {
          $params['user_id'] =  $leaveRequest->user_id;
          $params['year'] =  Carbon::parse($leaveRequest->created_at)->year;
          $leaveBalance = LeaveBalance::getSqlLeaveBalance($params)->first();
          if ($leaveBalance) {
            // kiểm tra còn phép mới được duyệt
            if ($leaveBalance->available > ($leaveBalance->used_leaves + $leaveRequest->total_days)) {
              $leaveBalance->used_leaves += $leaveRequest->total_days;
              $leaveBalance->save();
            } else {
              return $this->sendResponse('error', "Số ngày phép của " . $leaveBalance->user->name . " không đủ!");
            }
          }
        }
      }
      $leaveRequest->save();
      session()->flash('successMessage', 'Xác nhận thành công!');
      DB::commit();
      return $this->sendResponse('success', 'Xác nhận thành công');
    } catch (Exception $ex) {
      // throw $ex;
      DB::rollBack();
      abort(422, __($ex->getMessage()));
      return $this->sendResponse('error', __($ex->getMessage()));
    }
  }

  public function indexLeaveBalance(Request $request)
  {
    $params = $request->all();
    LeaveBalanceService::addLeaveBalance();
    // Thêm quyền xem dữ liệu
    $admin = Auth::guard('admin')->user();
    $rows = LeaveBalance::getSqlLeaveBalance($params)->orderBy('year', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;
    $this->responseData['module_name'] = __('Quản lý công phép');
    return $this->responseView($this->viewPart . '.leave_balance_index');
  }

  public function updateLeaveBalance(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $params = $request->only('total_leaves', 'transfer_old', 'available', 'used_leaves');
      $leaveBalance = LeaveBalance::find($id);
      $leaveBalance->fill($params);
      $leaveBalance->save();
      // Lấy data trả ra view
      $result['total_leaves'] = $leaveBalance->total_leaves ?? 0;
      $result['transfer_old'] = $leaveBalance->transfer_old ?? 0;
      $result['available'] = $leaveBalance->available ?? 0;
      $result['used_leaves'] = $leaveBalance->used_leaves ?? 0;
      DB::commit();
      return $this->sendResponse($result, __('Cập nhật thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      return $this->sendResponse('warning', __($ex->getMessage()));
    }
  }
}
