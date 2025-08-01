<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealIngredient;
use App\Models\MealIngredientCategory;
use Illuminate\Http\Request;
use App\Http\Services\MenuPlanningService;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\MealUnit;
use Exception;

class MealIngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'ingredients';
        $this->viewPart = 'admin.pages.meal.ingredients';
        $this->responseData['module_name'] = 'Quản lý thực phẩm';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealIngredient::getSqlIngredient($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $params_active = Consts::STATUS['active'];
        $this->responseData['list_ingredient_categories'] = MealIngredientCategory::getSqlIngredientCategory($params_active)->get();
        $this->responseData['rows'] = $rows;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $params_active = Consts::STATUS['active'];
        $this->responseData['list_ingredient_categories'] = MealIngredientCategory::getSqlIngredientCategory($params_active)->get();
        $this->responseData['list_unit_id'] = MealUnit::getSqlUnit()->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::INGREDIENTS_TYPE;
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ingredient_category_id' => 'required',
            'default_unit_id' => 'required',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();

        MealIngredient::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $mealIngredient = MealIngredient::findOrFail($id);
        $this->responseData['module_name'] = 'Cập nhật thực phẩm';
        $params_active = Consts::STATUS['active'];
        $this->responseData['list_ingredient_categories'] = MealIngredientCategory::getSqlIngredientCategory($params_active)->get();
        $this->responseData['list_unit_id'] = MealUnit::getSqlUnit()->get();
        $this->responseData['detail'] = $mealIngredient;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::INGREDIENTS_TYPE;

        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, $id)
    {
        $mealIngredient = MealIngredient::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'ingredient_category_id' => 'required',
            'default_unit_id' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealIngredient->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        $mealIngredient = MealIngredient::findOrFail($id);
        //kiểm tra xem thực phẩm này có được sử dụng trong thực đơn hàng ngày hay mẫu nào không
        if ($mealIngredient->ingredientdailyMenus()->count() > 0 || $mealIngredient->ingredientplanningMenus()->count() > 0) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Thực phẩm đã có trong thực đơn hàng ngày hoặc mẫu, không thể xóa!'));
        }
        $mealIngredient->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function searchIngredients(Request $request, MenuPlanningService $menuPlanningService)
    {
        try {
            $params_product = $request->all();
            $rows = MealIngredient::getSqlIngredient($params_product)->get();
            foreach ($rows as $row) {
                $row->ingredient_category_name = $row->ingredientCategory ? $row->ingredientCategory->name : '';
                $row->unit_default_name = $row->unitDefault ? $row->unitDefault->name : '';
                $row->ton_kho = $menuPlanningService->calculateIngredientStock($row->id, $request->area_id);
            }
            if ($rows->count() > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
}
