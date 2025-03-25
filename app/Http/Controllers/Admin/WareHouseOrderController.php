<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WareHouse;
use App\Models\Area;
use App\Models\WareHouseProduct;
use App\Models\WarehouseAsset;
use App\Models\WarehouseDepartment;
use App\Models\WareHousePosition;
use App\Models\WareHouseCategoryProduct;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Consts;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class WareHouseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'warehouse_order_product';
        $this->viewPart = 'admin.pages.warehouse_order';
        $this->responseData['module_name'] = 'Quản lý Đề xuất Order';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
        $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
        $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();

        $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
        $params['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
        $rows = WareHouseOrder::getSqlWareHouseOrder($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] =  $params;
        $this->responseData['status'] =  Consts::APPROVE_WAREHOUSE_ORDER;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * @author ThangNH
         * Update các nội dung để fix thông tin tạo đề xuất order theo đúng người dùng đang đăng nhập
         */
        $user = Auth::guard('admin')->user();
        // Khu vực chi nhánh
        $this->responseData['list_area'] = Area::where('id', $user->area_id)->get();
        $this->responseData['list_warehouse'] = WareHouse::where('area_id', $user->area_id)->get();
        // Bổ sung sau quyền phòng ban theo khu vực
        $this->responseData['department'] =  WarehouseDepartment::where('id', $user->department_id)->get();
        // Danh mục Sản phẩm
        $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();

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
            'staff_request' => 'required',
            'code' => "unique:tb_warehouse_order_products",
            'area_id' => 'required',
            'warehouse_id' => 'required',
            'period' => 'required',
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
            'cart.*.quantity' => 'nullable|integer|min:1',
            'cart.*.price' => 'nullable|numeric|min:0',
            // 'cart.*.subtotal_money' => 'nullable|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $params = $request->except('cart');
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
            $cart = $request->cart;
            $total_money = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);
            $params['total_money'] = $total_money;
            $wareHouseOrder = WareHouseOrder::create($params);
            WarehouseService::autoUpdateCode($wareHouseOrder->id, 'ĐX');
            $data = [];

            foreach ($cart as $details) {
                // Check and store order_detail
                $order_detail_params['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
                $order_detail_params['order_id'] = $wareHouseOrder->id;
                $order_detail_params['product_id'] = $details['product_id'];
                $order_detail_params['quantity'] = $details['quantity'] ?? 1;
                $order_detail_params['price'] = $details['price'] ?? null;
                $order_detail_params['subtotal_money'] = $details['quantity'] * $details['price'] ?? null;
                $order_detail_params['department'] = $request->department_request;
                $order_detail_params['admin_created_id'] = Auth::guard('admin')->user()->id;
                $order_detail_params['created_at'] = Carbon::now();
                array_push($data, $order_detail_params);
            }
            WareHouseOrderDetail::insert($data);

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WareHouseOrder  $wareHouseOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * Bổ sung thêm check đơn order có thuộc quyền quản lý của người đang đăng nhập không
         */
        $order_permission = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
        if (!in_array($id, $order_permission)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Phiếu order bạn muốn xem không thuộc quyền quản lý của bạn!'));
        }
        $wareHouseOrder = WareHouseOrder::find($id);
        $this->responseData['detail'] = $wareHouseOrder;

        $this->responseData['module_name'] = 'CHI TIẾT ĐỀ XUẤT ORDER';
        return $this->responseView($this->viewPart . '.show');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WareHouseOrder  $wareHouseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**
         * Chỉ có thể sửa đơn do mình tạo hoặc do mình đề xuất
         * Chỉ sửa được đơn ở trạng thái chưa duyệt
         */
        $user = Auth::guard('admin')->user();
        $detail = WareHouseOrder::find($id);
        if ($detail->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
            return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($detail->status) . ' không được phép chỉnh sửa!');
        }
        if ($detail->admin_created_id != $user->id && $detail->staff_request != $user->id) {
            return redirect()->back()->with('errorMessage', __('Chỉ có thể chỉnh sửa đề xuất do bạn tạo hoặc bạn đề xuất!'));
        }
        $this->responseData['list_area'] = Area::where('id', $user->area_id)->get();
        $this->responseData['list_warehouse'] = WareHouse::where('area_id', $user->area_id)->get();
        //Bổ sung sau quyền phòng ban theo khu vực
        $this->responseData['department'] =  WarehouseDepartment::where('id', $user->department_id)->get();
        //Danh mục Sản phẩm
        $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
        $this->responseData['detail'] = $detail;

        if (isset($detail->orderDetails)) {
            foreach ($detail->orderDetails as $row) {
                $row->ton_kho = WarehouseService::getTonkho($row->product_id, $detail->warehouse_id);
            }
        }
        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('admin')->user();
        $detail = WareHouseOrder::find($id);
        if ($detail->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
            return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($detail->status) . ' không được phép chỉnh sửa!');
        }
        if ($detail->admin_created_id != $user->id && $detail->staff_request != $user->id) {
            return redirect()->back()->with('errorMessage', __('Chỉ có thể chỉnh sửa đề xuất do bạn tạo hoặc bạn đề xuất!'));
        }

        $request->validate([
            'name' => 'required',
            'staff_request' => 'required',
            // 'code' => "unique:tb_warehouse_order_products,code," . $detail->id,
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
            $params = $request->except('order_id', 'cart');
            $cart = $request->cart;
            $total_money = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);
            $params['total_money'] = $total_money;
            $detail->fill($params);
            $detail->save();
            //UPdate detail order
            $cart = $request->cart;

            foreach ($cart as $details) {
                // Lấy danh sách product_id từ $cart
                $cartProductIds = collect($cart)->pluck('product_id')->toArray();
                // Xóa các sản phẩm không còn trong giỏ hàng
                WareHouseOrderDetail::where('order_id', $id)
                    ->whereNotIn('product_id', $cartProductIds)
                    ->delete();
                // Duyệt từng sản phẩm trong $cart để thêm/cập nhật
                WareHouseOrderDetail::updateOrCreate(
                    ['order_id' => $id, 'product_id' => $details['product_id']],
                    [
                        'type' => Consts::WAREHOUSE_TYPE_ORDER['order'],
                        'quantity' => $details['quantity'] ?? 1,
                        'price' => $details['price'] ?? null,
                        'subtotal_money' => $details['quantity'] * $details['price'] ?? null,
                        'department' => $request->department_request,
                        'admin_updated_id' => $user->id,
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WareHouseOrder  $wareHouseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::guard('admin')->user();
            $detail = WareHouseOrder::find($id);
            if ($detail->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
                return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($detail->status) . ' không được phép chỉnh sửa!');
            }
            if ($detail->admin_created_id != $user->id && $detail->staff_request != $user->id) {
                return redirect()->back()->with('errorMessage', __('Chỉ có thể chỉnh sửa đề xuất do bạn tạo hoặc bạn đề xuất!'));
            }
            $detail->delete();
            WareHouseOrderDetail::where('order_id', $id)->delete();
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    /**
     * Duyệt đơn đề xuất order
     * Chỉ duyệt đơn thuộc quyền dữ liệu duyệt order theo khu vực quản lý
     */
    public function approve(Request $request)
    {
        try {
            $params['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
            $wareHouseOrder = WareHouseOrder::where('id', $request->id)->whereIn('id', $params['order_permission'])->first();
            if (isset($wareHouseOrder)) {
                if ($wareHouseOrder->status == Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
                    $updateResult =  $wareHouseOrder->update([
                        'status' => Consts::APPROVE_WAREHOUSE_ORDER['approved'],
                    ]);
                }
                if ($updateResult) {
                    session()->flash('successMessage', __('Duyệt phiếu đề xuất thành công!'));
                    return $this->sendResponse("", 'success');
                }

                session()->flash('errorMessage', __('Duyệt phiếu đề xuất không thành công! Bạn không có quyền thao tác dữ liệu!'));
                return $this->sendResponse('', __('No records available!'));
            }

            session()->flash('errorMessage', __('Duyệt phiếu đề xuất không thành công! Bạn không có quyền thao tác dữ liệu!'));
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Tổng hợp phiếu đề xuất order => update
     */
    public function reportOrder(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $warehouse_permission = DataPermissionService::getPermisisonWarehouses($user->id);

        // Khu vực chi nhánh
        $params_area['id'] = DataPermissionService::getPermisisonAreas($user->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $params = $request->all();
        $params['period'] = isset($params['period']) ? $params['period'] : Carbon::now()->format('Y-m');
        $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
        $params['warehouse_permission'] = $warehouse_permission;
        if (!isset($params['status']))
            $params['status'] = [Consts::APPROVE_WAREHOUSE_ORDER['approved'], Consts::APPROVE_WAREHOUSE_ORDER['in order_buy'], Consts::APPROVE_WAREHOUSE_ORDER['out warehouse']];

        $all_product = WareHouseOrderDetail::getWareHouseOrderDetail($params)->get();
        //gruop lại theo product_id
        $rows = $all_product->unique('product_id')->values();

        // Tính tổng số lượng và danh sách phòng ban order sản phẩm
        $rows = $rows->map(function ($product) use ($all_product) {
            // phòng order sản phẩm
            $relatedDepartments = $all_product->where('product_id', $product->product_id)
                ->groupBy('department')
                ->map(function ($items) {
                    return $items->sum('quantity'); // Tính tổng số lượng cho từng phòng
                })->toArray();
            $totalQuantity = array_sum($relatedDepartments); //Tổng số lượng sản phẩm (cộng tất cả các phòng)
            $product->list_departments = $relatedDepartments;
            $product->total_quantity = $totalQuantity;
            return $product;
        });

        // danh sách department
        $params_dep['id'] = $all_product->map(function ($item) {
            return $item->department;
        })->unique()->values()->toArray();
        $this->responseData['list_dep'] = WarehouseDepartment::getSqlWareHouseDepartment($params_dep)->get();
        $this->responseData['rows'] = $rows;

        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = "Tổng hợp đề xuất Order kỳ " . $params['period'];

        $params_warehouse['warehouse_permission'] = $warehouse_permission;
        $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();

        $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();
        $this->responseData['status'] =  Consts::APPROVE_WAREHOUSE_ORDER;

        return $this->responseView($this->viewPart . '.report_order');
    }

    public function search(Request $request)
    {
        try {
            $params_product = $request->all();
            if (isset($params_product['warehouse_category_id']) && $params_product['warehouse_category_id'] != '') {
                $warehouse_category = WareHouseCategoryProduct::find($params_product['warehouse_category_id']);
                // Lấy tất cả id các thằng con của nó
                $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
                $params_product['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
            }

            $rows = WarehouseService::getDataProduct($params_product);
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }



    public function warehouseByArea(Request $request)
    {
        try {
            $params['area_id'] = $request->id;
            $params['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
            $rows = Warehouse::getSqlWareHouse($params)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function depByWarehouse(Request $request)
    {
        try {
            $params['area_id'] = $request->id;
            $rows = WarehouseDepartment::getSqlWareHouseDepartment($params)->get();;
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function getPositionByWarehouse(Request $request)
    {
        try {
            $params['warehouse_id'] = $request->id;
            $rows = WareHousePosition::getSqlWareHousePosition($params)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function confirmOrder(Request $request)
    {
        $id = $request->only('id')['id'];
        $order = WareHouseOrder::find($id);
        if ($order) {
            $json_params = (array)$order->json_params;
            $json_params['confirmed_name'] = Auth::guard('admin')->user()->name;
            $json_params['confirmed_code'] = Auth::guard('admin')->user()->admin_code;
            $order->confirmed = 'da_nhan';
            $order->json_params = $json_params;
            $order->save();
        }
        return $this->sendResponse('success', 'Lưu thông tin thành công');
    }
}
