<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouse;
use App\Models\WareHousePosition;
use App\Models\WareHouseCategoryProduct;
use App\Http\Services\DataPermissionService;
use App\Http\Services\WarehouseService;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Consts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseExport;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'warehouse';
        $this->viewPart = 'admin.pages.warehouse';
        $this->responseData['module_name'] = 'Quản lý kho';
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $params['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
        $rows = WareHouse::getSqlWareHouse($params)->orderBy('area_id')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
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
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        WareHouse::create($params);
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
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $wareHouse = WareHouse::where('id', $id)->whereIn('area_id', $params_area['id'])->first();
        // Check quyền thao tác kho theo khu vực
        if (empty($wareHouse)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể sửa kho do bạn quản lý!'));
        }
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['detail'] = $wareHouse;
        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, $id)
    {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $wareHouse = WareHouse::where('id', $id)->whereIn('area_id', $params_area['id'])->first();
        // Check quyền thao tác kho theo khu vực
        if (empty($wareHouse)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể sửa kho do bạn quản lý!'));
        }
        $params = $request->all();
        $request->validate([
            'name' => 'required',
        ]);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $wareHouse->fill($params);
        $wareHouse->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function destroy($id)
    {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $wareHouse = WareHouse::where('id', $id)->whereIn('area_id', $params_area['id'])->first();
        // Check quyền thao tác kho theo khu vực
        if (empty($wareHouse)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chỉ có thể xóa kho do bạn quản lý!'));
        }

        $wareHouse->delete();
        WareHousePosition::where('warehouse_id', $id)->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function reportOrderEntryDeliver(Request $request)
    {
        $params = $request->all();
        if (isset($params['warehouse_category_id']) && $params['warehouse_category_id'] != '') {
            $warehouse_category = WareHouseCategoryProduct::find($params['warehouse_category_id']);
            // Lấy tất cả id các thằng con của nó
            $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
            $params['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
            $params['warehouse_category_id_before'] = $warehouse_category->id;
        }
        $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
        $reportOrderEntryDeliver = WareHouse::getReportSqlWareHouseEntryDeliver($params)->get();

        // Lấy dữ liệu tồn kho trước kỳ
        $param_before_period = $request->except('from_date','to_date');
        $param_before_period['period_before'] = $params['from_date']??null;
        $param_before_period['product_id'] = $reportOrderEntryDeliver->pluck('product_id')->toArray();

        if (isset($request->warehouse_category_id) && $request->warehouse_category_id != '') {
            $warehouse_category = WareHouseCategoryProduct::find($request->warehouse_category_id);
            // Lấy tất cả id các thằng con của nó
            $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
            $param_before_period['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
        }
        $param_before_period['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
        $reportOrderEntryDeliver_truocky=WareHouse::getReportSqlWareHouseEntryDeliver($param_before_period)->get();

        $ton_kho_truoc_ky_map = $reportOrderEntryDeliver_truocky->mapWithKeys(function ($row_truoc_ky) {
            $ton_kho_truoc_ky_quantity = ($row_truoc_ky->nhap_kho_quantity + $row_truoc_ky->dieu_chuyen_nhan_quantity)
                - ($row_truoc_ky->xuat_kho_quantity + $row_truoc_ky->dieu_chuyen_giao_quantity) + $row_truoc_ky->thu_hoi_quantity; // Tính tồn trước kỳ
            return [$row_truoc_ky->product_id => $ton_kho_truoc_ky_quantity]; // Map theo product_id
        });

        // Tính toán số lượng tồn kho trước kỳ
        $rows = $reportOrderEntryDeliver->map(function ($row) use ($request,$ton_kho_truoc_ky_map) {
            $row->ton_kho_quantity = WarehouseService::getTonkho($row->product_id, $request->warehouse_id);//tồn hiện tại
            $row->ton_kho_truoc_ky_quantity = $ton_kho_truoc_ky_map[$row->product_id] ?? 0;//tồn trước kỳ
            $row->ton_kho_trong_ky_quantity = ($row->nhap_kho_quantity + $row->dieu_chuyen_nhan_quantity)
                - ($row->xuat_kho_quantity + $row->dieu_chuyen_giao_quantity) + $row->ton_kho_truoc_ky_quantity + $row->thu_hoi_quantity;//tồn trong kỳ
            return $row;
        });

        // Lấy dữ liệu tồn kho sản phẩm không có nhập xuất kỳ đó
        $param_not_in_period = $request->except('from_date','to_date');
        $param_not_in_period['period_before'] = $params['from_date']??null;
        // dd($param_not_in_period);
        $param_not_in_period['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
        $reportOrderEntryDeliver_truocky2=WareHouse::getReportSqlWareHouseEntryDeliver($param_not_in_period)->whereNotIn('tb_warehouse_entry_detail.product_id',$param_before_period['product_id'])->get();

        // Tính toán số lượng tồn kho đầu kỳ
        // $rows2 = $reportOrderEntryDeliver_truocky2->map(function ($row2) use ($request) {
        //     $row2->ton_kho_quantity = WarehouseService::getTonkho($row2->product_id, $request->warehouse_id);//tồn hiện tại
        //     $row2->ton_kho_truoc_ky_quantity =($row2->nhap_kho_quantity + $row2->dieu_chuyen_nhan_quantity)
        //     - ($row2->xuat_kho_quantity + $row2->dieu_chuyen_giao_quantity);//tồn trước kỳ
        //     $row2->ton_kho_trong_ky_quantity = 0 + $row2->ton_kho_truoc_ky_quantity;//tồn trong kỳ
        //     return $row2;
        // });
        $this->responseData['rows'] = $rows;
        // $this->responseData['rows2'] = $rows2;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = "Tổng hợp xuất nhập kho " . (isset($params['period']) ? ' trong kỳ ' . Carbon::createFromFormat('Y-m', $params['period'])->format('m-Y') : '');
        $params_warehouse['warehouse_permission'] = DataPermissionService::getPermisisonWarehouses(Auth::guard('admin')->user()->id);
        $this->responseData['list_warehouse'] = WareHouse::getSqlWareHouse($params_warehouse)->get();
        $this->responseData['warehouse_type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
        $this->responseData['warehouse_category'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();

        return $this->responseView($this->viewPart . '.report_order_entry_deliver');
    }
    public function export(Request $request)
    {
        $params = $request->all();
        if (isset($params['warehouse_category_id']) && $params['warehouse_category_id'] != '') {
            $warehouse_category = WareHouseCategoryProduct::find($params['warehouse_category_id']);
            // Lấy tất cả id các thằng con của nó
            $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
            $params['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
        }
        $params['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
        $reportOrderEntryDeliver = WareHouse::getReportSqlWareHouseEntryDeliver($params)->get();

        // Lấy dữ liệu tồn kho trước kỳ
        $param_before_period = $request->except('from_date','to_date');
        $param_before_period['period_before'] = $params['from_date']??null;
        $param_before_period['product_id'] = $reportOrderEntryDeliver->pluck('product_id')->toArray();
        if (isset($request->warehouse_category_id) && $request->warehouse_category_id != '') {
            $warehouse_category = WareHouseCategoryProduct::find($request->warehouse_category_id);
            // Lấy tất cả id các thằng con của nó
            $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
            $param_before_period['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
        }
        $param_before_period['entry_permission'] = DataPermissionService::getPermisisonEntryWarehouses(Auth::guard('admin')->user()->id);
        $reportOrderEntryDeliver_truocky=WareHouse::getReportSqlWareHouseEntryDeliver($param_before_period)->get();

        $ton_kho_truoc_ky_map = $reportOrderEntryDeliver_truocky->mapWithKeys(function ($row_truoc_ky) {
            $ton_kho_truoc_ky_quantity = ($row_truoc_ky->nhap_kho_quantity + $row_truoc_ky->dieu_chuyen_nhan_quantity)
                - ($row_truoc_ky->xuat_kho_quantity + $row_truoc_ky->dieu_chuyen_giao_quantity); // Tính tồn trước kỳ
            return [$row_truoc_ky->product_id => $ton_kho_truoc_ky_quantity]; // Map theo product_id
        });
        // Tính toán số lượng tồn kho trước kỳ
        $rows = $reportOrderEntryDeliver->map(function ($row) use ($request,$ton_kho_truoc_ky_map) {
            $row->ton_kho_quantity = WarehouseService::getTonkho($row->product_id, $request->warehouse_id);//tồn hiện tại
            $row->ton_kho_truoc_ky_quantity = $ton_kho_truoc_ky_map[$row->product_id] ?? 0;//tồn trước kỳ
            $row->ton_kho_trong_ky_quantity = ($row->nhap_kho_quantity + $row->dieu_chuyen_nhan_quantity)
                - ($row->xuat_kho_quantity + $row->dieu_chuyen_giao_quantity) + $row->ton_kho_truoc_ky_quantity;//tồn trong kỳ
            return $row;
        });
        return Excel::download(new WarehouseExport($rows), 'reporEntryDeliver.xlsx');
    }
}
