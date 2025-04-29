<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         parent::__construct();
         $this->routeDefault = 'service_categorys';
         $this->viewPart = 'admin.pages.service_categorys';
         $this->responseData['module_name'] = 'Quản lý nhóm dịch vụ';
         $this->responseData['routeDefault'] = $this->routeDefault;
     }
 
     public function index(Request $request)
     {
         $params = $request->all();
         $rows = ServiceCategory::getSqlServiceCategory($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
         $this->responseData['rows'] = $rows;
         $this->responseData['params'] = $params;
         $this->responseData['list_status'] = Consts::STATUS;
         return $this->responseView($this->viewPart . '.index');
     }
 
     public function create()
     {
         $this->responseData['list_status'] = Consts::STATUS;
         return $this->responseView($this->viewPart . '.create');
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'name' => 'required',
         ]);
 
         $params = $request->all();
         $params['admin_created_id'] = Auth::guard('admin')->user()->id;
         ServiceCategory::create($params);
         return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
     }
 
     public function edit(ServiceCategory $serviceCategory)
     {
         $this->responseData['detail'] = $serviceCategory;
         $this->responseData['list_status'] = Consts::STATUS;
         return $this->responseView($this->viewPart . '.edit');
     }
 
     public function update(Request $request, ServiceCategory $serviceCategory)
     {
         $request->validate([
             'name' => 'required',
         ]);
 
         $params = $request->all();
         $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
         $serviceCategory->update($params);
 
         return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
     }
 
     public function destroy(ServiceCategory $serviceCategory)
     {
         $serviceCategory->delete();
         return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
     }

    
}
