<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Deduction;
use App\Models\Area;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'deductions';
        $this->viewPart = 'admin.pages.deductions';
        $this->responseData['module_name'] = 'Quản lý giảm trừ';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'area_id', 'type']);
        $rows = Deduction::getSqlDeduction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['areas'] = Area::all();
        $this->responseData['status'] = Consts::STATUS;
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
        $this->responseData['condition_type'] = Consts::CONDITION_TYPE;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['module_name'] = "Thêm mới giảm trừ";
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
            'code' => 'required',
            'name' => 'required|max:255',
            'condition_type' => 'required',
        ]);
        $params = $request->only([
            'code',
            'name',
            'condition_type',
            'area_id',
            'description',
            'is_cumulative',
            'json_params',
            'status',
        ]);

        $params['is_cumulative'] =  $request->is_cumulative ?? '0';
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;
        Deduction::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show(Deduction $deduction) {
        $detail = $deduction;
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
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Deduction $deduction)
    {
        // $deduction = Deduction::find($id);
        $this->responseData['detail'] = $deduction;
        $this->responseData['areas'] = Area::all();
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['type'] = Consts::TYPE_POLICIES;
        $this->responseData['condition_type'] = Consts::CONDITION_TYPE;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['module_name'] = "Chỉnh sửa giảm trừ";
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deduction $deduction)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'code' => 'required',
            'name' => 'required|max:255',
            'condition_type' => 'required',
        ]);
        $params = $request->only([
            'code',
            'name',
            'condition_type',
            'area_id',
            'description',
            'is_cumulative',
            'json_params',
            'status',
        ]);
        // $deduction = Deduction::find($id);
        $params['is_cumulative'] =  $request->is_cumulative ?? '0';
        $params['admin_updated_id'] = $admin->id;
        $deduction->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deduction $deduction)
    {
        // $deduction = PaymentCycle::find($id);
        $deduction->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
