<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\ServiceConfig;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceConfigController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault = 'service_config';
        $this->viewPart = 'admin.pages.service_config';
        $this->responseData['module_name'] = 'Quản lý cấu hình dịch vụ';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = ServiceConfig::getSqlServiceConfig()->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['type'] = Consts::SERVICE_FEES;
        $this->responseData['areas'] = Area::all();
        $this->responseData['rows'] = $rows;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['type'] = Consts::SERVICE_FEES;
        $this->responseData['areas'] = Area::all();
        $this->responseData['module_name'] = "Thêm cấu hình phí dịch vụ";
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
            'type' => 'required',
        ]);
        $params = $request->only([
            'type',
            'time_start',
            'time_end',
            'area_id',
        ]);
        $params['json_params'] = $request->only('time_range');
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;

        ServiceConfig::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceConfig  $serviceConfig
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceConfig $serviceConfig)
    {
        $detail = $serviceConfig;
        $result['view'] = view($this->viewPart . '.show', compact('detail'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceConfig  $serviceConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceConfig $serviceConfig)
    {
        $this->responseData['areas'] = Area::all();
        $this->responseData['type'] = Consts::SERVICE_FEES;
        $this->responseData['detail'] = $serviceConfig;
        $this->responseData['module_name'] = "Sửa cấu hình phí dịch vụ";
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceConfig  $serviceConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceConfig $serviceConfig)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'type' => 'required',
        ]);
        $params = $request->only([
            'type',
            'time_start',
            'time_end',
            'area_id',
        ]);
        $params['json_params'] = $request->only('time_range');
        $params['admin_updated_id'] = $admin->id;
        $serviceConfig->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceConfig  $serviceConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceConfig $serviceConfig)
    {
        $serviceConfig->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
