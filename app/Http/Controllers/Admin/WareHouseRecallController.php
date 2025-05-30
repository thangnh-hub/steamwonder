<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Consts;
use App\Models\Admin;
use App\Models\WareHousePosition;
use App\Models\WareHouseEntry;
use App\Models\WarehouseAsset;
use App\Models\WareHouse;
use App\Models\WareHouseEntryDetail;
use App\Models\Area;
use App\Models\Department;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WareHouseRecallController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_recall';
    $this->viewPart = 'admin.pages.warehouse_recall';
    $this->responseData['module_name'] = 'Quản lý thu hồi';
  }

  public function index(Request $request)
  {
    $params = $request->all();
    // Get list post with filter params
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    $this->responseData['status'] =  Consts::WAREHOUSE_STATUS_TRANSFER;
    $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['thu_hoi'];
    $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;
    return $this->responseView($this->viewPart . '.index');
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

    $params['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['warehouses'] = WareHouse::getSqlWareHouse($params)->get();
    $this->responseData['position'] =  WareHousePosition::getSqlWareHousePosition($params)->get();

    $this->responseData['department'] =  Department::getSqlDepartment()->get();
    $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
    $this->responseData['module_name'] = 'Thêm mới Thu hồi';
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
      'area_id' => 'required',
      'day_create' => 'required',
      'period' => 'required',
      'asset' => 'required|array|min:1',
    ]);

    DB::beginTransaction();
    try {
      $user = Auth::guard('admin')->user();
      $params = $request->except('asset');
      $asset =  $request->only('asset')['asset'] ?? '';
      // format lại mảng asset chỉ lấy những sản phẩm được chọn
      $product_asset = $this->filterRecursive($asset);

      $params['staff_request'] = $user->id;
      $params['admin_created_id'] = $user->id;
      $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['thu_hoi'];
      $params['json_params']['list_asset'] = $product_asset;
      $wareHouseEntry = WareHouseEntry::create($params);
      WarehouseService::autoUpdateCode($wareHouseEntry->id, 'TH');
      foreach ($product_asset as $id_product => $asset) {
        // Thêm vào bảng EntryDetail
        $params_detail['entry_id'] = $wareHouseEntry->id;
        $params_detail['period'] = $wareHouseEntry->period;
        $params_detail['product_id'] = $id_product;
        $params_detail['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['thu_hoi'];
        $params_detail['quantity'] = count($asset);
        $params_detail['warehouse_id'] = $request->warehouse_id;
        $params_detail['admin_created_id'] = $user->id;
        $params_detail['created_at'] = Carbon::now();
        WareHouseEntryDetail::create($params_detail);
        // Cập nhật trạng thái từng tài sản và người sử dụng
        foreach ($asset as $val) {
          $detailAsset = WarehouseAsset::find($val['id']);
          $detailAsset->status = Consts::WAREHOUSE_ASSET_STATUS['new'];
          $detailAsset->state = Consts::WAREHOUSE_ASSET_STATUS['new'];
          $detailAsset->staff_entry = null;
          $detailAsset->recall_id = $wareHouseEntry->id;
          $detailAsset->position_id = $val['position'];
          $detailAsset->save();

          //Thêm mới lịch sử tài sản
          $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['thuhoi'];
          $params_asset_history['asset_id'] = $detailAsset->id;
          $params_asset_history['quantity'] = $detailAsset->quantity;
          $params_asset_history['position_id'] = $detailAsset->position_id;
          $params_asset_history['department_id'] = $detailAsset->department_id;
          $params_asset_history['state'] = $detailAsset->state;
          $params_asset_history['product_id'] = $detailAsset->product_id;
          $params_asset_history['day_recall'] = $wareHouseEntry->day_create ?? null;
          $params_asset_history['warehouse_id'] = $detailAsset->warehouse_id;
          WarehouseService::createdWarehouseAssetHistory($params_asset_history);
        }
      }
      DB::commit();
      return redirect()->route('warehouse_recall.index')->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      // throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function show($id)
  {
    $detail = WareHouseEntry::find($id);
    $this->responseData['module_name'] = 'Chi tiết Thu hồi';
    $this->responseData['detail'] = $detail;
    $this->responseData['list_asset'] = WarehouseAsset::where('recall_id', $detail->id)->get();
    return $this->responseView($this->viewPart . '.show');
  }

  public function getAssetFromFilter(Request $request)
  {
    $params = $request->all();
    $params['list_product_type'] = [Consts::WAREHOUSE_PRODUCT_TYPE['taisan'], Consts::WAREHOUSE_PRODUCT_TYPE['congcudungcu']];
    if (isset($params['position_id']) && $params['position_id'] != '') {
      $position = WareHousePosition::find($params['position_id']);
      // Lấy tất cả id các thằng con của nó
      $childIds = $position->allChildren()->pluck('id')->toArray();
      $params['list_position_id'] = array_merge([$position->id], $childIds);
      unset($params['position_id']);
    }
    try {
      $rows = WarehouseAsset::getSqlWarehouseAsset($params)->get();
      if (count($rows) > 0) {
        return $this->sendResponse($rows, 'success');
      }
      return $this->sendResponse('', __('No records available!'));
    } catch (Exception $ex) {
      // throw $ex;
      abort(422, __($ex->getMessage()));
    }
  }

  /** Format lại mảng chỉ lấy những phần từ tồn tại cả position và id */
  function filterRecursive($array)
  {
    return collect($array)->map(function ($item) {
      if (is_array($item)) {
        if (isset($item['position'], $item['id'])) {
          return $item;
        }
        return $this->filterRecursive($item);
      }
    })->filter()->toArray();
  }

  public function indexReimburse(Request $request)
  {
    $params = $request->all();
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    $this->responseData['status'] =  Consts::WAREHOUSE_STATUS_TRANSFER;
    $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
    $params['status'] = Consts::WAREHOUSE_TYPE_ENTRY['hoan_tra'];
    $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;
    $this->responseData['module_name'] = 'Quản lý hoàn trả';
    return $this->responseView($this->viewPart . '.reimburse_index');
  }
  public function createReimburse()
  {
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

    $params['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['warehouses'] = WareHouse::getSqlWareHouse($params)->get();
    $this->responseData['position'] =  WareHousePosition::getSqlWareHousePosition($params)->get();

    $this->responseData['department'] =  Department::getSqlDepartment()->get();
    $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
    $this->responseData['module_name'] = 'Thêm mới Hoàn trả';
    return $this->responseView($this->viewPart . '.reimburse_create');
  }
  public function storeReimburse(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'warehouse_id' => 'required',
      'area_id' => 'required',
      'day_create' => 'required',
      'period' => 'required',
      'asset' => 'required|array|min:1',
    ]);

    DB::beginTransaction();
    try {
      $user = Auth::guard('admin')->user();
      $params = $request->except('asset');
      $list_asset =  $request->only('asset')['asset'] ?? [];
      // format lại mảng asset chỉ lấy những sản phẩm được chọn
      $params['staff_request'] = $user->id;
      $params['admin_created_id'] = $user->id;
      $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
      $params['status'] = Consts::WAREHOUSE_TYPE_ENTRY['hoan_tra'];
      $params['json_params']['list_asset'] = $list_asset;
      $wareHouseEntry = WareHouseEntry::create($params);
      WarehouseService::autoUpdateCode($wareHouseEntry->id, 'XK');
      foreach ($list_asset as $id_product => $asset) {
        // Thêm vào bảng EntryDetail
        $params_detail['entry_id'] = $wareHouseEntry->id;
        $params_detail['period'] = $wareHouseEntry->period;
        $params_detail['product_id'] = $id_product;
        $params_detail['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
        $params_detail['quantity'] = count($asset);
        $params_detail['warehouse_id'] = $request->warehouse_id;
        $params_detail['admin_created_id'] = $user->id;
        WareHouseEntryDetail::create($params_detail);
        // Cập nhật trạng thái từng tài sản
        foreach ($asset as $val) {
          $detailAsset = WarehouseAsset::find($val['id']);
          $detailAsset->status = Consts::WAREHOUSE_ASSET_STATUS['hoan_tra'];
          $detailAsset->state = Consts::WAREHOUSE_ASSET_STATUS['hoan_tra'];
          $detailAsset->staff_entry = null;
          $detailAsset->recall_id = $wareHouseEntry->id;
          $detailAsset->position_id = null;
          $detailAsset->department_id = null;
          $detailAsset->save();

          //Thêm mới lịch sử tài sản
          $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['xuatkho'];
          $params_asset_history['asset_id'] = $detailAsset->id;
          $params_asset_history['quantity'] = $detailAsset->quantity;
          $params_asset_history['position_id'] = $detailAsset->position_id;
          $params_asset_history['department_id'] = $detailAsset->department_id;
          $params_asset_history['state'] = $detailAsset->state;
          $params_asset_history['product_id'] = $detailAsset->product_id;
          $params_asset_history['day_recall'] = $wareHouseEntry->day_create ?? null;
          $params_asset_history['warehouse_id'] = $detailAsset->warehouse_id;
          WarehouseService::createdWarehouseAssetHistory($params_asset_history);
        }
      }
      DB::commit();
      return redirect()->route('warehouse_reimburse.index')->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      // throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function showReimburse($id)
  {
    $detail = WareHouseEntry::find($id);
    $this->responseData['module_name'] = 'Chi tiết Hoàn trả';
    $this->responseData['detail'] = $detail;
    $this->responseData['list_asset'] = WarehouseAsset::where('recall_id', $detail->id)->get();
    return $this->responseView($this->viewPart . '.reimburse_show');
  }
}
