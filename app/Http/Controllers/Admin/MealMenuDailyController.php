<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealMenuDaily;
use App\Models\MealAges;
use App\Models\MealDishes;
use App\Models\MealMenuDishes;
use App\Models\MealMenuDishesDaily;
use App\Models\MealMenuPlanning; 
use App\Models\MealMenuIngredient;
use App\Models\MealMenuIngredientDaily;
use App\Http\Services\MenuPlanningService;
use Illuminate\Http\Request;
use App\Consts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MealMenuDailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'menu_dailys';
        $this->viewPart = 'admin.pages.meal.menu_dailys';
        $this->responseData['module_name'] = 'Quản lý thực đơn hàng ngày';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = MealMenuDaily::getSqlMenuDaily($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        $this->responseData['menuPlannings'] = MealMenuPlanning::getSqlMenuPlanning(Consts::STATUS['active'])->get();
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function createFromTemplate(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Tạo thực đơn hàng ngày
            $template = MealMenuPlanning::findOrFail($request->meal_menu_planning_id);
            Carbon::setLocale('vi');
            $date = Carbon::parse($request->date);
            $weekday = ucfirst($date->translatedFormat('l')); // VD: "Thứ hai"
            $name = 'Thực đơn ' . $weekday . ' - ' . $date->format('d/m/Y');
            $dailyMenu = MealMenuDaily::create([
                'meal_menu_planning_id'   => $template->id,
                'date'               => $request->date,
                'name'              => $name,
                'description'        => $template->description,
                'count_student'      => $template->count_student,
                'meal_age_id'        => $template->meal_age_id,
                'season'            => $template->season,
                'status'             => Consts::STATUS['active'],
                'admin_created_id'   => Auth::guard('admin')->id(),
            ]);
            $dailyMenu->code = 'TD' . str_pad($dailyMenu->id, 5, '0', STR_PAD_LEFT);
            $dailyMenu->save();

            // 2. Lấy danh sách món ăn từ thực đơn mẫu
            $templateDishes = MealMenuDishes::where('menu_id', $request->meal_menu_planning_id)->get();
            $insertDishes = [];
            foreach ($templateDishes as $dish) {
                $insertDishes[] = [
                    'menu_daily_id'     => $dailyMenu->id,
                    'dishes_id'         => $dish->dishes_id,
                    'type'              => $dish->type,
                    'status'            => $dish->status,
                    'admin_created_id'  => Auth::guard('admin')->id(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
            MealMenuDishesDaily::insert($insertDishes);

            // 3. Lấy danh sách nguyên liệu từ thực đơn mẫu
            $templateIngredients = MealMenuIngredient::where('menu_id', $request->meal_menu_planning_id)->get();
            $insertIngredientData = [];
            foreach ($templateIngredients as $ingredient) {
                $insertIngredientData[] = [
                    'menu_daily_id'     => $dailyMenu->id,
                    'ingredient_id'     => $ingredient->ingredient_id,
                    'value'             => $ingredient->value,
                    'status'            => $ingredient->status,
                    'admin_created_id'  => Auth::guard('admin')->id(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
            MealMenuIngredientDaily::insert($insertIngredientData);

            DB::commit();

            return redirect()->back()->with('successMessage', 'Tạo thực đơn hàng ngày thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $mealmenu = MealMenuDaily::findOrFail($id);

        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_season'] = Consts::MEAL_SEASON;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        $this->responseData['detail'] = $mealmenu;
        $this->responseData['dishes_by_type'] = $mealmenu->menuDishes->groupBy('type');
        $icons = [
            'breakfast' => '🍳',
            'lunch'     => '🍛',
            'brunch'    => '🍲',
        ];
        $this->responseData['mealTypes'] = collect(Consts::DISHES_TIME)->mapWithKeys(function ($value, $key) use ($icons) {
            $labels = [
                'breakfast' => 'Bữa sáng',
                'lunch'     => 'Bữa trưa',
                'brunch'    => 'Bữa chiều',
            ];
            return [$value => ($icons[$key] ?? '') . ' ' . ($labels[$key] ?? ucfirst($key))];
        });
        return $this->responseView($this->viewPart . '.edit' );
    }

    //xóa món khỏi thực đơn
    public function deleteDish(Request $request)
    {
        $dish = MealMenuDishesDaily::findOrFail($request->dish_id);
        $dish->delete();
        // Tính toán lại nguyên liệu cho thực đơn
        $menuPlanningService = new MenuPlanningService();
        $menuPlanningService->recalculateIngredientsDaily($dish->menu_daily_id);
        return redirect()->back()->with('successMessage', 'Xoá món ăn khỏi thực đơn thành công!');
    }

    public function moveDish(Request $request)
    {
        $dish = MealMenuDishesDaily::findOrFail($request->dish_id);
        // Kiểm tra xem món ăn đã có trong bữa mới chưa
        $exists = MealMenuDishesDaily::where('menu_daily_id', $dish->menu_daily_id)
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
        DB::beginTransaction();
        try {
            $duplicates = [];
            $addedCount = 0;

            foreach ($request->dishes_ids as $dish_id) {
                $exists = MealMenuDishesDaily::where('menu_daily_id', $request->menu_daily_id)
                            ->where('dishes_id', $dish_id)
                            ->where('type', $request->type)
                            ->exists();

                if ($exists) {
                    $dishName = MealDishes::find($dish_id)->name ?? 'ID ' . $dish_id;
                    $duplicates[] = $dishName;
                    continue;
                }

                MealMenuDishesDaily::create([
                    'menu_daily_id' => $request->menu_daily_id,
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
            $menuPlanningService->recalculateIngredientsDaily($request->menu_daily_id);
            DB::commit();
            return redirect()->back()->with('successMessage', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Đã xảy ra lỗi khi thêm món ăn: ' . $e->getMessage());
        }
    }

    public function updateIngredients(Request $request, $menuId)
    {
        $menu = MealMenuDaily::findOrFail($menuId);
        $countStudent = max($menu->count_student, 1);
        $input = $request->input('ingredients', []);

        foreach ($input as $id => $valuePerOne) {
            $ingredient = MealMenuIngredientDaily::find($id);

            if ($ingredient && $ingredient->menu_daily_id == $menu->id) {
                $ingredient->value = round(floatval($valuePerOne) * $countStudent, 2);
                $ingredient->admin_updated_id = auth('admin')->id();
                $ingredient->save();
            }
        }

        return back()->with('successMessage', 'Cập nhật định lượng thành công!');
    }

    public function update(Request $request, $id)
    {
        $mealmenu = MealMenuDaily::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'count_student' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealmenu->update($params);
        // Tính toán lại nguyên liệu cho thực đơn
        $menuPlanningService = new MenuPlanningService();
        $menuPlanningService->recalculateIngredientsDaily($mealmenu->id);
        return redirect()->back()->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $dailyMenu = MealMenuDaily::findOrFail($id);
            $dailyMenu->menuDishes()->delete();
            $dailyMenu->menuIngredients()->delete();
            $dailyMenu->delete();
            DB::commit();
            return redirect()->back()->with('successMessage', 'Xóa thực đơn hàng ngày thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }


}
