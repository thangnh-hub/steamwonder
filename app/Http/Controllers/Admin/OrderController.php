<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\UserService;
use App\Models\AffiliateHistory;
use App\Models\Order;
use App\Models\User;
use App\Models\tbClass;
use App\Models\Admin;
use App\Models\Area;
use App\Models\UserClass;
use App\Models\Holiday;
use App\Models\LessonSylabu;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterConfirmation;
use App\Models\Course;
use App\Models\Syllabus;

class OrderController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'order_courses';
    $this->viewPart = 'admin.pages.orders';
    $this->responseData['module_name'] = __('Quản lý đăng ký');
  }
  public function index(Request $request)
  {
    $this->responseData['module_name'] = __('Quản lý đăng ký dịch vụ');
    $params = $request->all();
    $this->responseData['params'] = $params;
    $params['is_type'] = Consts::ORDER_TYPE['courses'];
    if (isset($params['created_at_from'])) {
      $params['created_at_from'] = Carbon::createFromFormat('d/m/Y', $params['created_at_from'])->format('Y-m-d');
    }
    if (isset($params['created_at_to'])) {
      $params['created_at_to'] = Carbon::createFromFormat('d/m/Y', $params['created_at_to'])->addDays(1)->format('Y-m-d');
    }
    $rows = Order::getOrderCourses($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Order  $order
   * @return \Illuminate\Http\Response
   */
  public function show(Order $order)
  {

    $this->responseData['module_name'] = __('Order Courses Management');
    $this->responseData['detail'] = Order::getOrderCourses(['id' => $order->id])->first();


    // Check if customer_id is existed, get infor of account customer
    if ($order->customer_id > 0) {
      $this->responseData['customer'] = User::find($order->customer_id);
    }
    return $this->responseView($this->viewPart . '.show');
  }

  public function update(Request $request, Order $order)
  {
    // Try catch và xử lý kiểm tra trạng thái đơn hàng để + hoặc - điểm (tiền) cho AFL
    DB::beginTransaction();
    try {
      $request->validate([
        'status' => 'required|max:255'
      ]);
      $params = $request->only([
        'status',
        'admin_note'
      ]);
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
      if ($order->status == 1) {
        return redirect()->back()->with('errorMessage', __('Trạng thái đã hoàn thành không được phép cập nhật !'));
      }
      // Update order
      $order->fill($params);
      $order->save();
      // active xong thì tạo lớp học online và gán học viên vào
      if ($order->status == 1) {
        //Thông tin người đăng ký
        $customer = Admin::where('id', $order->customer_id)->first();
      }
      DB::commit();
      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Order  $order
   * @return \Illuminate\Http\Response
   */
  public function destroy(Order $order)
  {
    // Chỉ xóa đơn hàng khi chưa đc xử lý
    if ($order->status != 0) {
      return redirect()->back()->with('errorMessage', __('Đơn hàng đã sử lý không được phép xóa!'));
    }
    $order->delete();

    return redirect()->back()->with('successMessage', __('Delete record successfully!'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
}
