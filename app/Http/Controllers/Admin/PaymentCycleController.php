<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\PaymentCycle;
use App\Models\Area;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentCycleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'payment_cycle';
        $this->viewPart = 'admin.pages.payment_cycle';
        $this->responseData['module_name'] = 'Chu kỳ thanh toán';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'area_id', 'months']);
        $rows = PaymentCycle::getSqlPaymentCycle($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['areas'] = Area::all();
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['areas'] = Area::all();
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['type'] = Consts::TYPE_POLICIES;
        $this->responseData['module_name'] = "Thêm chu kỳ thanh toán";
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
            'months' => 'required',
            'name' => 'required|max:255',
        ]);
        $params = $request->only([
            'area_id',
            'months',
            'name',
            'json_params',
            'is_default',

        ]);

        $params['is_default'] =  $request->is_default ?? '0';
        if ($params['is_default'] != 0) {
            PaymentCycle::where('is_default', 1)->update(['is_default' => 0]);
        }
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;
        PaymentCycle::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentCycle  $payment_cycle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = PaymentCycle::find($id);
        $serviceIds = collect(optional($detail->json_params)->services)->keys();
        // Định nghĩa mối quan hệ tùy chỉnh
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');
        $data_service = collect($detail->json_params->services)->map(function ($val, $key) use ($services) {
            $val->detail = $services->get($key); // Gắn thêm chi tiết service
            return $val;
        })->toArray();
        $result['view'] = view($this->viewPart . '.show', compact('detail', 'data_service'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentCycle  $payment_cycle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment_cycle = PaymentCycle::find($id);
        $this->responseData['areas'] = Area::all();
        $this->responseData['type'] = Consts::TYPE_POLICIES;
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['module_name'] = "Sửa chu kỳ thanh toán";
        $this->responseData['detail'] = $payment_cycle;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentCycle  $payment_cycle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'months' => 'required',
            'name' => 'required|max:255',
        ]);
        $payment_cycle = PaymentCycle::find($id);
        $params = $request->only([
            'area_id',
            'months',
            'name',
            'json_params',
            'is_default',
        ]);
        $params['is_default'] =  $request->is_default ?? '0';
        $params['admin_updated_id'] = $admin->id;
        if ($params['is_default'] != 0) {
            PaymentCycle::where('is_default', 1)->update(['is_default' => 0]);
        }
        $payment_cycle->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentCycle  $payment_cycle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_cycle = PaymentCycle::find($id);
        $payment_cycle->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
