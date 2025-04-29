<?php

namespace App\Http\Controllers\Admin;

use App\Models\EducationProgram;
use App\Models\EducationAge;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Http\Services\DataPermissionService;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         parent::__construct();
         $this->routeDefault = 'services';
         $this->viewPart = 'admin.pages.services';
         $this->responseData['module_name'] = 'Quản lý dịch vụ';
         $this->responseData['routeDefault'] = $this->routeDefault;
     }
 
     public function index(Request $request)
     {
         $params = $request->all();
         $params['order_by'] = 'iorder';
         $rows = Service::getSqlService($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
         $this->responseData['rows'] = $rows;
         $this->responseData['params'] = $params;

         $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
         $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        
         $params_service_category['status'] = Consts::STATUS['active'];
         $this->responseData['list_service_category'] = ServiceCategory::getSqlServiceCategory($params_service_category)->get();

         $this->responseData['list_is_attendance'] = Consts::SERVICE_IS_ATTENDANCE;
         $this->responseData['list_is_default'] = Consts::SERVICE_IS_DEFAULT;
         $this->responseData['list_service_type'] = Consts::SERVICE_TYPE;
         $this->responseData['list_status'] = Consts::STATUS;
         
         return $this->responseView($this->viewPart . '.index');
     }
 
     public function create()
     {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
       
        $this->responseData['list_is_attendance'] = Consts::SERVICE_IS_ATTENDANCE;
        $this->responseData['list_is_default'] = Consts::SERVICE_IS_DEFAULT;
        $this->responseData['list_service_type'] = Consts::SERVICE_TYPE;
        $this->responseData['list_status'] = Consts::STATUS;

        $params_active['status'] = Consts::STATUS['active'];
        $this->responseData['list_service_category'] = ServiceCategory::getSqlServiceCategory($params_active)->get();
        $this->responseData['list_education_age'] = EducationAge::getSqlEducationAge($params_active)->get();
        $this->responseData['list_education_program'] = EducationProgram::getSqlEducationProgram($params_active)->get();

        return $this->responseView($this->viewPart . '.create');
     }
 
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'area_id' => 'required|integer',
                'service_category_id' => 'required|integer',
                'service_detail.price' => 'required|numeric|min:0',
                'service_detail.quantity' => 'required|integer|min:0',
                'service_detail.start_at' => 'required|date',
                'service_detail.end_at' => 'nullable|date|after_or_equal:service_detail.start_at',
            ]);

            $params = $request->except('service_detail');
            $adminId = Auth::guard('admin')->user()->id;
            $params['admin_created_id'] = $adminId;
            $params['iorder'] = $request->iorder ?? 0;
            $service = Service::create($params);

            // Lưu ServiceDetail
            if (isset($request->service_detail)) {
                $detail = $request->service_detail;
                $detail['service_id'] = $service->id;
                $detail['admin_created_id'] = $adminId;

                ServiceDetail::create($detail);
            }

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Thêm dịch vụ thành công'));
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra: ') . $ex->getMessage())->withInput();
        }
    }

 
    public function edit($id)
    {
        $service = Service::findOrFail($id);

        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $params_active['status'] = Consts::STATUS['active'];

        $this->responseData['list_service_category'] = ServiceCategory::getSqlServiceCategory($params_active)->get();
        $this->responseData['list_education_age'] = EducationAge::getSqlEducationAge($params_active)->get();
        $this->responseData['list_education_program'] = EducationProgram::getSqlEducationProgram($params_active)->get();

        $this->responseData['list_is_attendance'] = Consts::SERVICE_IS_ATTENDANCE;
        $this->responseData['list_is_default'] = Consts::SERVICE_IS_DEFAULT;
        $this->responseData['list_service_type'] = Consts::SERVICE_TYPE;
        $this->responseData['list_status'] = Consts::STATUS;

        $this->responseData['service'] = $service;

        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, Service $service)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'area_id' => 'required|integer',
                'service_category_id' => 'required|integer',
                'service_detail.*.price' => 'required|numeric|min:0',
                'service_detail.*.quantity' => 'required|integer|min:0',
                'service_detail.*.start_at' => 'required|date',
                'service_detail.*.end_at' => 'nullable|date|after_or_equal:service_detail.*.start_at',
            ]);

            $params = $request->except('service_detail');
            $adminId = Auth::guard('admin')->user()->id;
            $params['admin_updated_id'] = $adminId;
            $params['iorder'] = $request->iorder ?? 0;
            $service->update($params);

            if (!empty($request->service_detail)) {
                $service->serviceDetail()->delete();
                foreach ($request->service_detail as $detail) {
                    $detail['service_id'] = $service->id;
                    $detail['admin_created_id'] = Auth::guard('admin')->id();
                    ServiceDetail::create($detail);
                }
            }

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Cập nhật dịch vụ thành công'));
        } catch (\Exception $ex) {
            DB::rollback();
            // return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra: ') . $ex->getMessage())->withInput();
            throw $ex; // Rethrow the exception to be handled by the global exception handler
        }
    }
 
     public function destroy(Service $service)
     {
        $service->serviceDetail()->delete();
        $service->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
     }
 
}
