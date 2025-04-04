<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WareHouse;
use App\Models\Area;
use App\Models\Admin;
use App\Models\WareHousePosition;
use App\Models\WarehouseAssetHistory;
use App\Models\WareHouseProduct;
use App\Http\Services\DataPermissionService;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use App\Models\WareHouseCategoryProduct;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\WarehouseAsset;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Services\WarehouseService;
use App\Models\tbClass;

class WareHouseDeliverController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'deliver_warehouse';
    $this->viewPart = 'admin.pages.warehouse_deliver';
    $this->responseData['module_name'] = 'Quản lý xuất kho';
  }

  public function deliverWarehouse(Request $request)
  {

    $params = $request->all();
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->orderBy('tb_warehouses.area_id')->get();
    $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
    $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    // Thay đổi ở đây để duyệt mà vẫn giữ lại các thuộc tính của paginate
    $rows->getCollection()->transform(function ($item) {
      if (isset($item->json_params->list_class_id)) {
        $list_class_id = (array) $item->json_params->list_class_id;
        $list_class = tbClass::whereIn('id', $list_class_id)->get();
        $item['list_class'] = $list_class ?? null;
      }
      return $item;
    });
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] =  $params;

    $params_order['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
    $params_order['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_order'] = WareHouseOrder::getSqlWareHouseOrder($params_order)->get();

    return $this->responseView($this->viewPart . '.index');
  }

  public function deliverWarehouseCreate(Request $request)
  {
    $user = Auth::guard('admin')->user();
    $params_area['id'] = DataPermissionService::getPermisisonAreas($user->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['module_name'] = "Thêm mới xuất kho";
    $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $area_selected = $user->area_id;
    if (isset($request->order_id) && $request->order_id > 0) {
      $order_selected = WareHouseOrder::find($request->order_id);

      $area_selected = $order_selected->area_id > 0 ? $order_selected->area_id : ($order_selected->warehouse->area_id ?? $user->area_id);

      $this->responseData['order_selected'] = $order_selected;
      $params_order['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
      $params_order['status'] = ['approved', 'in order_buy'];
      $params_order['warehouse_id'] = $order_selected->warehouse_id;

      $this->responseData['list_order'] = WareHouseOrder::getSqlWareHouseOrder($params_order)->get();
    }
    $this->responseData['area_selected'] = $area_selected;
    $this->responseData['list_warehouse'] = WareHouse::where('area_id', $area_selected)->get();
    return $this->responseView($this->viewPart . '.create');
  }

  public function deliverWarehouseStore(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'warehouse_id_deliver' => 'required',
      'order_id' => 'required',
      'period' => 'required',
      'day_deliver' => 'required',
      'day_entry' => 'required',
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
      // 'cart.*.subtotal_money' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
      $user = Auth::guard('admin')->user();
      $params = $request->except('cart', 'asset');
      $info_order = WareHouseOrder::find($request->order_id); // Info phiếu đề xuất order

      $params['admin_created_id'] = $user->id;
      $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
      $params['warehouse_id'] = $info_order->warehouse_id ?? null; // kho nhận
      $params['department_id'] = $info_order->department_request ?? null; // phòng ban nhận
      $params['staff_entry'] = $info_order->staff_request ?? null; // người nhận
      $params['staff_deliver'] = $user->id; // người giao
      $cart = $request->cart;
      $total_money = array_reduce($cart, function ($carry, $item) {
        return $carry + ($item['quantity'] * $item['price']);
      }, 0);
      $params['total_money'] = $total_money;
      $wareHouseEntry = WareHouseEntry::create($params);
      WarehouseService::autoUpdateCode($wareHouseEntry->id, 'XK');

      $data = [];
      $cart = $request->cart;
      // Check nếu có tài sản cố định kèm theo thì filter lọc ra tài sản được chọn
      $list_asset = [];
      if (isset($request->asset)) {
        $list_asset = array_filter($request->asset, function ($asset) {
          return isset($asset['id']) && (int) $asset['id'] > 0;
        });
      }
      /**
       * Duyệt danh sách sản phẩm để tạo đơn xuất kho tương ứng
       * - Nếu là vật tư tiêu hao sẽ thực hiện trừ số lượng
       * - Nếu là TSCĐ sẽ thực hiện update thông tin tài sản
       * - Cập nhật lịch sử tương ứng
       */
      foreach ($cart as $details) {
        // Update bảng chi tiết xuất
        $order_detail_params['entry_id'] = $wareHouseEntry->id;
        $order_detail_params['period'] = $wareHouseEntry->period;
        $order_detail_params['product_id'] = $details['product_id'];
        $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
        $order_detail_params['quantity'] = $details['quantity'] ?? 1;
        $order_detail_params['price'] = $details['price'] ?? null;
        $order_detail_params['subtotal_money'] = $details['quantity'] * $details['price'] ?? null;
        $order_detail_params['warehouse_id'] = $info_order->warehouse_id ?? null;
        $order_detail_params['department_id'] = $info_order->department_request ?? null;
        $order_detail_params['staff_entry'] = $info_order->staff_request ?? null;
        $order_detail_params['warehouse_id_deliver'] = $request->warehouse_id_deliver;
        $order_detail_params['admin_created_id'] = $user->id;
        $order_detail_params['created_at'] = Carbon::now();
        array_push($data, $order_detail_params);
        // Nếu là vật tư tiêu hao thì thực hiện cập nhật lại số lượng
        $detail_product = WareHouseProduct::find($details['product_id']);
        if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['vattutieuhao']) {
          // Kiểm tra nếu mã sản phẩm đã tồn tại
          $existingAsset = WarehouseAsset::where('product_id', $details['product_id'])
            ->where('warehouse_id', $request->warehouse_id_deliver)->first();
          if ($existingAsset) {
            // Trừ số lượng
            $existingAsset->quantity -= $details['quantity'] ?? 1;
            $existingAsset->updated_at = Carbon::now();
            $existingAsset->deliver_id = $wareHouseEntry->id;
            $existingAsset->save();
          }
        }
      }
      WareHouseEntryDetail::insert($data);
      // Update lại thông tin tài sản cố định nếu có
      if (count($list_asset) > 0) {
        foreach ($list_asset as $asset_info) {
          $asset = WarehouseAsset::find($asset_info['id']);
          $asset->fill([
            'status' => Consts::WAREHOUSE_ASSET_STATUS['deliver'],
            'deliver_id' => $wareHouseEntry->id,
            'warehouse_id' => $info_order->warehouse_id,
            'department_id' => $info_order->department_request,
            'staff_entry' => $info_order->staff_request,
            'updated_at' => Carbon::now(),
            'position_id' => $asset_info['position'] ?? null,
          ]);
          $asset->save();
          // Tạo lịch sử tài sản trong bảng asset history
          $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['xuatkho'];
          $params_asset_history['asset_id'] = $asset->id;
          $params_asset_history['quantity'] = $asset->quantity;
          $params_asset_history['position_id'] = $asset->position_id;
          $params_asset_history['department_id'] = $asset->department_id;
          $params_asset_history['state'] = $asset->state;
          $params_asset_history['product_id'] = $asset->product_id;
          $params_asset_history['staff_entry'] = $asset->staff_entry;
          $params_asset_history['staff_deliver'] = $request->staff_deliver ?? null;
          $params_asset_history['day_entry'] = $request->day_entry ?? null;
          $params_asset_history['day_deliver'] = $request->day_deliver ?? null;
          $params_asset_history['warehouse_id'] = $asset->warehouse_id;
          $params_asset_history['warehouse_id_deliver'] = $request->warehouse_id_deliver;
          WarehouseService::createdWarehouseAssetHistory($params_asset_history);
        }
      }
      // cập nhật trạng thái phiếu order là đã xuất
      $info_order->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER['out warehouse']]);
      DB::commit();
      return redirect()->route('deliver_warehouse.show', $wareHouseEntry->id)->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function deliverWarehouseShow(Request $request, $id)
  {
    $entry = WareHouseEntry::find($id);
    if (isset($entry->json_params->list_class_id)) {
      $list_class_id = (array) $entry->json_params->list_class_id;
      $list_class = tbClass::whereIn('id', $list_class_id)->get();
      $entry['list_class'] = $list_class ?? null;
    }
    $this->responseData['detail'] = $entry;
    $this->responseData['entry_details'] = $entry->entryDetails ?? null;
    $this->responseData['list_assets'] = WarehouseAsset::where('deliver_id', $id)
      ->whereIn('tb_warehouses_asset.product_type', ['taisan', 'congcudungcu'])
      ->get();

    $this->responseData['module_name'] = "CHI TIẾT PHIẾU XUẤT KHO";

    return $this->responseView($this->viewPart . '.show');
  }

  public function deliverWarehouseEdit(Request $request, $id)
  {
    return redirect()->back()->with('errorMessage', __('Chức năng không khả dụng!'));
  }

  public function deliverWarehouseUpdate(Request $request, $id)
  {
    return redirect()->back()->with('errorMessage', __('Chức năng không khả dụng!'));
  }

  public function deliverWarehouseDelete(Request $request, $id)
  {
    // Không cho xóa xuất kho
    return redirect()->back()->with('successMessage', __('Chức năng xóa hiện tại đang khóa, liên hệ bộ phận kỹ thuật để thực hiện!'));
  }

  public function orderDetailByOrder(Request $request)
  {
    try {
      $rows = WareHouseOrderDetail::where('order_id', $request->id)->orderBy('product_id')->get();
      $rows = $rows->map(function ($row) {
        $row->warehouse_type_text = $row->product->warehouse_type;
        return $row;
      });
      if (count($rows) > 0) {
        return $this->sendResponse($rows, 'success');
      }
      return $this->sendResponse('', __('No records available!'));
    } catch (Exception $ex) {
      // throw $ex;
      abort(422, __($ex->getMessage()));
    }
  }
  public function orderDetailProductIdByOrder(Request $request)
  {
    try {
      $params_asset['status'] = $request->status;
      $params_asset['product_id'] = $request->id;
      $params_asset['warehouse_id'] = $request->warehouse_id;
      $params_asset['id'] = $request->id_asset ?? null;
      $rows = WarehouseAsset::getSqlWareHouseAsset($params_asset)->get();
      $rows = $rows->map(function ($row) use ($request) {
        $params_position['warehouse_id'] = $request->warehouse_id;
        $row->position_by_warehouse = WareHousePosition::getSqlWareHousePosition($params_position)->get();
        return $row;
      });
      if (count($rows) > 0) {
        return $this->sendResponse($rows, 'success');
      }
      return $this->sendResponse('', __('No records available!'));
    } catch (Exception $ex) {
      // throw $ex;
      abort(422, __($ex->getMessage()));
    }
  }
}
