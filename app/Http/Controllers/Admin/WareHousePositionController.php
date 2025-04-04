<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Support\Facades\Auth;
use App\Models\WareHousePosition;
use App\Http\Services\DataPermissionService;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class WareHousePositionController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_position';
    $this->viewPart = 'admin.pages.warehouse_position';
    $this->responseData['module_name'] = 'Quản lý vị trí kho';
  }
  public function index(Request $request)
  {
    $params = $request->all();
    $warehouse_permission = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $params_warehouse['warehouse_permission'] = $warehouse_permission;
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    // Get list post with filter params
    $params['warehouse_permission'] = $warehouse_permission;
    $rows = WareHousePosition::getSqlWareHousePosition($params)->get();
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;
    $this->responseData['status'] =  Consts::STATUS;
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
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    $this->responseData['positions'] =  WareHousePosition::getSqlWareHousePosition()->get();
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
      'warehouse_id' => 'required',
    ]);
    $params = $request->all();
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    WareHousePosition::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\WareHouse  $wareHouse
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $this->responseData['status'] = Consts::STATUS;
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    $this->responseData['positions'] =  WareHousePosition::getSqlWareHousePosition()->get();
    $wareHouse = WareHousePosition::where('id', $id)->whereIn('warehouse_id', $params_warehouse['warehouse_permission'])->first();
    // Check quyền thao tác vị trị kho theo khu vực kho
    if (empty($wareHouse)) {
      return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể sửa vị trí trong kho do bạn quản lý!'));
    }

    $this->responseData['detail'] = $wareHouse;
    return $this->responseView($this->viewPart . '.edit');
  }

  public function update(Request $request, $id)
  {
    $warehouse_permission = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $wareHouse = WareHousePosition::where('id', $id)->whereIn('warehouse_id', $warehouse_permission)->first();
    // Check quyền thao tác vị trị kho theo khu vực kho
    if (empty($wareHouse)) {
      return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể sửa vị trí trong kho do bạn quản lý!'));
    }
    $params = $request->all();
    $request->validate([
      'name' => 'required',
      'warehouse_id' => 'required',
    ]);
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $wareHouse->fill($params);
    $wareHouse->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  public function destroy($id)
  {
    $warehouse_permission = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $wareHouse = WareHousePosition::where('id', $id)->whereIn('warehouse_id', $warehouse_permission)->first();
    // Check quyền thao tác vị trị kho theo khu vực kho
    if (empty($wareHouse)) {
      return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể sửa vị trí trong kho do bạn quản lý!'));
    }
    $wareHouse->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
