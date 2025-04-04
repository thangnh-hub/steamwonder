<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\WareHouseInventory;
use App\Models\Admin;
use App\Models\Area;
use App\Models\WarehouseDepartment;
use App\Models\WarehouseAsset;
use App\Models\WarehouseAssetHistory;
use App\Models\WareHousePosition;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Services\WarehouseService;
use App\Http\Services\DataPermissionService;


class WereHouseInventoryController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_inventory';
    $this->viewPart = 'admin.pages.warehouse_inventory';
    $this->responseData['module_name'] = 'Kiểm kê tài sản';
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $params = $request->all();
    // Get list post with filter params

    $params['warehouseinventory_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);;
    $rows = WareHouseInventory::getSqlWareHouseInventory($params)->orderBy('date_received', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;
    $this->responseData['persons'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->where('id', '!=', '1')->get();
    $params_areas['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['areas'] = Area::getSqlArea($params_areas)->get();
    $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();
    $this->responseData['status_inventory'] =  Consts::STATUS_INVENTORY;
    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $this->responseData['persons'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->where('id', '!=', '1')->get();
    $params_areas['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['areas'] = Area::getSqlArea($params_areas)->get();

    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['warehouses'] =  WareHouse::getSqlWareHouse($params_warehouse)->get();

    $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();
    $this->responseData['positions'] =  WareHousePosition::getSqlWareHousePosition()->get();
    $state = Consts::STATE_WAREHOUSES_ASSET;
    $state = array_combine(array_keys($state), array_map('__', $state));
    $this->responseData['state'] =  $state;
    return $this->responseView($this->viewPart . '.create');
  }

  public function getViewListProduct(Request $request)
  {
    $params = $request->only(['warehouse_id', 'department_id', 'position_id']);
    $params['list_position_id'] = [];
    if (isset($params['position_id']) && $params['position_id'] != '') {
      $position = WareHousePosition::find($params['position_id']);
      // Lấy tất cả id các thằng con của nó
      $childIds = $position->allChildren()->pluck('id')->toArray();
      $params['list_position_id'] = array_merge([$position->id], $childIds);
      unset($params['position_id']);
    }
    $warehouse_asset = WarehouseAsset::getSqlWareHouseAsset($params)->get();
    $positions = WareHousePosition::getSqlWareHousePosition($params)->get();
    foreach ($warehouse_asset as $val) {
      $val->name_product_type = __($val->product_type);
    };
    $result['warehouse_asset'] = $warehouse_asset;
    $result['positions'] = $positions;
    return $this->sendResponse($result, 'Lấy thông tin thành công');
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
      'period' => 'required',
      'person_id' => "required",
      'area_id' => 'required',
      'date_received' => 'required',
    ]);
    DB::beginTransaction();
    try {
      $params = $request->except('asset', 'synchronize');
      $asset = $request->only('asset')['asset'] ?? [];
      $params['admin_created_id'] = Auth::guard('admin')->user()->id;
      // Tạo lịch kiểm kê
      $warehouse_inventory = WareHouseInventory::create($params);

      if (count($asset) > 0) {
        // Tạo warehouse_asset_history với type là "kiemke"
        foreach ($asset as $key => $val) {
          $asset = WarehouseAsset::find($key);
          $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['kiemke'];
          $params_asset_history['inventory_id'] = $warehouse_inventory->id;
          $params_asset_history['asset_id'] = $key;
          $params_asset_history['quantity'] = $val['quantity'];
          $params_asset_history['product_id'] = $asset->product_id;
          $params_asset_history['position_id'] = $val['position_id'];
          $params_asset_history['department_id'] = $val['department_id'];
          $params_asset_history['state'] = $val['state'] ?? 'new';
          $params_asset_history['warehouse_id'] = $warehouse_inventory->warehouse_id;
          $params_asset_history['json_params']['note'] = $val['note'];
          WarehouseService::createdWarehouseAssetHistory($params_asset_history);
        }
      } else {
        DB::rollBack();
        return redirect()->back()->with('errorMessage', __('Không tìm thấy sản phẩm!'));
      }

      // Trường hợp lưu và đồng bộ
      $synchronize = $request->only('synchronize')['synchronize'] ?? '';
      if ($synchronize == 'synchronize') {
        $this->synchronizeWarehouseAsset($warehouse_inventory->id);
        DB::commit();
        return redirect()->route($this->routeDefault . '.show', $warehouse_inventory->id)->with('successMessage', __('Đồng bộ tài sản thành công!'));
      }

      DB::commit();
      return redirect()->route($this->routeDefault . '.edit', $warehouse_inventory->id)->with('successMessage', __('Lưu kiểm kê thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\WareHouseInventory  $wareHouseInventory
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $wareHouseInventory = wareHouseInventory::find($id);
    $this->responseData['detail'] = $wareHouseInventory;
    //Lấy thông tin asset_history tương ứng
    $asset_history = WarehouseAssetHistory::where('inventory_id', $wareHouseInventory->id)->get();
    $this->responseData['asset_history'] = $asset_history;
    $this->responseData['state'] = Consts::STATE_WAREHOUSES_ASSET;
    return $this->responseView($this->viewPart . '.show');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\WareHouseInventory  $wareHouseInventory
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $wareHouseInventory = wareHouseInventory::find($id);
    if ($wareHouseInventory->status == Consts::STATUS_INVENTORY['Approve']) {
      return redirect()->route($this->routeDefault . '.show', $wareHouseInventory->id);
    }
    $this->responseData['detail'] = $wareHouseInventory;
    //Lấy thông tin asset_history tương ứng
    $asset_history = WarehouseAssetHistory::where('inventory_id', $wareHouseInventory->id)->get();
    $this->responseData['asset_history'] = $asset_history;
    $this->responseData['state'] = Consts::STATE_WAREHOUSES_ASSET;
    $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();
    $positions = WareHousePosition::getSqlWareHousePosition(['warehouse_id' => $wareHouseInventory->warehouse_id])->get();
    $this->responseData['positions'] = $positions;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\WareHouseInventory  $wareHouseInventory
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $wareHouseInventory = wareHouseInventory::find($id);
      if ($wareHouseInventory->status == Consts::STATUS_INVENTORY['Approve']) {
        return redirect()->back()->with('errorMessage', __('Trạng thái đã duyệt không thể chỉnh sửa!'));
      }

      $asset = $request->only('asset')['asset'];
      foreach ($asset as $key => $val) {
        // lấy asset_history theo key
        $asset_history = WarehouseAssetHistory::find($key);
        $asset_history->state = $val['state'] ?? null;
        $asset_history->department_id = $val['department_id'] ?? null;
        $asset_history->position_id = $val['position_id'] ?? null;
        $asset_history->quantity = $val['quantity'] ?? 0;
        $json = (array) $asset_history->json_params;
        $json['note'] = $val['note'] ?? '';
        $asset_history->json_params = $json;
        $asset_history->save();
      }
      // Trường hợp lưu và đồng bộ
      $synchronize = $request->only('synchronize')['synchronize'] ?? '';
      if ($synchronize == 'synchronize') {
        $synchronize = $this->synchronizeWarehouseAsset($id);
        DB::commit();
        return redirect()->route($this->routeDefault . '.show', $wareHouseInventory->id)->with('successMessage', __('Đồng bộ tài sản thành công!'));
      }
      DB::commit();
      return redirect()->back()->with('successMessage', __('Lưu thông tin thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      abort(422, __($ex->getMessage()));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\WareHouseInventory  $wareHouseInventory
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $wareHouseInventory = wareHouseInventory::find($id);
    if ($wareHouseInventory->status == Consts::STATUS_INVENTORY['Approve']) {
      return redirect()->back()->with('errorMessage', __('Trạng thái đã duyệt không thể Xóa!'));
    }
    $wareHouseInventory->delete();
    WarehouseAssetHistory::where('inventory_id', $id)->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Xóa kiểm kê thành công!'));
  }

  public function synchronizeWarehouseAsset($id)
  {
    DB::beginTransaction();
    try {
      // Cập nhật lại trạng thái => đã duyệt
      $inventory = WareHouseInventory::find($id);
      $inventory->status = Consts::STATUS_INVENTORY['Approve'];
      $inventory->save();
      // Lấy thông tin từ asset_history để cấp nhật lại số lượng
      $asset_history = WarehouseAssetHistory::where('inventory_id', $id)->get();
      foreach ($asset_history as $item) {
        $asset = WareHouseAsset::find($item->asset_id);
        $asset->quantity = $item->quantity;
        $asset->position_id = $item->position_id;
        $asset->department_id = $item->department_id;
        $asset->state = $item->state;
        $asset->save();
      }
      DB::commit();
      return $this->sendResponse('success', 'Đồng bộ tài sản thành công!');
    } catch (Exception $ex) {
      DB::rollBack();
      return $this->sendResponse('warning', $ex->getMessage());
    }
  }
}
