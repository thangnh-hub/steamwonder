<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseOrder;
use App\Models\WareHouseOrderDetail;
use App\Models\WareHouse;
use App\Models\Area;
use App\Models\WarehouseDepartment;
use App\Models\WareHouseCategoryProduct;
use App\Models\WareHouseProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Consts;
use App\Models\Admin;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use App\Models\WareHouseEntryDetail;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class WareHouseOrderBuyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'warehouse_order_product_buy';
        $this->viewPart = 'admin.pages.warehouse_order_buy';
        $this->responseData['module_name'] = 'Quản lý Đề xuất mua sắm';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
        $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
        $this->responseData['status'] =  Consts::APPROVE_WAREHOUSE_ORDER_BUY;
        $this->responseData['department'] =  WarehouseDepartment::getSqlWareHouseDepartment()->get();
        $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['buy'];
        $params['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
        $rows = WareHouseOrder::getSqlWareHouseOrder($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

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
        $user = Auth::guard('admin')->user();
        // Khu vực chi nhánh
        $params_area['id'] = DataPermissionService::getPermisisonAreas($user->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        // Bổ sung quyền phòng ban theo khu vực
        $this->responseData['department'] =  WarehouseDepartment::where('id', $user->department_id)->get();
        $this->responseData['list_warehouse'] = WareHouse::where('area_id', $user->area_id)->get();
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
            // Thêm mới mua sắm
            $params = $request->except('cart', 'list_order');
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['buy'];
            $cart = $request->cart;
            $total_money = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);
            $params['total_money'] = $total_money;
            $wareHouseOrder = WareHouseOrder::create($params);
            WarehouseService::autoUpdateCode($wareHouseOrder->id, 'MS');
            //gắn phiếu order với phiếu mua sắm, và Update phiếu order chuyển trạng thái thành đang mua sắm
            $list_order = $request->list_order;
            if ($list_order != null) {
                WareHouseOrder::whereIn('id', $list_order)->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER['in order_buy']]);
                $wareHouseOrder->update([
                    'json_params->related_order' => implode(",", $list_order),
                ]);
            }
            // Thêm chi tiết phiếu mua sắm
            $data = [];
            $cart = $request->cart;
            foreach ($cart as $details) {
                $order_detail_params['type'] = Consts::WAREHOUSE_TYPE_ORDER['buy'];
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

        // Đề xuất order related
        if (isset($wareHouseOrder->json_params->related_order)) {
            // Danh sách các đơn order liên quan
            $list_id_related_order = explode(',', $wareHouseOrder->json_params->related_order);
            $list_relateds = WareHouseOrder::whereIn('id', $list_id_related_order)->with('orderDetails')->orderBy('warehouse_id')->orderBy('id')->get();
            $this->responseData['list_relateds'] = $list_relateds;

            //Những Phòng ban đề xuất trong phiếu đề xuất order
            $list_id_department_order = collect($list_relateds)->pluck('department_request')->unique()->toArray();
            $this->responseData['department'] =  WarehouseDepartment::whereIn('id', $list_id_department_order)->get();
            // Duyệt và lọc + map các phần tử trùng product_id và phòng ban sau đó lấy tổng số lượng
            $allDetails = $list_relateds->flatMap(fn($item) => $item['orderDetails'])
                ->groupBy(fn($item) => $item['product_id'] . '-' . $item['department'])
                ->map(fn($group) => [
                    'product_id' => $group->first()['product_id'],
                    'department' => $group->first()['department'],
                    'quantity' => $group->sum('quantity'),
                ])
                ->values()
                ->groupBy('product_id'); // Nhóm lại và lấy key phần tử chính là id của sản phẩm (dùng khi fill cho sản phẩm trong orderDetail)
            // Lấy ra toàn bộ đơn order có trạng thái khác 'not approved' của các phòng ban trong đơn order_related trong kỳ trước đó
            $prev_month = Carbon::createFromFormat('Y-m', $wareHouseOrder->period)->subMonth()->format('Y-m');
            $list_order_prev_month = WareHouseOrder::whereIn('department_request', $list_id_department_order)->where('period', '=', $prev_month)->where('status', '<>', 'not approved')->with('orderDetails')->orderBy('warehouse_id')->orderBy('id')->get();
            $allDetails_prev = $list_order_prev_month->flatMap(fn($item) => $item['orderDetails'])
                ->groupBy(fn($item) => $item['product_id'] . '-' . $item['department'])
                ->map(fn($group) => [
                    'product_id' => $group->first()['product_id'],
                    'department' => $group->first()['department'],
                    'quantity' => $group->sum('quantity'),
                ])
                ->values()
                ->groupBy('product_id');
        }

        // Lấy tất cả các sản phẩm nhập kho theo phiếu mua sắm
        $param_entry_detail['order_id'] = $wareHouseOrder->id;
        $wareHouseEntryDetail = WareHouseEntryDetail::getSqlWareHouseEntryDetail($param_entry_detail)->get();
        // Lấy tất cả các sản phẩm nhập kho theo phiếu mua sắm
        $param_deliver_detail['order_id'] = explode(',', $wareHouseOrder->json_params->related_order ?? '');
        $wareHouseDeliverDetail = WareHouseEntryDetail::getSqlWareHouseEntryDetail($param_deliver_detail)->get();

        // Duyệt all product trong đơn mua sắm và fill data
        foreach ($wareHouseOrder->orderDetails as $order_detail) {
            // Tồn kho theo sản phẩm
            $order_detail->ton_kho = WarehouseService::getTonkho($order_detail->product_id, $wareHouseOrder->warehouse_id);
            // Tồn kho trước kỳ
            $order_detail->ton_kho_truoc_ky = WarehouseService::getTonkho_truoc_ky($order_detail->product_id, $wareHouseOrder->warehouse_id, $wareHouseOrder->created_at);

            // Fill số lượng order theo sản phẩm của phòng ban trong đề xuất liên quan (nếu có)
            if (isset($allDetails[$order_detail->product_id])) {
                $detail_product = $allDetails[$order_detail->product_id];
                $quantity_by_department = $detail_product
                    ->groupBy('department')
                    ->map(fn($group) => [
                        'quantity' => $group->sum('quantity')
                    ]);
                // Check nếu có order của product đó trong tháng trước
                if (isset($allDetails_prev[$order_detail->product_id])) {
                    $detail_prev = $allDetails_prev[$order_detail->product_id];
                    // fill prev order tháng trước nếu có của phòng ban
                    $quantity_by_department = $quantity_by_department->map(function ($item, $index) use ($detail_prev) {
                        foreach ($detail_prev as $prev) {
                            if ((int) $prev['department'] == (int) $index) {
                                $item['prev'] = $prev['quantity'];
                            }
                        }
                        return $item;
                    });
                }
                // Nhóm mảng theo cấu trúc: orderDetail->quantity_by_department->{id_phongban}->quantity => value là số lượng tương ứng
                $order_detail->quantity_by_department = $quantity_by_department;
            }

            // Số lượng sản phẩm nhập kho tương ứng theo phiếu nhập đã gắn với phiếu mua sắm
            $order_detail->total_entry = !empty($wareHouseEntryDetail) ? $wareHouseEntryDetail->filter(function ($item, $key) use ($order_detail) {
                return $item->product_id == $order_detail->product_id;
            })->sum('quantity') : 0;

            // Số lượng sản phẩm xuất kho tương ứng theo đơn order
            $order_detail->total_deliver = !empty($wareHouseDeliverDetail) ? $wareHouseDeliverDetail->filter(function ($item, $key) use ($order_detail) {
                return $item->product_id == $order_detail->product_id;
            })->sum('quantity') : 0;
        }

        $this->responseData['module_name'] = 'CHI TIẾT PHIẾU ĐỀ XUẤT MUA SẮM';
        return $this->responseView($this->viewPart . '.show');
    }


    public function orderDetailStore(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WareHouseOrder  $wareHouseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**
         * Bổ sung thêm check đơn có thuộc quyền quản lý của người đang đăng nhập không
         * Sẽ không cho thay đổi lại kho và cơ sở để đảm bảo dữ liệu không bị sai lệch
         */
        $user = Auth::guard('admin')->user();
        $order_permission = DataPermissionService::getPermisisonOrderWarehouses($user->id);
        if (!in_array($id, $order_permission)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Phiếu không thuộc quyền quản lý của bạn!'));
        }
        $detail = WareHouseOrder::find($id);
        if ($detail->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
            return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($detail->status) . ' không được phép chỉnh sửa!');
        }
        $detail->orderDetails->map(function ($orderDetail) use ($detail) {
            $orderDetail->ton_kho =  WarehouseService::getTonkho($orderDetail->product_id, $detail->warehouse_id);
            return $orderDetail;
        });
        $this->responseData['detail'] = $detail;
        $this->responseData['category_products'] =  WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
        // Danh sách đê xuất order đã duyệt theo kho
        $params['type'] = Consts::WAREHOUSE_TYPE_ORDER['order'];
        $params['status'] = Consts::APPROVE_WAREHOUSE_ORDER['approved'];
        $params['order_permission'] = $order_permission;
        $params['warehouse_id'] = $detail->warehouse_id;
        $list_orders = WareHouseOrder::getSqlWareHouseOrder($params)->with('orderDetails')->orderBy('warehouse_id')->get();
        $this->responseData['list_orders'] = $list_orders;
        // List đề xuất order related
        if (isset($detail->json_params->related_order)) {
            $list_relateds = WareHouseOrder::getSqlWareHouseOrder()->whereIn('tb_warehouse_order_products.id', explode(',', $detail->json_params->related_order))->with('orderDetails')->orderBy('warehouse_id')->orderBy('tb_warehouse_order_products.id')->get();
            $this->responseData['list_relateds'] = $list_relateds;
        }
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WareHouseOrder  $wareHouseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Bổ sung thêm check đơn có thuộc quyền quản lý của người đang đăng nhập không
         * Sẽ không cho thay đổi lại kho và cơ sở để đảm bảo dữ liệu không bị sai lệch
         */
        $user = Auth::guard('admin')->user();
        $order_permission = DataPermissionService::getPermisisonOrderWarehouses($user->id);
        if (!in_array($id, $order_permission)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Phiếu không thuộc quyền quản lý của bạn!'));
        }
        $wareHouseOrder = WareHouseOrder::find($id);
        if ($wareHouseOrder->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
            return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($wareHouseOrder->status) . ' không được phép chỉnh sửa!');
        }
        $request->validate([
            'name' => 'required',
            'period' => 'required',
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
            'cart.*.quantity' => 'nullable|integer|min:1',
            'cart.*.price' => 'nullable|numeric|min:0',
            // 'cart.*.subtotal_money' => 'nullable|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            // Phiếu order đã gắn sẽ cập nhật thành đã duyệt
            if (isset($wareHouseOrder->json_params->related_order)) {
                WareHouseOrder::whereIn('id', explode(',', $wareHouseOrder->json_params->related_order))
                    ->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER['approved']]);
            }
            $params = $request->only(['name', 'period', 'day_create', 'json_params']);
            $cart = $request->cart;
            $total_money = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);
            $params['total_money'] = $total_money;
            $wareHouseOrder->fill($params);
            $wareHouseOrder->save();

            $list_order = $request->list_order; //list order mới
            //  Update phiếu order mới thành related và update thành đã gắn
            if ($list_order != null) {
                WareHouseOrder::whereIn('id', $list_order)->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER['in order_buy']]);
                $wareHouseOrder->update([
                    'json_params->related_order' => implode(",", $list_order),
                ]);
            }
            // UPdate detail order
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
            /**
             * Bổ sung thêm check đơn có thuộc quyền quản lý của người đang đăng nhập không
             * Sẽ không cho thay đổi lại kho và cơ sở để đảm bảo dữ liệu không bị sai lệch
             */
            $user = Auth::guard('admin')->user();
            $order_permission = DataPermissionService::getPermisisonOrderWarehouses($user->id);
            if (!in_array($id, $order_permission)) {
                return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Phiếu không thuộc quyền quản lý của bạn!'));
            }
            $wareHouseOrder = WareHouseOrder::find($id);
            if ($wareHouseOrder->status != Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
                return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($wareHouseOrder->status) . ' không được phép xóa!');
            }
            // Phiếu order đã gắn sẽ cập nhật thành đã duyệt
            if (isset($wareHouseOrder->json_params->related_order)) {
                WareHouseOrder::whereIn('id', explode(',', $wareHouseOrder->json_params->related_order))->where('status', Consts::APPROVE_WAREHOUSE_ORDER['in order_buy'])
                    ->update(['status' => Consts::APPROVE_WAREHOUSE_ORDER['approved']]);
            }
            $wareHouseOrder->delete();
            WareHouseOrderDetail::where('order_id', $id)->delete();
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }


    public function orderDetailByOrder(Request $request)
    {
        try {
            $rows = WareHouseOrderDetail::where('order_id', $request->id)->orderBy('product_id')->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function getOrderByWarehouse(Request $request)
    {
        try {
            //Danh sách đê xuất order đã duyệt
            $type = $request->type ?? Consts::WAREHOUSE_TYPE_ORDER['order'];
            $params['type'] = $type;
            $params['status'] = $request->status;
            $params['warehouse_id'] = $request->warehouse_id;
            $params['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
            $list_orders = WareHouseOrder::getSqlWareHouseOrder($params)->with('orderDetails')->orderBy('warehouse_id')->get();
            foreach ($list_orders as $list_order) {
                $list_order->status = __($list_order->status);
                $list_order->link_order = route('warehouse_order_product.show', $list_order->id);

                $list_order->data_products = $list_order->orderDetails->map(function ($orderDetail) use ($request) {
                    return [
                        'id' => $orderDetail->product->id ?? null,
                        'name' => $orderDetail->product->name ?? null,
                        'unit' => $orderDetail->product->unit ?? null,
                        'warehouse_type' => $orderDetail->product->warehouse_type ?? null,
                        'quantity' => $orderDetail->quantity ?? 0,
                        'price' => $orderDetail->price ?? 0,
                        'subtotal_money' => $orderDetail->subtotal_money ?? 0,
                        //Nếu có tồn tại kho giao thì lấy tồn kho theo kho giao (phục vụ điều chuyển)
                        'ton_kho' => WarehouseService::getTonkho($orderDetail->product->id, (isset($request->warehouse_deliver) && $request->warehouse_deliver != "") ? $request->warehouse_deliver : $request->warehouse_id),
                    ];
                });
            }
            if (count($list_orders) > 0) {
                return $this->sendResponse($list_orders, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function approve(Request $request)
    {
        try {
            $params['order_permission'] = DataPermissionService::getPermisisonOrderWarehouses(Auth::guard('admin')->user()->id);
            $wareHouseOrder = WareHouseOrder::where('id', $request->id)->whereIn('id', $params['order_permission'])->first();

            if (isset($wareHouseOrder)) {
                if ($wareHouseOrder->status == Consts::APPROVE_WAREHOUSE_ORDER['not approved']) {
                    $updateResult =  $wareHouseOrder->update([
                        'status' => Consts::APPROVE_WAREHOUSE_ORDER['approved'],
                        'approved_id' => Auth::guard('admin')->user()->id,
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
    public function printPaymentRequest(Request $request)
    {
        $params = $request->all();
        $user =  Auth::guard('admin')->user();
        $order_buy = WareHouseOrder::find($params['id']);
        $department = WarehouseDepartment::find($user->department_id);
        $total_money = $order_buy->total_money + ((int) $params['vat10'] ?? 0) + ((int) $params['vat8'] ?? 0) - ((int) $params['money'] ?? 0);
        $text_money = $this->convertNumberToWords((int)$total_money);
        $data = [
            'name' => $user->name,
            'bo_phan' => $department->name,
            'total_money' => $total_money,
            'text_money' => $text_money,
        ];
        return view($this->viewPart . '.print', compact('data', 'order_buy', 'params'))->render();
    }

    public function convertNumberToWords($number)
    {
        $hyphen = ' ';
        $conjunction = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = [
            0 => 'không',
            1 => 'một',
            2 => 'hai',
            3 => 'ba',
            4 => 'bốn',
            5 => 'năm',
            6 => 'sáu',
            7 => 'bảy',
            8 => 'tám',
            9 => 'chín',
            10 => 'mười',
            11 => 'mười một',
            12 => 'mười hai',
            13 => 'mười ba',
            14 => 'mười bốn',
            15 => 'mười lăm',
            16 => 'mười sáu',
            17 => 'mười bảy',
            18 => 'mười tám',
            19 => 'mười chín',
            20 => 'hai mươi',
            30 => 'ba mươi',
            40 => 'bốn mươi',
            50 => 'năm mươi',
            60 => 'sáu mươi',
            70 => 'bảy mươi',
            80 => 'tám mươi',
            90 => 'chín mươi',
            100 => 'trăm',
            1000 => 'nghìn',
            1000000 => 'triệu',
            1000000000 => 'tỷ'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }

        $string = '';
        $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = (int) ($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $hyphen . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];

                if ($remainder) {
                    // Thêm logic xử lý không trăm
                    if ($remainder < 1000) {
                        $string .= $hyphen . $this->convertNumberToWords($remainder);
                    } else {
                        $string .= ' ' . $this->convertNumberToWords($remainder);
                    }
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];
            foreach (str_split((string) $fraction) as $digit) {
                $words[] = $dictionary[$digit];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
