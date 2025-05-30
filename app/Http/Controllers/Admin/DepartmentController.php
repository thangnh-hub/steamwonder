<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Http\Services\DataPermissionService;
use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use App\Consts;

class DepartmentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'department';
    $this->viewPart = 'admin.pages.department';
    $this->responseData['module_name'] = __('Quản lý phòng ban');
  }

  public function index(Request $request)
  {
    $params = $request->all();
    // Get list post with filter params
    $rows = Department::getSqlDepartment($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;
    $this->responseData['status'] =  Consts::STATUS;
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
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
    $this->responseData['list_type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
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
    $request->validate([
      'name' => 'required',
      'code' => 'required',
    ]);
    $params = $request->all();
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    Department::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function show(Department $department)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function edit(Department $department)
  {
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['detail'] = $department;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Department $department)
  {
    $params = $request->all();
    $request->validate([
      'name' => 'required',
      'code' => 'required',
    ]);
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $department->fill($params);
    $department->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Department  $department
   * @return \Illuminate\Http\Response
   */
  public function destroy(Department $department)
  {
    $department->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
