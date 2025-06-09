<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealDishes;
use App\Imports\DishesImport;
use App\Models\MealAges;
use App\Models\MealIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Consts;

class MealDishesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'dishes';
        $this->viewPart = 'admin.pages.meal.dishes';
        $this->responseData['module_name'] = 'Quản lý Món ăn';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealDishes::getSqlDishes($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::DISHES_TYPE;
        $this->responseData['list_time'] = Consts::DISHES_TIME;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::DISHES_TYPE;
        $this->responseData['list_time'] = Consts::DISHES_TIME;
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();
        $dishes = MealDishes::create($params);
        return redirect()->route($this->routeDefault . '.edit',$dishes->id)->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $mealDishes = MealDishes::findOrFail($id);
        $this->responseData['module_name'] = 'Cập nhật món ăn';
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_type'] = Consts::DISHES_TYPE;
        $this->responseData['list_time'] = Consts::DISHES_TIME;
        $this->responseData['detail'] = $mealDishes;

        //Nguyên liệu
        //Nhóm tuổi
        
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();

        //thực phẩm
        $this->responseData['list_ingredient'] = MealIngredient::getSqlIngredient(Consts::STATUS['active'])->get();

        return $this->responseView($this->viewPart . '.edit' );
    }

    public function update(Request $request, $id)
    {
        $mealIngredient = MealDishes::findOrFail($id);
        $request->validate([
            'name' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealIngredient->update($params);

        return redirect()->back()->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        $mealIngredient = MealDishes::findOrFail($id);
        $mealIngredient->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function importDataDishes(Request $request)
    {
        $params = $request->all();
        // Kiểm tra và validate file đầu vào
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
 
        if (!isset($params['file'])) {
            return redirect()->back()->with('errorMessage', __('Cần chọn file để Import!'));
        }

        try {
            // Import file
            $import = new DishesImport($params);
            Excel::import($import, request()->file('file'));

            return redirect()->back()->with('successMessage', 'Import data thành công');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Lỗi tại dòng " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->back()->with('errorMessage', implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            // Bắt lỗi chung khác
            return redirect()->back()->with('errorMessage', 'Lỗi khi import: ' . $e->getMessage());
        }
    }
    
}
