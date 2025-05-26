<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealSupplier;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;

class MealSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'suppliers';
        $this->viewPart = 'admin.pages.suppliers';
        $this->responseData['module_name'] = 'Quản lý nhà cung cấp';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealSupplier::getSqlSupplier($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;

        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:tb_meal_suppliers,code',
            'phone' => 'nullable|unique:tb_meal_suppliers,phone',
            'area_id' => 'required'
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();

        MealSupplier::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit(MealSupplier $supplier)
    {
        $this->responseData['detail'] = $supplier;

        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, MealSupplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|unique:tb_meal_suppliers,phone,' . $supplier->id,
            'code' => 'unique:tb_meal_suppliers,code,' . $supplier->id,
            'area_id' => 'required'
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();

        $supplier->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy(MealSupplier $supplier)
    {
        $supplier->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

}
