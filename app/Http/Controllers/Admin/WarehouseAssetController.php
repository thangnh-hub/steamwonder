<?php

namespace App\Http\Controllers\Admin;

use App\Models\WarehouseAsset;
use App\Models\WareHouseProduct;
use App\Models\WareHouse;
use App\Models\Department;
use App\Models\WareHousePosition;
use App\Models\WareHouseEntry;
use App\Models\WareHouseCategoryProduct;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseAssetStatisticalExport;


class WarehouseAssetController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_asset';
    $this->viewPart = 'admin.pages.warehouse_asset';
    $this->responseData['module_name'] = 'Quản lý tài sản';
  }
  public function index(Request $request)
  {
    $params = $request->all();
    // Danh sách tài sản phân trang
    if (isset($params['position_id']) && $params['position_id'] != '') {
      $position = WareHousePosition::find($params['position_id']);
      // Lấy tất cả id các thằng con của nó
      $childIds = $position->allChildren()->pluck('id')->toArray();
      $params['list_position_id'] = array_merge([$position->id], $childIds);
      $position_id = $params['position_id'];
      unset($params['position_id']);
    }
    $params['asset_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $rows = WarehouseAsset::getSqlWarehouseAsset($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    // Lất tất tài sản để lọc

    $params_all_rows['asset_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $all_rows = WarehouseAsset::getSqlWarehouseAsset($params_all_rows)->get();
    $this->responseData['rows'] =  $rows;
    $this->responseData['status'] =  Consts::STATUS;

    $params_areas["id"] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
    $this->responseData['areas'] = Area::getSqlArea($params_areas)->get();

    $this->responseData['state'] = Consts::STATE_WAREHOUSES_ASSET;
    $this->responseData['type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
    $this->responseData['list_warehouse'] = Warehouse::whereIn('id', WarehouseService::getUniqueObjectToData('warehouse_id', $all_rows))->get();
    $this->responseData['list_department'] =  Department::getSqlDepartment()->get();
    $this->responseData['list_position'] =  WareHousePosition::getSqlWareHousePosition()->get();
    $this->responseData['warehouse_entry'] =  WareHouseEntry::whereIn('id', WarehouseService::getUniqueObjectToData('entry_id', $all_rows))->get();
    $this->responseData['warehouse_deliver'] =  WareHouseEntry::whereIn('id', WarehouseService::getUniqueObjectToData('deliver_id', $all_rows))->get();
    $params['position_id'] = $position_id ?? '';
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
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse()->get();
    $this->responseData['list_product'] = WareHouseProduct::getSqlWareHouseProduct()->get();
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
      'warehouse_id' => 'required',
      'product_id' => 'required',
    ]);
    $params = $request->all();
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    WarehouseAsset::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\WarehouseAsset  $warehouseAsset
   * @return \Illuminate\Http\Response
   */
  public function show(WarehouseAsset $warehouseAsset)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\WarehouseAsset  $warehouseAsset
   * @return \Illuminate\Http\Response
   */
  public function edit(WarehouseAsset $warehouseAsset)
  {
    $asset_permission = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    if (!in_array($warehouseAsset->warehouse_id, $asset_permission)) {
      return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập tài sản này!'));
    }

    $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();

    $this->responseData['list_product'] = WareHouseProduct::getSqlWareHouseProduct()->get();
    $this->responseData['detail'] = $warehouseAsset;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\WarehouseAsset  $warehouseAsset
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, WarehouseAsset $warehouseAsset)
  {
    DB::beginTransaction();
    try {
      $params = $request->except('note');
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
      $params['json_params']['note'] = $request->only('note')['note'];
      $warehouseAsset->fill($params);
      $warehouseAsset->save();

      // Tạo lịch sử trong bảng asset history
      $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['chinhsua'];
      $params_asset_history['asset_id'] = $warehouseAsset->id;
      $params_asset_history['quantity'] = $warehouseAsset->quantity;
      $params_asset_history['position_id'] = $warehouseAsset->position_id;
      $params_asset_history['department_id'] = $warehouseAsset->department_id;
      $params_asset_history['state'] = $warehouseAsset->state;
      $params_asset_history['product_id'] = $warehouseAsset->product_id;
      $params_asset_history['warehouse_id'] = $warehouseAsset->warehouse_id;
      $params_asset_history['json_params']['note'] = $warehouseAsset->json_params->note;
      WarehouseService::createdWarehouseAssetHistory($params_asset_history);

      // Lấy data trả ra view
      $result['state'] = __($warehouseAsset->state ?? '');
      $result['department'] = $warehouseAsset->department->name ?? '';
      $result['position'] = $warehouseAsset->position->name ?? '';
      $result['note'] = $warehouseAsset->json_params->note ?? '';
      // $result['quantity'] = $warehouseAsset->quantity ?? 0;
      DB::commit();
      return $this->sendResponse($result, __('Cập nhật thành công!'));
    } catch (Exception $ex) {
      DB::rollBack();
      return $this->sendResponse('warning', __($ex->getMessage()));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\WarehouseAsset  $warehouseAsset
   * @return \Illuminate\Http\Response
   */
  public function destroy(WarehouseAsset $warehouseAsset)
  {
    $warehouseAsset->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }

  /** Thống kê tổng hợp số lượng */
  public function statistical(Request $request)
  {
    $params = $request->all();
    // Lấy tất cả tài sản trong hệ thống
    $params['asset_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
    $all_asset = WarehouseAsset::getSqlWareHouseAsset($params)
      ->where('tb_warehouses_asset.status', '!=', Consts::WAREHOUSE_ASSET_STATUS['hoan_tra'])
      ->get();
    // tổng hợp tài sản groupBy theo product_id
    $rows = $all_asset
      ->groupBy('product_id')
      ->map(function ($items) {
        $product = new \stdClass();
        $product->product_id = $items->first()['product_id'];
        $product->product_code = $items->first()['product']['code'];
        $product->name = $items->first()->product->name;
        $product->product_type = $items->first()['product_type'];
        return $product;
      })
      ->values();
    // Lấy danh sách kho qua truy vấn
    $list_warehouse = Warehouse::whereIN('id', WarehouseService::getUniqueObjectToData('warehouse_id', $all_asset))->get();
    // Lấy khu vực theo kho
    $list_area =  WarehouseService::getUniqueObjectToData('area', $list_warehouse);
    foreach ($list_area as $area) {
      $area->warehouse = $list_warehouse->filter(function ($item, $key) use ($area) {
        return $item->area_id == $area->id;
      });
    }

    // Gán thông tin số lượng theo từng kho
    // Nhóm $all_asset theo warehouse_id và product_id để truy xuất nhanh hơn
    $groupedAssets = $all_asset->groupBy(function ($item) {
      return $item->warehouse_id . '_' . $item->product_id;
    });
    foreach ($rows as $val) {
      $val->warehouse = $list_warehouse->mapWithKeys(function ($warehouse) use ($groupedAssets, $val) {
        $key = $warehouse->id . '_' . $val->product_id;
        // Lấy tổng số lượng từ groupedAssets nếu tồn tại
        $totalQuantity = $groupedAssets->get($key, collect())->sum('quantity');

        // Tính tổng số lượng trong kho (status = new)
        $total_quantity_new = $groupedAssets->get($key, collect())
          ->filter(fn($item) => $item['status'] == 'new')
          ->sum('quantity');
        // Tính tổng số lượng đang sử dụng (status != new)
        $total_quantity_using = $groupedAssets->get($key, collect())
          ->filter(fn($item) => $item['status'] != 'new')
          ->sum('quantity');

        return [$warehouse->id => [
          'total' => $totalQuantity,
          'new' => $total_quantity_new,
          'using' => $total_quantity_using,
        ]];
      })->toArray();
      // Tổng số lượng tất cả khu vực
      $val->total_warehouse = array_sum(array_column($val->warehouse, 'total'));
      $val->total_warehouse_new = array_sum(array_column($val->warehouse, 'new'));
      $val->total_warehouse_using = array_sum(array_column($val->warehouse, 'using'));
    }

    $this->responseData['rows'] = $rows;
    $this->responseData['count_warehouse'] = $list_warehouse->count();
    $this->responseData['params'] = $params;
    $this->responseData['list_area'] = $list_area;
    $this->responseData['type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
    $this->responseData['warehouse'] = WareHouse::getSqlWareHouse()->get();
    $this->responseData['category_product'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $this->responseData['module_name'] = 'Thống kê số lượng tài sản';
    return $this->responseView($this->viewPart . '.statistical');
  }
  public function exportStatistical(Request $request)
  {
    $params = $request->all();
    return Excel::download(new WarehouseAssetStatisticalExport($params), 'Thong_ke_tai_san.xlsx');
  }
  public function viewStatistical(Request $request)
  {
    $params = $request->all();
    $warehouse_asset = WarehouseAsset::getSqlWareHouseAsset($params)->get();
    // Lấy các phòng ban và số lượng tài sản tương ứng theo phòng ban
    $department_assets = $warehouse_asset
      ->groupBy('department_id')
      ->map(function ($items, $department_id) {
        return [
          'department_id' => $department_id,
          'department_name' => $items->first()->department->code ?? 'Đang trong kho',
          'total_quantity' => $items->sum('quantity'),
        ];
      })->values();


    // Lấy các vị trí từ danh sách tài sản
    $position_assets = $this->getPositionAssets($warehouse_asset);
    $parent_ids = $position_assets->pluck('parent_id')->filter()->unique();
    //Lấy tất cả các cấp cha và gắn Key theo id
    $parent_positions = $this->getParentPositions($parent_ids)->keyBy('position_id');

    // Hợp nhất cha và con
    // Đồng bộ quantity của danh sách cha và con trước khi merge để tránh nhân bản thêm
    $parent_positions = $parent_positions->map(function ($item) use ($position_assets) {
      if (isset($position_assets[$item['position_id']])) {
        // Gộp thông tin của parent vào item hiện tại nếu cần thiết
        $item['quantity'] = $position_assets[$item['position_id']]['quantity'];
      }
      return $item;
    });
    $position_assets = $position_assets->merge($parent_positions)->unique();

    // Tính tổng số lượng theo các cấp
    $position_assets = $position_assets->map(function ($val) use ($position_assets) {
      $val['total_quantity'] = $this->calculateTotalQuantity($position_assets, $val);
      return $val;
    });
    // Format hiển thị theo dạng cây
    $position_hierarchy = $this->buildHierarchy($position_assets);
    // Lấy thông tin để hiển thị view
    $warehause =  Warehouse::find($params['warehouse_id']);
    $product =  WareHouseProduct::find($params['product_id']);
    $result['view_department'] = view($this->viewPart . '.view_statistical', compact('params', 'product', 'warehause', 'department_assets'))->render();
    $result['view_position'] = view($this->viewPart . '.view_statistical', compact('params', 'product', 'warehause', 'position_hierarchy'))->render();
    return $this->sendResponse($result, 'Lấy thông tin thành công');
  }

  // Hàm để phân cấp positions để hiển thị ra view
  function buildHierarchy($positions, $parent_id = null)
  {
    $result = collect();
    $positions->filter(function ($item) use ($parent_id) {
      return $item['parent_id'] === $parent_id;
    })->each(function ($item) use ($positions, $result) {
      $children = $this->buildHierarchy($positions, $item['position_id']);
      $data = [
        'position_id' => $item['position_id'],
        'parent_id' => $item['parent_id'],
        'position_name' => $item['position_name'],
        'quantity' => $item['quantity'],
        'total_quantity' => $item['total_quantity'],
        'children' => $children,
      ];
      // Tính toán colspan dựa trên số lượng con
      $data['colspan'] = $this->countLeafNodes($data);
      $result->push($data);
    });
    return $result;
  }
  /** Lấy danh sách các vị trí từ tài sản*/
  function getPositionAssets($warehouse_asset)
  {
    return $warehouse_asset->groupBy('position_id')->map(function ($items, $position_id) {
      return [
        'position_id' => $position_id,
        'parent_id' => $items->first()->position->parent_id ?? null,
        'position_name' => $items->first()->position->name ?? 'Chưa cập nhật',
        'quantity' => $items->sum('quantity'),
        'total_quantity' => 0, // Sẽ được tính sau
      ];
    });
  }

  /** Lấy danh sách tất cả các vị trí cha nếu có*/
  function getParentPositions($parent_ids, $all_positions = null)
  {
    if ($all_positions === null) {
      $all_positions = collect();
    }
    // Nếu danh sách parent_id trống thì return
    if ($parent_ids->isEmpty()) {
      return $all_positions;
    }
    // Truy vấn danh sách parent hiện tại
    $current_parents = WareHousePosition::whereIn('id', $parent_ids)
      ->get()
      ->mapWithKeys(function ($parent) {
        return [
          $parent->id => [
            'position_id' => $parent->id,
            'parent_id' => $parent->parent_id ?? null,
            'position_name' => $parent->name ?? 'Chưa cập nhật',
            'quantity' => 0, // Không có quantity trực tiếp
            'total_quantity' => 0, // Sẽ được tính sau
          ],
        ];
      });

    // Hợp nhất các parent hiện tại vào danh sách tổng
    $all_positions = $all_positions->merge($current_parents);
    // Lấy danh sách parent_id mới (cha của cha)
    $next_parent_ids = $current_parents->pluck('parent_id')->filter()->unique();
    // Gọi lại hàm để tiếp tục xử lý các cấp cha tiếp theo
    return $this->getParentPositions($next_parent_ids, $all_positions)->unique();
  }
  /** Tính total_quantity của vị trí và con của nó */
  function calculateTotalQuantity($position_assets, $position)
  {
    $total = $position['quantity'];
    $children = $position_assets->filter(fn($child) => $child['parent_id'] === $position['position_id']);
    foreach ($children as $child) {
      $total += $this->calculateTotalQuantity($position_assets, $child);
    }
    return $total;
  }
  /** Đếm số phần tử con */
  function countLeafNodes($list)
  {
    $leafCount = 0;
    // Nếu không có children, nó là một "leaf node" (cấp cuối cùng)
    if (count($list['children']) == 0) {
      return 1;
    }
    // Nếu có con, tiếp tục đệ quy đếm số leaf nodes
    $leafCount = 0;
    foreach ($list['children'] as $child) {
      $leafCount += (int) $this->countLeafNodes($child);
    }
    return $leafCount;
  }
}
