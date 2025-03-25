<?php

namespace App\Http\Controllers\Admin;

use App\Models\WareHouseCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;

class WareHouseCategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'warehouse_category_product';
        $this->viewPart = 'admin.pages.warehouse_category_product';
        $this->responseData['module_name'] = 'Quản lý danh mục tài sản';
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct($params)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] =  $params;
        $this->responseData['status'] =  Consts::STATUS;
        $this->responseData['type'] =  Consts::WAREHOUSE_PRODUCT_TYPE;
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
        $this->responseData['categorys'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
        $params=Consts::STATUS['active'];
        $this->responseData['type'] =  Consts::WAREHOUSE_PRODUCT_TYPE;
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
            'code' => "unique:tb_warehouse_category_product",
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $createWarehouse = WareHouseCategoryProduct::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $wareHouseCategoryProduct=WareHouseCategoryProduct::find($id);
        $this->responseData['status'] = Consts::STATUS;
        $params=Consts::STATUS['active'];
        $this->responseData['detail'] = $wareHouseCategoryProduct;
        $this->responseData['categorys'] = WareHouseCategoryProduct::getSqlWareHouseCategoryProduct()->get();
        $this->responseData['type'] =  Consts::WAREHOUSE_PRODUCT_TYPE;

        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, $id)
    {
        $wareHouseCategoryProduct=WareHouseCategoryProduct::find($id);
        $params=$request->all();
        $request->validate([
            'name' => 'required',
            'code' => "unique:tb_warehouse_category_product,code," . $wareHouseCategoryProduct->id,
        ]);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $wareHouseCategoryProduct->fill($params);
        $wareHouseCategoryProduct->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function destroy($id)
    {
        $wareHouseCategoryProduct=WareHouseCategoryProduct::find($id);
        $wareHouseCategoryProduct->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
