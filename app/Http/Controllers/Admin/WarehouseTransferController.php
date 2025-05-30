<?php

namespace App\Http\Controllers\Admin;

use App\Models\WarehouseTransfer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Consts;
use App\Models\Admin;
use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WarehouseAssetHistory;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use App\Models\WareHouseProduct;
use App\Models\WarehouseAsset;
use App\Models\WareHouse;
use App\Models\Area;
use App\Models\Department;
use App\Models\WareHouseCategoryProduct;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_transfer';
    $this->viewPart = 'admin.pages.warehouse_transfer';
    $this->responseData['module_name'] = 'Quản lý Điều chuyển';
  }

  public function index(Request $request)
  {
    $params = $request->all();
    // Get list post with filter params
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
    $this->responseData['status'] =  Consts::WAREHOUSE_STATUS_TRANSFER;
    $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['dieu_chuyen'];
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
    $this->responseData['module_name'] = 'Thêm mới Điều chuyển';
    //Khu vực chi nhánh
    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['all_area'] = Area::getsqlArea()->get();
    $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
    //Bổ sung sau quyền phòng ban theo khu vực
    $this->responseData['department'] =  Department::getSqlDepartment()->get();
    //Danh mục Sản phẩm
    $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $this->responseData['warehouses'] =  WareHouse::getSqlWareHouse()->get();

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
      'area_id_deliver' => 'required',
      'warehouse_id_deliver' => 'required',
      'day_deliver' => 'required',
      'area_id' => 'required',
      'warehouse_id' => 'required',
      'staff_entry' => 'required',
      'day_entry' => 'required',
      'period' => 'required',
      'day_create' => 'required',
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
      $params = $request->except('cart', 'asset');
      $cart = $request->only('cart')['cart'] ?? '';
      // Người giao và người đề xuất chính là user đăng nhập
      $params['staff_deliver'] = Auth::guard('admin')->user()->id;
      $params['staff_request'] = Auth::guard('admin')->user()->id;
      $params['admin_created_id'] = Auth::guard('admin')->user()->id;
      $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['dieu_chuyen'];
      $params['status'] = Consts::WAREHOUSE_STATUS_TRANSFER['new'];
      $params['json_params']['list_asset'] = $request->only('asset')['asset'] ?? '';
      $total_money = array_reduce($cart, function ($carry, $item) {
        return $carry + ($item['quantity'] * $item['price']);
      }, 0);
      $params['total_money'] = $total_money;
      $wareHouseEntry = WareHouseEntry::create($params);
      WarehouseService::autoUpdateCode($wareHouseEntry->id, 'ĐC');
      $data = [];
      if ($cart != Null) {
        foreach ($cart as $details) {
          // Update bảng chi tiết
          $order_detail_params['entry_id'] = $wareHouseEntry->id;
          $order_detail_params['period'] = $wareHouseEntry->period;
          $order_detail_params['product_id'] = $details['product_id'];
          $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['dieu_chuyen'];
          $order_detail_params['quantity'] = $details['quantity'] ?? 1;
          $order_detail_params['price'] = $details['price'] ?? null;
          $order_detail_params['subtotal_money'] = $details['subtotal_money'] ?? null;
          $order_detail_params['warehouse_id_deliver'] = $params['warehouse_id_deliver'] ?? '';
          $order_detail_params['staff_deliver'] = $params['staff_deliver'] ?? '';
          $order_detail_params['day_deliver'] = $params['day_deliver'] ?? '';
          $order_detail_params['warehouse_id'] = $params['warehouse_id'] ?? '';
          $order_detail_params['staff_entry'] = $params['staff_entry'] ?? '';
          $order_detail_params['day_entry'] = $params['day_entry'] ?? '';
          $order_detail_params['admin_created_id'] = Auth::guard('admin')->user()->id;
          $order_detail_params['created_at'] = Carbon::now();
          array_push($data, $order_detail_params);
        }
        WareHouseEntryDetail::insert($data);
      }
      DB::commit();
      return redirect()->route('warehouse_transfer.index')->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
      // return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\WarehouseTransfer  $warehouseTransfer
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }
    $this->responseData['module_name'] = 'Chi tiết Điều chuyển';
    $warehouseTransfer = WareHouseEntry::find($id);

    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
    //Bổ sung sau quyền phòng ban theo khu vực
    $this->responseData['department'] =  Department::getSqlDepartment()->get();

    //list tài sản đã chọn
    $list_asset = isset($warehouseTransfer->json_params->list_asset) && $warehouseTransfer->json_params->list_asset != '' ? (array)$warehouseTransfer->json_params->list_asset : [];
    $list_asset_ids = array_values(array_map(fn($item) => $item->id, $list_asset));
    $this->responseData['list_asset_ids'] = $list_asset_ids;
    //list kho
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();

    //chi tiết các sản phẩm trong phiếu điều chuyển
    $rows = $warehouseTransfer->entryDetails;
    foreach ($rows as $row) {
      $row->ton_kho = WarehouseService::getTonkho($row->product_id, $warehouseTransfer->warehouse_id_deliver);
    }
    $this->responseData['rows'] = $rows;

    //ID đang đăng nhập
    $this->responseData['id_user'] = Auth::guard('admin')->user()->id;

    $this->responseData['detail'] = $warehouseTransfer;
    return $this->responseView($this->viewPart . '.show');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\WarehouseTransfer  $warehouseTransfer
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $this->responseData['module_name'] = 'Chỉnh sửa Điều chuyển';
    $warehouseTransfer = WareHouseEntry::find($id);

    if ($warehouseTransfer->status != Consts::WAREHOUSE_STATUS_TRANSFER['new']) {
      return redirect()->back()->with('errorMessage', __('Đơn điều chuyển đã nhận hoặc đã duyệt. Không được phép chỉnh sửa ! Vui lòng ấn xem chi tiết'));
    }

    if ($warehouseTransfer->staff_entry != Auth::guard('admin')->user()->id && $warehouseTransfer->staff_deliver != Auth::guard('admin')->user()->id) {
      return redirect()->back()->with('errorMessage', __('Bạn không phải người nhận hoặc người giao của đơn điều chuyển này !'));
    }

    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }

    $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->get();
    //Bổ sung sau quyền phòng ban theo khu vực
    $this->responseData['department'] =  Department::getSqlDepartment()->get();
    //Danh mục Sản phẩm
    $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $this->responseData['detail'] = $warehouseTransfer;
    //list tài sản đã chọn
    $list_asset = isset($warehouseTransfer->json_params->list_asset) && $warehouseTransfer->json_params->list_asset != '' ? (array)$warehouseTransfer->json_params->list_asset : [];
    $list_asset_ids = array_values(array_map(fn($item) => $item->id, $list_asset));
    $this->responseData['list_asset_ids'] = $list_asset_ids;
    //list kho
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();

    //chi tiết các sản phẩm trong phiếu điều chuyển
    $rows = $warehouseTransfer->entryDetails;
    foreach ($rows as $row) {
      $row->ton_kho = WarehouseService::getTonkho($row->product_id, $warehouseTransfer->warehouse_id_deliver);
    }
    $this->responseData['rows'] = $rows;

    if (Auth::guard('admin')->user()->id ==  $warehouseTransfer->staff_entry) {
      return $this->responseView($this->viewPart . '.layout_edit.nguoinhan');
    } else {
      return $this->responseView($this->viewPart . '.layout_edit.nguoigiao');
    }
    // return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\WarehouseTransfer  $warehouseTransfer
   * @return \Illuminate\Http\Response
   */

  //người giao update
  public function update(Request $request, $id)
  {
    $warehouseTransfer = WareHouseEntry::find($id);

    if ($warehouseTransfer->status != Consts::WAREHOUSE_STATUS_TRANSFER['new']) {
      return redirect()->back()->with('errorMessage', __('Đơn điều chuyển đã nhận hoặc đã duyệt. Không được phép chỉnh sửa ! Vui lòng ấn xem chi tiết'));
    }

    if ($warehouseTransfer->staff_deliver != Auth::guard('admin')->user()->id) {

      return redirect()->back()->with('errorMessage', __('Bạn không phải người giao của đơn điều chuyển này !'));
    }

    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }

    $request->validate([
      'day_deliver' => 'required',
      'staff_entry' => 'required',
      'day_entry' => 'required',
      'period' => 'required',
      'day_create' => 'required',
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
      $params = $request->except('cart', 'asset');
      $params['json_params']['list_asset'] = $request->only('asset')['asset'] ?? '';
      $cart = $request->only('cart')['cart'];
      $total_money = array_reduce($cart, function ($carry, $item) {
        return $carry + ($item['quantity'] * $item['price']);
      }, 0);
      $params['total_money'] = $total_money;
      $warehouseTransfer->fill($params);
      $warehouseTransfer->save();
      if ($cart != Null) {
        foreach ($cart as $details) {
          // Lấy danh sách product_id từ $cart
          $cartProductIds = collect($cart)->pluck('product_id')->toArray();
          // Xóa các sản phẩm không còn trong giỏ hàng
          WareHouseEntryDetail::where('entry_id', $id)
            ->whereNotIn('product_id', $cartProductIds)
            ->delete();
          // Duyệt từng sản phẩm trong $cart để thêm/cập nhật
          WareHouseEntryDetail::updateOrCreate(
            ['entry_id' => $id, 'product_id' => $details['product_id']],
            [
              'period' => $request->period,
              'type' => Consts::WAREHOUSE_TYPE_ENTRY['dieu_chuyen'],
              'quantity' => $details['quantity'] ?? 1,
              'price' => $details['price'] ?? null,
              'subtotal_money' => $details['subtotal_money'] ?? null,
              'warehouse_id_deliver' => $warehouseTransfer->warehouse_id_deliver,
              'staff_entry' => $request->staff_entry,
              'warehouse_id' => $warehouseTransfer->warehouse_id,
              'day_entry' => $request->day_entry,
              'day_deliver' => $request->day_deliver,
              'admin_updated_id' => Auth::guard('admin')->user()->id,
            ]
          );
        }
      }
      DB::commit();
      return redirect()->route('warehouse_transfer.index')->with('successMessage', __('Lưu thông tin thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      // throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  //update nhận đơn
  public function receivedTransfer(Request $request, $id)
  {
    $warehouseTransfer = WareHouseEntry::find($id);

    if ($warehouseTransfer->status != Consts::WAREHOUSE_STATUS_TRANSFER['new']) {
      return redirect()->back()->with('errorMessage', __('Đơn điều chuyển đã nhận hoặc đã duyệt. Không được phép chỉnh sửa ! Vui lòng ấn xem chi tiết'));
    }
    if ($warehouseTransfer->staff_entry != Auth::guard('admin')->user()->id) {
      return redirect()->back()->with('errorMessage', __('Bạn không phải người nhận của đơn điều chuyển này !'));
    }

    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }
    $request->validate([
      'day_entry' => 'required',
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
    ]);
    DB::beginTransaction();
    try {
      $params = $request->except('cart', 'asset', 'note');
      $params['json_params'] = (array)$warehouseTransfer->json_params ?? [];
      $params['json_params']['note'] = $request->note;
      $params['json_params']['list_asset_entry'] = $request->only('asset')['asset'] ?? '';
      $params['status'] = Consts::WAREHOUSE_STATUS_TRANSFER['received'];
      $warehouseTransfer->fill($params);
      $warehouseTransfer->save();

      $cart = $request->only('cart')['cart'];
      foreach ($cart as $details) {
        // Duyệt từng sản phẩm trong $cart để thêm/cập nhật
        WareHouseEntryDetail::updateOrCreate(
          ['entry_id' => $id, 'product_id' => $details['product_id']],
          [
            'quantity_entry' => $details['quantity'] ?? 0,
            'admin_updated_id' => Auth::guard('admin')->user()->id,
          ]
        );
      }
      DB::commit();
      return redirect()->route('warehouse_transfer.index')->with('successMessage', __('Xác nhận nhận đơn thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      // throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  public function approvedTransfer(Request $request, $id)
  {
    $warehouseTransfer = WareHouseEntry::find($id);
    if ($warehouseTransfer->status != Consts::WAREHOUSE_STATUS_TRANSFER['received']) {
      return redirect()->back()->with('errorMessage', __('Người nhận cần xác nhận đã nhận đơn mới được phép duyệt'));
    }
    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }

    $request->validate([
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
      $cart = $request->only('cart')['cart'];
      if ($cart != Null) {
        // Check tồn kho trước khi update k số lượng sẽ sai
        foreach ($cart as $details) {
          $detail_product = WareHouseProduct::find($details['product_id']);
          $ton_kho = WarehouseService::getTonkho($details['product_id'], $warehouseTransfer->warehouse_id_deliver);
          if ((int) $ton_kho < (int) $details['quantity_entry']) {
            return redirect()->back()->with('errorMessage', __('Số lượng tồn kho của ' . $detail_product->name . ' không đủ!'));
          }
        }

        foreach ($cart as $details) {
          //update bảng tài sản và lịch sử tsan
          if ($detail_product) {
            if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['taisan']  || $detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['congcudungcu']) {
              $params_asset['id'] = collect($warehouseTransfer->json_params->list_asset)->pluck('id')->all();;
              $listAsset = WarehouseAsset::getSqlWareHouseAsset($params_asset)->get();
              if ($listAsset) {
                foreach ($listAsset as $asset) {
                  //update tsan
                  $asset->fill([
                    'transfer_id' => $id, //đơn điều chuyển id
                    'warehouse_id' => $request->warehouse_id, // cập nhật lại kho theo kho nhận
                    'staff_entry' => $request->staff_entry, // cập nhật lại người nhận/sử dụng theo người nhận
                    'updated_at' => Carbon::now(),
                    'status' => collect($warehouseTransfer->json_params->list_asset_entry)->contains('id', $asset->id) ? $asset->status : Consts::STATUS_ASSET['transfer'],
                    // Nếu id không nằm trong mảng list_asset_entry thì trạng thái sẽ là transfer ngược lại sẽ lấy theo status cũ
                  ]);
                  $asset->save();
                  // Tạo lịch sử tài sản trong bảng asset history
                  $params_history['type'] = Consts::WAREHOUSE_TYPE_ENTRY['dieu_chuyen'];
                  $params_history['asset_id'] = $asset->id;
                  $params_history['quantity'] = 1;
                  $params_history['status'] = $asset->status;
                  $params_history['product_id'] = $asset->product_id;
                  $params_history['warehouse_id'] = $asset->warehouse_id;
                  $params_history['staff_entry'] = $asset->staff_entry;
                  $params_history['day_entry'] = $request->day_entry;
                  $params_history['warehouse_id_deliver'] = $request->warehouse_id_deliver;
                  $params_history['staff_deliver'] = $request->staff_deliver;
                  $params_history['day_deliver'] = $request->day_deliver;
                  WarehouseService::createdWarehouseAssetHistory($params_history);
                }
              }
            }
            if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['vattutieuhao']) {
              $quantity = $details['quantity'];
              // Kiểm tra nếu mã sản phẩm đã tồn tại ở kho giao
              $existingAsset = WarehouseAsset::where('product_id', $details['product_id'])
                ->where('warehouse_id', $request->warehouse_id_deliver)->first();
              if ($existingAsset) {
                // Trừ số lượng
                $existingAsset->quantity -= $quantity;
                $existingAsset->updated_at = Carbon::now();
                $existingAsset->transfer_id = $id;
                $existingAsset->save();
              }

              //Kiểm tra sản phẩm có tồn tại ở kho nhận không, có thì cập nhật số lượng không thì tạo mới
              $existingAsset = WarehouseAsset::where('warehouse_id', $request->warehouse_id)->where('product_id', $details['product_id'])->first();
              WarehouseAsset::updateOrCreate(
                ['warehouse_id' => $request->warehouse_id, 'product_id' => $details['product_id']],
                [
                  'quantity' => $existingAsset ? $existingAsset->quantity + $details['quantity_entry'] : $details['quantity_entry'],
                  'status' => Consts::STATUS_ASSET['new'],
                  'transfer_id' => $id,
                  'product_type' => $detail_product->warehouse_type ?? "",
                  'price' => $details['price'],
                  'warehouse_id' => $request->warehouse_id ?? null,
                  'code' => $detail_product->code,
                  'name' => $detail_product->name,
                  'admin_created_id' => Auth::guard('admin')->user()->id,
                ]
              );
            }
          }
        }
      }

      $warehouseTransfer->update(['status' => Consts::WAREHOUSE_STATUS_TRANSFER['approved']]);
      DB::commit();
      return redirect()->route('warehouse_transfer.index')->with('successMessage', __('Duyệt phiếu điều chuyển thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      // throw $ex;
      return redirect()->back()->with('errorMessage', __($ex->getMessage()));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\WarehouseTransfer  $warehouseTransfer
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $warehouseTransfer = WareHouseEntry::find($id);
    if ($warehouseTransfer->status != Consts::WAREHOUSE_STATUS_TRANSFER['new']) {
      return redirect()->back()->with('errorMessage', __('Đơn điều chuyển đã nhận hoặc đã duyệt. Không được phép xóa !'));
    }
    if ($warehouseTransfer->admin_created_id != Auth::guard('admin')->user()->id) {
      return redirect()->back()->with('errorMessage', __('Chỉ có thể xóa đề xuất do bạn tạo!'));
    }
    $entry_permission = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($id, $entry_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập phiếu điều chuyển này!'));
    }
    DB::beginTransaction();
    try {
      $warehouseTransfer->delete();
      WareHouseEntryDetail::where('entry_id', $id)->delete();
      DB::commit();
      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
    }
  }
}
