<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Promotion;
use App\Models\Area;
use App\Models\Service;
use App\Models\PaymentCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PromotionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'promotions';
        $this->viewPart = 'admin.pages.promotion';
        $this->responseData['module_name'] = 'Quản lý chương trình khuyến mãi';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'area_id', 'promotion_type']);
        $rows = Promotion::getSqlPromotion($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['areas'] = Area::all();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type'] = Consts::PROMOTION_TYPE;
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
        $this->responseData['type'] = Consts::PROMOTION_TYPE;
        $this->responseData['areas'] = Area::all();
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['payment_cycle'] = PaymentCycle::getSqlPaymentCycle()->get();

        $this->responseData['module_name'] = "Thêm chương trình khuyến mãi";
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
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'promotion_code' => 'required',
            'promotion_name' => 'required|max:255',
            'promotion_type' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'json_params' => 'required',
        ]);
        $params = $request->only([
            'area_id ',
            'promotion_code',
            'promotion_name',
            'promotion_type',
            'description',
            'json_params',
            'status',
            'time_start',
            'time_end',
        ]);
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;
        Promotion::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        // $detail = Promotion::find($id);
        $services = Service::getSqlService()->get();
        $payment_cycle = PaymentCycle::getSqlPaymentCycle()->get();
        $detail = $promotion;
        if (isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1) {

            $data_service = null;
        } else {
            $serviceIds = collect(optional($detail->json_params)->services)->keys();
            // Định nghĩa mối quan hệ tùy chỉnh
            $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');
            $data_service = collect($detail->json_params->services)->map(function ($val, $key) use ($services) {
                $val->detail = $services->get($key);
                return $val;
            })->toArray();
        }
        $result['view'] = view($this->viewPart . '.show', compact('detail', 'data_service','services','payment_cycle'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion)
    {
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['areas'] = Area::all();
        $this->responseData['type'] = Consts::PROMOTION_TYPE;
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['payment_cycle'] = PaymentCycle::getSqlPaymentCycle()->get();
        $this->responseData['detail'] = $promotion;
        $this->responseData['module_name'] = "Sửa chương trình khuyến mãi";
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'promotion_code' => 'required',
            'promotion_name' => 'required|max:255',
            'promotion_type' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'json_params' => 'required',
        ]);
        $params = $request->only([
            'area_id ',
            'promotion_code',
            'promotion_name',
            'promotion_type',
            'description',
            'json_params',
            'status',
            'time_start',
            'time_end',
        ]);
        $params['admin_updated_id'] = $admin->id;
        $promotion->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->status = Consts::STATUS_DELETE;
        $promotion->save();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
