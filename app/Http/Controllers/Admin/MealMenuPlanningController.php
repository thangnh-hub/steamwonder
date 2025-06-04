<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealMenuPlanning;
use App\Models\MealAges;
use App\Models\MealMenuIngredient;
use App\Models\MealMenuDishes;
use App\Models\MealIngredient;
use App\Http\Services\MenuPlanningService;
use App\Models\MealDishes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use Illuminate\Support\Facades\DB;

class MealMenuPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'menu_plannings';
        $this->viewPart = 'admin.pages.meal.menu_plannings';
        $this->responseData['module_name'] = 'Quản lý thực đơn mẫu';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealMenuPlanning::getSqlMenuPlanning($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_season'] = Consts::MEAL_SEASON;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        return  $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'count_student' => 'required',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->id();
        $menu_planning = MealMenuPlanning::create($params);
        $menu_planning->code = 'TDM' . str_pad($menu_planning->id, 5, '0', STR_PAD_LEFT);
        $menu_planning->save();
        return redirect()->route($this->routeDefault . '.edit',$menu_planning->id)->with('successMessage', __('Add new successfully!'));
    }

    public function edit($id)
    {
        $mealmenu = MealMenuPlanning::findOrFail($id);
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_season'] = Consts::MEAL_SEASON;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        $this->responseData['detail'] = $mealmenu;

        $this->responseData['dishes_by_type'] = $mealmenu->menuDishes->groupBy('type');
        $icons = [
            'breakfast' => '🍳',
            'demo_breakfast' => '🍳',
            'lunch'     => '🍛',
            'brunch'    => '🍲',
            'demo_brunch'    => '🍲',
        ];
        $this->responseData['mealTypes'] = collect(Consts::DISHES_TIME)->mapWithKeys(function ($value, $key) use ($icons) {
            $labels = [
                'breakfast' => 'Bữa sáng',
                'demo_breakfast' => 'Bữa phụ sáng',
                'lunch'     => 'Bữa trưa',
                'brunch'    => 'Bữa chiều',
                'demo_brunch' => 'Bữa phụ chiều', // Thêm bữa phụ chiều
            ];
            return [$value => ($icons[$key] ?? '') . ' ' . ($labels[$key] ?? ucfirst($key))];
        });

        return $this->responseView($this->viewPart . '.edit' );
    }
    //xóa món khỏi thực đơn
    public function deleteDish(Request $request)
    {
        $dish = MealMenuDishes::findOrFail($request->dish_id);
        $dish->delete();
        // Tính toán lại nguyên liệu cho thực đơn
        $menuPlanningService = new MenuPlanningService();
        $menuPlanningService->recalculateIngredients($dish->menu_id);
        return redirect()->back()->with('successMessage', 'Xoá món ăn khỏi thực đơn thành công!');
    }

    public function moveDish(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|exists:tb_meal_menu_dishes,id',
            'new_meal_type' => 'required|in:' . implode(',', array_keys(Consts::DISHES_TIME)),
        ]);

        $dish = MealMenuDishes::findOrFail($request->dish_id);

        // Kiểm tra xem món ăn đã có trong bữa mới chưa
        $exists = MealMenuDishes::where('menu_id', $dish->menu_id)
        ->where('dishes_id', $dish->dishes_id)
        ->where('type', $request->new_meal_type)
        ->where('id', '!=', $dish->id)
        ->exists();

        if (!$exists) {
            $dish->type = $request->new_meal_type;
            $dish->save();
        }
        return redirect()->back()->with('successMessage', 'Di chuyển món ăn thành công!');
    }

    //Tìm và thêm món ăn vào thực đơn
    public function searchDishes(Request $request)
    {
        $params['keyword']= $request->input('keyword');
        $params['status'] = Consts::STATUS['active'];
        $query = MealDishes::getSqlDishes($params);

        return response()->json($query->get(['id', 'name']));
    }

    public function addDishes(Request $request, MenuPlanningService $menuPlanningService)
    {
        $request->validate([
            'menu_id' => 'required|exists:tb_meal_menu_planning,id',
            'dishes_ids' => 'required|array',
            'type' => 'required|in:' . implode(',', array_keys(Consts::DISHES_TIME)),
        ]);
        DB::beginTransaction();
        try {
            $duplicates = [];
            $addedCount = 0;

            foreach ($request->dishes_ids as $dish_id) {
                $exists = MealMenuDishes::where('menu_id', $request->menu_id)
                            ->where('dishes_id', $dish_id)
                            ->where('type', $request->type)
                            ->exists();

                if ($exists) {
                    $dishName = MealDishes::find($dish_id)->name ?? 'ID ' . $dish_id;
                    $duplicates[] = $dishName;
                    continue;
                }

                MealMenuDishes::create([
                    'menu_id' => $request->menu_id,
                    'dishes_id' => $dish_id,
                    'type' => $request->type,
                    'status' => 'active',
                    'admin_created_id' => auth('admin')->id(),
                ]);
                $addedCount++;
            }
            
            // Tạo thông báo flash session
            $message = "Đã thêm $addedCount món ăn vào thực đơn.";
            if (!empty($duplicates)) {
                $message .= ' Các món đã tồn tại: ' . implode(', ', $duplicates) . '.';
            }

            // Tính toán lại nguyên liệu cho thực đơn
            $menuPlanningService->recalculateIngredients($request->menu_id);
            DB::commit();
            return redirect()->back()->with('successMessage', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Đã xảy ra lỗi khi thêm món ăn: ' . $e->getMessage());
        }
    }

    //Tìm và thêm nguyên liệu vào thực đơn
    public function searchIngredients(Request $request)
    {
        $params['keyword']= $request->input('keyword');
        $params['status'] = Consts::STATUS['active'];
        $query = MealIngredient::getSqlIngredient($params);

        return response()->json($query->get(['id', 'name']));
    }

    public function addIngredients(Request $request, MenuPlanningService $menuPlanningService)
    {
        $request->validate([
            'menu_id' => 'required|exists:tb_meal_menu_planning,id',
            'ingredient_ids' => 'required|array',
        ]);

        $menuId = $request->menu_id;
        $countStudent = MealMenuPlanning::find($menuId)->count_student ?? 1;

        DB::beginTransaction();
        try {
            foreach ($request->ingredient_ids as $ingredientId) {
                $perChild = floatval($request->ingredient_values[$ingredientId]);
                $totalValue = $perChild * $countStudent;

                // Kiểm tra trùng
                $exists = MealMenuIngredient::where('menu_id', $menuId)
                            ->where('ingredient_id', $ingredientId)
                            ->exists();
                if ($exists) continue;

                MealMenuIngredient::create([
                    'menu_id' => $menuId,
                    'ingredient_id' => $ingredientId,
                    'value' => $totalValue,
                    'admin_created_id' => auth('admin')->id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('successMessage', 'Đã thêm nguyên liệu vào thực đơn.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Lỗi: ' . $e->getMessage());
        }

    }

    public function updateIngredients(Request $request, $menuId)
    {
        $menu = MealMenuPlanning::findOrFail($menuId);
        $countStudent = max($menu->count_student, 1);
        $input = $request->input('ingredients', []);

        foreach ($input as $id => $valuePerOne) {
            $ingredient = MealMenuIngredient::find($id);

            if ($ingredient && $ingredient->menu_id == $menu->id) {
                $ingredient->value = round(floatval($valuePerOne) * $countStudent, 2);
                $ingredient->admin_updated_id = auth('admin')->id();
                $ingredient->save();
            }
        }

        return back()->with('successMessage', 'Cập nhật định lượng thành công!');
    }

    public function update(Request $request, $id)
    {
        $mealmenu = MealMenuPlanning::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'count_student' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealmenu->update($params);
        // Tính toán lại nguyên liệu cho thực đơn
        $menuPlanningService = new MenuPlanningService();
        $menuPlanningService->recalculateIngredients($mealmenu->id);
        return redirect()->back()->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $menu = MealMenuPlanning::with('dailyMenus')->findOrFail($id);

            if ($menu->dailyMenus->count() > 0) {
                throw new \Exception('Không thể xóa vì thực đơn đã được áp dụng cho thực đơn hàng ngày.');
            }
            $menu->menuDishes()->delete();
            $menu->menuIngredients()->delete();
            $menu->delete();
            DB::commit();
            return redirect()->back()->with('successMessage', 'Đã xóa thực đơn mẫu thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

}
