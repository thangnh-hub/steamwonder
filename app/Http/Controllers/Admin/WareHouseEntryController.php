<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WareHouseProduct;
use App\Models\WareHouse;
use App\Models\Area;
use App\Models\WareHouseEntry;
use App\Models\Department;
use App\Models\PaymentRequest;
use App\Models\WarehouseAsset;
use App\Models\WareHouseCategoryProduct;
use App\Models\WareHouseEntryDetail;
use App\Http\Services\DataPermissionService;
use Illuminate\Http\Request;
use App\Consts;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Services\WarehouseService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WareHouseEntryImportAsset;

class WareHouseEntryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'entry_warehouse';
    $this->viewPart = 'admin.pages.warehouse_entry';
    $this->responseData['module_name'] = 'Quản lý nhập kho';
  }

  public function entryWarehouse(Request $request)
  {
    $params = $request->all();
    $this->responseData['params'] =  $params;
    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();

    $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
    $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
    $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    $params_order['type'] = Consts::WAREHOUSE_TYPE_ORDER['buy'];
    $params_order['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_order'] = WareHouseOrder::getSqlWareHouseOrder($params_order)
      ->where('tb_warehouse_order_products.status', '<>', 'not approved')
      ->with('orderDetails')
      ->orderBy('warehouse_id')
      ->get();

    return $this->responseView($this->viewPart . '.index');
  }

  public function entryWarehouseCreate(Request $request)
  {
    $user = Auth::guard('admin')->user();
    $params_area['id'] = DataPermissionService::getPermisisonAreas($user->id);
    $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
    $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $this->responseData['module_name'] = __("Thêm mới nhập kho");
    $area_selected = $user->area_id;
    if (isset($request->order_id) && $request->order_id > 0) {
      $order_selected = WareHouseOrder::find($request->order_id);

      $area_selected = $order_selected->area_id > 0 ? $order_selected->area_id : ($order_selected->warehouse->area_id ?? $user->area_id);

      $this->responseData['order_selected'] = $order_selected;
      $params_order['type'] = Consts::WAREHOUSE_TYPE_ORDER['buy'];
      $params_order['status'] = 'approved';
      $params_order['warehouse_id'] = $order_selected->warehouse_id;

      $this->responseData['list_order'] = WareHouseOrder::getSqlWareHouseOrder($params_order)->get();
    }
    $this->responseData['area_selected'] = $area_selected;
    $this->responseData['list_warehouse'] = WareHouse::where('area_id', $area_selected)->get();

    return $this->responseView($this->viewPart . '.create');
  }
  public function entryWarehouseStorePayment(Request $request)
  {
    $params = $request->all();
    $params['user_id'] = Auth::guard('admin')->user()->id;
    $params['is_entry'] = 1;
    $PaymentRequest = PaymentRequest::create($params);
    return redirect()->route('payment_request.show', $PaymentRequest->id)->with('successMessage', __('Add new successfully!'));
  }

  public function entryWarehouseStore(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'warehouse_id' => 'required',
      'area_id' => 'required',
      'period' => 'required',
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
      'cart.*.quantity' => 'nullable|integer|min:1',
      'cart.*.price' => 'nullable|numeric|min:0',
      // 'cart.*.subtotal_money' => 'nullable|numeric|min:0',
    ]);
    DB::beginTransaction();
    try {
      $user = Auth::guard('admin')->user();
      $params = $request->except('cart');
      $params['admin_created_id'] = $user->id;
      $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
      $cart = $request->cart;
      $total_money = array_reduce($cart, function ($carry, $item) {
        return $carry + ($item['quantity'] * $item['price']);
      }, 0);
      $params['total_money'] = $total_money;
      $wareHouseEntry = WareHouseEntry::create($params);
      WarehouseService::autoUpdateCode($wareHouseEntry->id, 'NK');
      if ($request->order_id > 0) {
        WareHouseOrder::find($request->order_id)->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER_BUY['in warehouse']]);
      }
      $data = [];
      $cart = $request->cart;
      foreach ($cart as $details) {
        // Check and store order_detail
        $order_detail_params['entry_id'] = $wareHouseEntry->id;
        $order_detail_params['period'] = $wareHouseEntry->period;
        $order_detail_params['product_id'] = $details['product_id'];
        $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
        $order_detail_params['quantity'] = $details['quantity'] ?? 1;
        $order_detail_params['price'] = $details['price'] ?? null;
        $order_detail_params['subtotal_money'] =  $details['quantity'] * $details['price'] ?? null;
        $order_detail_params['warehouse_id'] = $request->warehouse_id ?? null;
        $order_detail_params['admin_created_id'] = $user->id;
        $order_detail_params['created_at'] = Carbon::now();
        array_push($data, $order_detail_params);

        $detail_product = WareHouseProduct::find($details['product_id']);
        if ($detail_product) {
          if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['taisan'] || $detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['congcudungcu']) {
            $quantity = $details['quantity'] ?? 1;
            for ($i = 1; $i <= $quantity; $i++) {
              // Tạo mã tài sản
              $currentYear = Carbon::now()->year;
              // lần mua
              $year_entry_order = WareHouseEntry::where('type', Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho']) // Chỉ lấy phiếu nhập kho
                ->where('period', 'like', "$currentYear-%")->get()->count();
              $year_entry_order = str_pad(($year_entry_order + 1), 2, '0', STR_PAD_LEFT);
              //Đếm số lượng sản phẩm
              $latestAsset = WarehouseAsset::where('product_id', $details['product_id'])
                ->where('warehouse_id', $request->warehouse_id ?? null)
                ->get()->count();
              $nextNumber = $latestAsset ? $latestAsset + 1 : 1; // Số thứ tự tiếp theo
              $area = WareHouse::find($request->warehouse_id);
              $name_area = isset($area) ? $area->area->code : "";
              $assetCode = Carbon::now()->year . $year_entry_order . $name_area . $detail_product->code_auto . $detail_product->category_product->code_auto . $detail_product->code . '_' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

              // Chuẩn bị dữ liệu để lưu vào bảng `tb_warehouses_asset`
              $WarehouseAsset =  WarehouseAsset::create(
                [
                  'code' => $assetCode,
                  'warehouse_id' => $request->warehouse_id ?? null,
                  'entry_id' => $wareHouseEntry->id,
                  'product_id' => $details['product_id'],
                  'price' => $details['price'] ?? null,
                  'quantity' => 1,
                  'name' => $detail_product->name,
                  'product_type' => $detail_product->warehouse_type ?? "",
                  'status' => Consts::WAREHOUSE_ASSET_STATUS['new'],
                  'state' => Consts::STATE_WAREHOUSES_ASSET['new'],
                  'admin_created_id' => $user->id,
                  'department_id' => $request->department_id ?? ($user->department_id ?? null)
                ]
              );
              // Tạo lịch sử tài sản trong bảng asset history
              $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['nhapkho'];
              $params_asset_history['asset_id'] = $WarehouseAsset->id;
              $params_asset_history['quantity'] = $WarehouseAsset->quantity;
              $params_asset_history['position_id'] = $WarehouseAsset->position_id;
              $params_asset_history['department_id'] = $WarehouseAsset->department_id;
              $params_asset_history['state'] = $WarehouseAsset->state;
              $params_asset_history['product_id'] = $WarehouseAsset->product_id;
              $params_asset_history['warehouse_id'] = $WarehouseAsset->warehouse_id;
              WarehouseService::createdWarehouseAssetHistory($params_asset_history);
            }
          }
          if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['vattutieuhao']) {
            $quantity = $details['quantity'] ?? 1;
            // Kiểm tra nếu mã sản phẩm đã tồn tại
            $existingAsset = WarehouseAsset::where('product_id', $details['product_id'])
              ->where('warehouse_id', $request->warehouse_id ?? null)->first();
            if ($existingAsset) {
              // Cộng dồn số lượng
              $existingAsset->quantity += $quantity;
              $existingAsset->updated_at = Carbon::now();
              $existingAsset->save();
            } else {
              $params_asset = [
                'entry_id' => $wareHouseEntry->id,
                'product_id' => $details['product_id'],
                'product_type' => $detail_product->warehouse_type ?? "",
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'warehouse_id' => $request->warehouse_id ?? null,
                'code' => $detail_product->code,
                'name' => $detail_product->name,
                'status' => Consts::WAREHOUSE_ASSET_STATUS['new'],
                'admin_created_id' => $user->id,
                'department_id' => $request->department_id ?? ($user->department_id ?? null)
              ];
              $existingAsset = WarehouseAsset::create($params_asset);
            }
            // Tạo lịch sử tài sản trong bảng asset history
            $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['nhapkho'];
            $params_asset_history['asset_id'] = $existingAsset->id;
            $params_asset_history['quantity'] = $existingAsset->quantity;
            $params_asset_history['position_id'] = $existingAsset->position_id;
            $params_asset_history['department_id'] = $existingAsset->department_id;
            $params_asset_history['state'] = $existingAsset->state;
            $params_asset_history['product_id'] = $existingAsset->product_id;
            $params_asset_history['warehouse_id'] = $existingAsset->warehouse_id;
            WarehouseService::createdWarehouseAssetHistory($params_asset_history);
          }
        }
      }
      WareHouseEntryDetail::insert($data);

      DB::commit();
      return redirect()->route('entry_warehouse.show', $wareHouseEntry->id)->with('successMessage', __('Add new successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
    }
  }

  public function entryWarehouseEdit(Request $request, $id)
  {
    return redirect()->back()->with('errorMessage', __('Chức năng không khả dụng!'));
  }

  public function entryWarehouseShow(Request $request, $id)
  {
    $entry = WareHouseEntry::find($id);
    $this->responseData['detail'] = $entry;
    $this->responseData['entry_details'] = $entry->entryDetails ?? null;
    $this->responseData['list_assets'] = WarehouseAsset::where('entry_id', $id)
      ->whereIn('tb_warehouses_asset.product_type', ['taisan', 'congcudungcu'])
      ->get();
    $this->responseData['module_name'] = __("CHI TIẾT PHIẾU NHẬP KHO");

    $this->responseData['status'] = Consts::PAYMENT_REQUEST_STATUS;
    $this->responseData['department'] = Department::get();
    $this->responseData['admin'] = Auth::guard('admin')->user();
    return $this->responseView($this->viewPart . '.show');
  }

  public function entryWarehouseUpdate(Request $request, $id)
  {
    return redirect()->back()->with('errorMessage', __('Chức năng không khả dụng!'));
  }

  public function entryWarehouseDelete(Request $request, $id)
  {
    // Không cho xóa nhập kho
    return redirect()->back()->with('successMessage', __('Chức năng xóa hiện tại đang khóa, liên hệ bộ phận kỹ thuật để thực hiện!'));
  }

  public function orderDetailByOrder(Request $request)
  {
    try {
      $rows = WareHouseOrderDetail::where('order_id', $request->id)->orderBy('product_id')->get();
      $rows = $rows->map(function ($row) use ($request) {
        $row->warehouse_type_text = __($row->product->warehouse_type);
        $row->ton_kho = WarehouseService::getTonkho($row->product_id, $request->warehouse_id);
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

  /** Ipmort nhập kho từ file tài sản lên hệ thống */
  public function importEntry(Request $request)
  {
    DB::beginTransaction();
    try {
      $params = $request->all();
      if (isset($params['file'])) {
        if ($this->checkFileImport($params['file']) == false) {
          $_datawith = 'errorMessage';
          $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
          session()->flash($_datawith, $mess);
          return $this->sendResponse($_datawith, $mess);
        }
        $_datawith = 'successMessage';

        $file = $request->file('file'); // Lấy file từ request
        // Lấy tên gốc của file
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Tạo phiếu nhập kho
        $params_entry['name'] = $fileName;
        $params_entry['area_id'] = (int) explode('_', $fileName)[1];   // Khu vực
        $params_entry['warehouse_id'] = (int) explode('_', $fileName)[2];   // Kho
        $params_entry['period'] = date('Y-m', time());
        $params_entry['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params_entry['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
        $wareHouseEntry = WareHouseEntry::create($params_entry);
        WarehouseService::autoUpdateCode($wareHouseEntry->id, 'NK');

        $import = new WareHouseEntryImportAsset($params, $wareHouseEntry->id);
        Excel::import($import, request()->file('file'));
        if ($import->hasError) {
          session()->flash('errorMessage', $import->errorMessage);
          return $this->sendResponse('warning', $import->errorMessage);
        }
        $data_count = $import->getRowCount();
        $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];

        foreach ($data_count['error_mess'] as $val) {
          $mess .= '</br>' . $val;
        };
        if (count($data_count['error_mess']) > 0) {
          $_datawith = 'errorMessage';
        };
        DB::commit();
        session()->flash($_datawith, $mess);
        return $this->sendResponse($_datawith, $mess);
      }
      session()->flash('errorMessage', __('Cần chọn file để Import!'));
      return $this->sendResponse('warning', __('Cần chọn file để Import!'));
    } catch (Exception $ex) {
      // throw $ex;
      DB::rollBack();
      abort(422, __($ex->getMessage()));
    }
  }

  public function updateVAT(Request $request)
  {
    $detail = WareHouseEntryDetail::find($request->id);
    if (isset($detail)) {
      $detail->update([
        'json_params->vat_money' => $request->vat_money,
      ]);
    }
  }
}
