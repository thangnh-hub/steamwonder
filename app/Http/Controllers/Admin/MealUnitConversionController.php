<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MealUnitConversion;
use App\Models\MealUnit;
use App\Consts;
use Illuminate\Validation\Rule;

class MealUnitConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'unit_conversions';
        $this->viewPart = 'admin.pages.meal.unit_conversions';
        $this->responseData['module_name'] = 'Chuyển đổi đơn vị';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealUnitConversion::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {   
        $this->responseData['list_units'] = MealUnit::all();
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
       $request->validate([
            'from_unit_id' => [
                'required',
                Rule::unique('tb_meal_unit_conversions')
                    ->where(fn ($query) => $query->where('to_unit_id', $request->to_unit_id)),
            ],
            'to_unit_id' => 'required|exists:tb_meal_units,id|different:from_unit_id',
            'ratio' => 'required|numeric|min:0.0001',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();
        MealUnitConversion::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $unit = MealUnitConversion::findOrFail($id);
        $this->responseData['detail'] = $unit;
        $this->responseData['list_units'] = MealUnit::all();
        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, $id)
    {
        $unit = MealUnitConversion::findOrFail($id);
        // Validate the request
        $request->validate([
            'from_unit_id' => [
                'required',
                Rule::unique('tb_meal_unit_conversions')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('to_unit_id', $request->to_unit_id)),
            ],
            'to_unit_id' => 'required|exists:tb_meal_units,id|different:from_unit_id',
            'ratio' => 'required|numeric|min:0.0001',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();

        $unit->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        $unit = MealUnitConversion::findOrFail($id);
        $unit->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
       
}
