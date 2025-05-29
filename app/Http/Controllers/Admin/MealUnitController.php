<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\MealUnit;


class MealUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'units';
        $this->viewPart = 'admin.pages.meal.units';
        $this->responseData['module_name'] = 'Quản lý đơn vị thực phẩm';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealUnit::getSqlUnit($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tb_meal_units,name',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();
        MealUnit::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit(MealUnit $unit)
    {
        $this->responseData['detail'] = $unit;
        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, MealUnit $unit)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $params = $request->all();
        $params['is_base'] =  $request->is_base ?? '0';
        $params['admin_updated_id'] = Auth::guard('admin')->id();

        $unit->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy(MealUnit $unit)
    {
        $unit->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
