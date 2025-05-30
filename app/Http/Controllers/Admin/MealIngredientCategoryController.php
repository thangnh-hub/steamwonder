<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealIngredientCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;

class MealIngredientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'ingredients_category';
        $this->viewPart = 'admin.pages.meal.ingredients_category';
        $this->responseData['module_name'] = 'Danh mục thực phẩm';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealIngredientCategory::getSqlIngredientCategory($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::INGREDIENTS_TYPE;
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();

        MealIngredientCategory::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $mealIngredientCategory = MealIngredientCategory::findOrFail($id);
        $this->responseData['module_name'] = 'Cập nhật danh mục thực phẩm';
        $this->responseData['detail'] = $mealIngredientCategory;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::INGREDIENTS_TYPE;

        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, $id)
    {
        $mealIngredientCategory = MealIngredientCategory::findOrFail($id);
        $request->validate([
            'name' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealIngredientCategory->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        $mealIngredientCategory = MealIngredientCategory::findOrFail($id);
        $usedCount = $mealIngredientCategory->mealIngredients()->count();
        if ($usedCount > 0) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', 'Không thể xóa danh mục vì đang có thực phẩm sử dụng danh mục này.');
        }
        $mealIngredientCategory->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
