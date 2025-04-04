<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseProduct;
use App\Models\WareHouseCategoryProduct;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Http\Services\WarehouseService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WareHouseProductImport;
use App\Imports\WareHouseProductImportAsset;

class WareHouseProductController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'warehouse_product';
    $this->viewPart = 'admin.pages.warehouse_product';
    $this->responseData['module_name'] = 'Quản lý sản phẩm';
  }
  public function index(Request $request)
  {
    $params = $request->all();
    $this->responseData['params'] =  $params;
    // Get list post with filter params
    if (isset($params['warehouse_category_id']) && $params['warehouse_category_id'] != '') {
      $warehouse_category = WareHouseCategoryProduct::find($params['warehouse_category_id']);
      // Lấy tất cả id các thằng con của nó
      $childIds = WarehouseService::getAllChildrenIds($warehouse_category);
      $params['warehouse_category_id'] = array_merge([$warehouse_category->id], $childIds);
    }

    $rows = WareHouseProduct::getSqlWareHouseProduct($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;

    $this->responseData['status'] =  Consts::STATUS;
    $this->responseData['list_type'] = Consts::WAREHOUSE_PRODUCT_TYPE;

    $this->responseData['list_category'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
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
    $this->responseData['list_type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
    $this->responseData['list_category'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
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
      'warehouse_category_id' => 'required',
      'code' => "required|unique:tb_warehouse_product",
    ]);
    $params = $request->all();
    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    WareHouseProduct::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  public function edit($id)
  {
    $warehouseproduct = WareHouseProduct::find($id);
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['list_type'] = Consts::WAREHOUSE_PRODUCT_TYPE;
    $this->responseData['list_category'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
    $this->responseData['detail'] = $warehouseproduct;
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $warehouseproduct = WareHouseProduct::find($id);
    $params = $request->all();
    $request->validate([
      'name' => 'required',
      'warehouse_category_id' => 'required',
      'code' => "required|unique:tb_warehouse_product,code," . $warehouseproduct->id,
    ]);
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
    $warehouseproduct->fill($params);
    $warehouseproduct->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $warehouseproduct = WareHouseProduct::find($id);
    $warehouseproduct->delete();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }

  /**
   * Import danh sách sản phẩm ban đầu
   */
  public function importProduct(Request $request)
  {
    $params = $request->all();
    if (isset($params['file'])) {
      if ($this->checkFileImport($params['file']) == false) {
        $_datawith = 'errorMessage';
        $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
        session()->flash($_datawith, $mess);
        return $this->sendResponse($_datawith, $mess);
      }
      $_datawith = 'successMessage';
      $import = new WareHouseProductImport($params);
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
      session()->flash($_datawith, $mess);
      return $this->sendResponse($_datawith, $mess);
    }
    session()->flash('errorMessage', __('Cần chọn file để Import!'));
    return $this->sendResponse('warning', __('Cần chọn file để Import!'));
  }
  /** Import tài sản theo đúng mã quy ước*/
  public function importAsset(Request $request)
  {
    $params = $request->all();
    if (isset($params['file'])) {
      if ($this->checkFileImport($params['file']) == false) {
        $_datawith = 'errorMessage';
        $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
        session()->flash($_datawith, $mess);
        return $this->sendResponse($_datawith, $mess);
      }
      $_datawith = 'successMessage';
      $import = new WareHouseProductImportAsset($params);
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
      session()->flash($_datawith, $mess);
      return $this->sendResponse($_datawith, $mess);
    }
    session()->flash('errorMessage', __('Cần chọn file để Import!'));
    return $this->sendResponse('warning', __('Cần chọn file để Import!'));
  }
}
