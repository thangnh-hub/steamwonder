<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Models\CmsProduct;
use App\Models\CmsService;
use App\Models\Order;
use App\Models\CountryModel;
use App\Models\City;
use App\Models\Discount;
use App\Models\Ship;
use App\Models\OrderDetail;
use App\Models\Course;
use App\Models\Parameter;
use Illuminate\Support\Facades\App;
use App\Models\User;
use Exception;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrderCourses($id)
    {
        DB::beginTransaction();
        try {
            if (Auth::guard('web')->check() == false) {
                return redirect()->route('home')->with('warningMessage', __('Yêu cầu đăng nhập!'));
            }
            $user = Auth::guard('web')->user();
            // check đăng ký
            $check = Order::where('customer_id',$user->id)->where('syllabus_id', (int)$id)->count();
            if($check > 0){
                return redirect()->back()->with('warningMessage', __('Bạn đã đăng ký khóa học này !'));
            }
            $order_params['is_type'] = Consts::ORDER_TYPE['courses'];
            $order_params['customer_id'] = $user->id;
            $order_params['syllabus_id'] = $id;
            $order = Order::create($order_params);
            DB::commit();
            return redirect()->back()->with('successMessage', __('Đã gửi yêu cầu. Chờ xác nhận từ quản trị viên !'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }


}
