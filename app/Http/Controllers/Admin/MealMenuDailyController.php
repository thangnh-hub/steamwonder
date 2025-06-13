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
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use Carbon\CarbonPeriod;

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
        // $this->generateDailyMenus('2025-06-01', '2025-06-30', 3, 1); 

        $permisson_area_id = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        if (empty($permisson_area_id)) {
            $permisson_area_id = [-1];
        }
        $params = $request->all();
        $params['status'] = Consts::STATUS['active'];
        $params['permisson_area_id'] = $permisson_area_id;
        $params['order_by'] = 'area_id';
        $rows = MealMenuDaily::getSqlMenuDaily($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_meal_age'] = MealAges::getSqlMealAge(Consts::STATUS['active'])->get();
        $this->responseData['menuPlannings'] = MealMenuPlanning::getSqlMenuPlanning(Consts::STATUS['active'])->get();
        $this->responseData['params'] = $params;

        $params_area['id'] = $permisson_area_id;
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
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
            $weekday = ucfirst($date->translatedFormat('l'));
            $name = 'Thực đơn ' . $weekday . ' - ' . $date->format('d/m/Y'). ' - ' . $template->mealAge->name ?? "";
            $dailyMenu = MealMenuDaily::create([
                'meal_menu_planning_id'   => $template->id,
                'date'               => $request->date,
                'area_id'               => $request->area_id,
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
        $admin = Auth::guard('admin')->user();
        $permittedAreaIds = DataPermissionService::getPermisisonAreas($admin->id);
        if (!in_array($mealmenu->area_id, $permittedAreaIds)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Thực đơn không thuộc khu vực quản lý!'));
        }

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
        $admin = Auth::guard('admin')->user();
        $permittedAreaIds = DataPermissionService::getPermisisonAreas($admin->id);
        if (!in_array($menu->area_id, $permittedAreaIds)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Thực đơn không thuộc khu vực quản lý!'));
        }
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
        $admin = Auth::guard('admin')->user();
        $permittedAreaIds = DataPermissionService::getPermisisonAreas($admin->id);
        if (!in_array($mealmenu->area_id, $permittedAreaIds)) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Thực đơn không thuộc khu vực quản lý!'));
        }
        $request->validate([
            'name' => 'required',
            // 'count_student' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->id();
        $mealmenu->update($params);
        // $menuPlanningService = new MenuPlanningService();
        // $menuPlanningService->recalculateIngredientsDaily($mealmenu->id);
        return redirect()->back()->with('successMessage', __('Update successfully!'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $dailyMenu = MealMenuDaily::findOrFail($id);
            $admin = Auth::guard('admin')->user();
            $permittedAreaIds = DataPermissionService::getPermisisonAreas($admin->id);
            if (!in_array($dailyMenu->area_id, $permittedAreaIds)) {
                return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Thực đơn không thuộc khu vực quản lý!'));
            }
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
    public function reportByDay(Request $request)
    {
        $params = $request->all();
        if (!empty($params['month'])) {
            $selectedDate = Carbon::createFromFormat('Y-m', $params['month']);
            $month = $selectedDate->month;
            $year = $selectedDate->year;
        } else {
            $month = now()->month;
            $year = now()->year;
        }
        //Lấy danh sách khu vực theo quyền của người dùng
        $permisson_area_id = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        // Nếu người dùng chọn khu vực cụ thể, chỉ lấy khu vực đó
        $filter_area_ids = $permisson_area_id;
        if (!empty($params['area_id'])) {
            $filter_area_ids = [$params['area_id']];
        }

        // Lọc dữ liệu theo tháng và năm
        $menus = MealMenuDaily::with(['mealAge', 'area'])
            ->where('status', Consts::STATUS['active'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('area_id', $filter_area_ids)
            ->orderBy('date', 'asc')
            ->get();

        // Group theo ngày → theo khu vực
        $menusGrouped = $menus->groupBy([
            fn($item) => $item->date,
            fn($item) => $item->area_id,
        ]);

        $this->responseData['menusGrouped'] = $menusGrouped;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.report_by_day');
    }

    //Hamf show thực đơn theo ngày
    public function showByDate($date, $area_id)
    {
        $query = MealMenuDaily::with([
            'mealAge',
            'menuDishes.dishes',
            'menuIngredients.ingredients',
            'area'
        ])
        ->whereDate('date', $date);
        if ($area_id) {
            $query->where('area_id', $area_id);
        }
        $menus = $query->get();
        if ($menus->isEmpty()) {
            return redirect()->back()->with('error', 'Không có thực đơn cho ngày này');
        }

        $groupedIngredients = [];
        foreach ($menus as $menu) {
            foreach ($menu->menuIngredients as $item) {
                if (!$item->ingredients) continue;

                $ingredient = $item->ingredients;
                $type = $ingredient->type ?? 'Chưa xác định';
                $id = $ingredient->id;

                if (!isset($groupedIngredients[$type][$id])) {
                    $groupedIngredients[$type][$id] = [
                        'ingredient' => $ingredient,
                        'total' => 0,
                        'count_student' => 0,
                    ];
                }

                $groupedIngredients[$type][$id]['total'] += $item->value;
                $groupedIngredients[$type][$id]['count_student'] += $menu->count_student;
            }
        }
        $this->responseData['date'] = $date;
        $this->responseData['menus'] = $menus;
        $this->responseData['groupedIngredients'] = $groupedIngredients;
        $this->responseData['module_name'] = "Tổng hợp thực phẩm ngày " . Carbon::parse($date)->format('d/m/Y'). ' - ' . ($area_id ? Area::find($area_id)->name : '');

        return $this->responseView($this->viewPart . '.show_by_date');
    }

    
    public function reportByWeek(Request $request)
    {
        $week = $request->input('week') ?? now()->format('o-\WW');
        [$year, $weekNumber] = explode('-W', $week);
        $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        $endOfWeek = (clone $startOfWeek)->endOfWeek();

        // Lấy khu vực theo quyền
        $permisson_area_id = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        // Lấy thực đơn trong tuần
        $query = MealMenuDaily::with(['menuDishes.dishes', 'mealAge', 'area'])
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->whereIn('area_id', $permisson_area_id);

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $menus = $query->get();
        // Group dữ liệu dạng: date → area_id → [name, data → age → type → dishes]
        $menusGrouped = $menus->groupBy('date')->map(function ($dailyMenus) {
            return $dailyMenus->groupBy(function ($menu) {
                return optional($menu->mealAge)->name; // group theo nhóm tuổi
            })->map(function ($ageGroup) {
                $mealTypes = [];

                foreach ($ageGroup as $menu) {
                    foreach ($menu->menuDishes as $menuDish) {
                        $type = $menuDish->type ?? 'unknown';
                        $mealTypes[$type][] = $menuDish->dishes;
                    }
                }

                return collect($mealTypes);
            });
        });

        //Xử lý view theo age
        $menusGroupedByAge = [];
        $dishesTime = Consts::DISHES_TIME;
        foreach ($menus as $menu) {
            $ageName = optional($menu->mealAge)->name ?? 'Không rõ';
            $date = $menu->date;

            foreach ($dishesTime as $type) {
                $menusGroupedByAge[$ageName][$type][$date] = []; 
            }

            foreach ($menu->menuDishes as $menuDish) {
                $type = $menuDish->type ?? 'unknown';
                if (!in_array($type, $dishesTime)) continue;

                $menusGroupedByAge[$ageName][$type][$date][] = $menuDish->dishes;
            }
        }

        ksort($menusGroupedByAge); // Sắp xếp theo tên nhóm tuổi

        $this->responseData['menusGroupedByAge'] = $menusGroupedByAge;
        $this->responseData['dishesTime'] = $dishesTime;

        // $daysInWeek = CarbonPeriod::create($startOfWeek, $endOfWeek);
        $daysInWeek = CarbonPeriod::create($startOfWeek, $endOfWeek)->filter(function ($date) {
            return $date->dayOfWeek >= Carbon::MONDAY && $date->dayOfWeek <= Carbon::FRIDAY;
        });
        $this->responseData['params'] = $request->all();
        $this->responseData['menusGrouped'] = $menusGrouped;
        $this->responseData['daysInWeek'] = $daysInWeek;
        $this->responseData['week'] = $week;
        $this->responseData['module_name'] = "Báo cáo thực đơn theo tuần $weekNumber năm $year (Từ " . $startOfWeek->format('d/m/Y') . " đến " . $endOfWeek->format('d/m/Y') . ")";

        //Bổ sung
        $this->responseData['show_report'] = $request->filled('area_id') && $request->filled('week');
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $this->responseData['selected_month'] = $selectedMonth;

        $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        $weeksInMonth = [];
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        foreach ($period as $date) {
            $weekNumber = $date->isoWeek;
            $yearOfWeek = $date->isoWeekYear;

            $key = $yearOfWeek . '-W' . str_pad($weekNumber, 2, '0', STR_PAD_LEFT);
            if (!isset($weeksInMonth[$key])) {
                $startOfWeek = Carbon::now()->setISODate($yearOfWeek, $weekNumber)->startOfWeek();
                $endOfWeek = (clone $startOfWeek)->endOfWeek();

                $weeksInMonth[$key] = [
                    'label' => "Tuần $weekNumber (" . $startOfWeek->format('d/m') . " - " . $endOfWeek->format('d/m') . ")",
                    'value' => $key
                ];
            }
        }
        $this->responseData['currentYearWeeks'] = collect(array_values($weeksInMonth));
        return $this->responseView($this->viewPart . '.report_by_week');
    }

    
    public function generateDailyMenus($fromDate, $toDate, $mealAgeId, $areaId)
    {
        $adminId = Auth::guard('admin')->id();
        $from = Carbon::parse($fromDate);
        $to = Carbon::parse($toDate);

        // Danh sách ngày (trừ thứ 7 & chủ nhật)
        $dates = [];
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $dates[] = $date->copy();
            }
        }
        // Lấy danh sách thực đơn mẫu phù hợp với nhóm tuổi & cơ sở
        $planningMenus = MealMenuPlanning::with(['menuDishes', 'menuIngredients'])
            ->where('meal_age_id', $mealAgeId)
            ->where('status', 'active')
            ->get();
        if ($planningMenus->isEmpty()) {
            return redirect()->back()->with('errorMessage', 'Không tìm thấy thực đơn mẫu phù hợp.');
        }

        DB::beginTransaction();
        try {
            foreach ($dates as $index => $date) {
                // Kiểm tra xem ngày đã có thực đơn chưa
                $existing = MealMenuDaily::where('meal_age_id', $mealAgeId)
                    ->where('area_id', $areaId)
                    ->whereDate('date', $date->toDateString())
                ->first();

                if ($existing) {
                    continue; // Bỏ qua ngày đã có thực đơn
                }
                // Chọn thực đơn mẫu lần lượt
                $planningMenu = $planningMenus[$index % $planningMenus->count()];
                $weekday = ucfirst($date->translatedFormat('l'));
                $name_daily = 'Thực đơn ' . $weekday . ' - ' . $date->format('d/m/Y'). ' - ' . $planningMenu->mealAge->name ?? "";
                $dailyMenu = MealMenuDaily::create([
                    'meal_menu_planning_id' => $planningMenu->id,
                    'meal_age_id' => $mealAgeId,
                    'area_id' => $areaId,
                    'date' => $date->toDateString(),
                    'name' => $name_daily,
                    'description' => $planningMenu->description,
                    'count_student' => $planningMenu->count_student,
                    'json_params' => $planningMenu->json_params,
                    'status' => 'active',
                    'admin_created_id' => $adminId,
                    'admin_updated_id' => $adminId,
                ]);
                $dailyMenu->code = 'TD' . str_pad($dailyMenu->id, 5, '0', STR_PAD_LEFT);
                $dailyMenu->save();

                // Chuẩn bị dữ liệu món ăn
                $dishData = $planningMenu->menuDishes->map(function ($dish) use ($dailyMenu, $adminId) {
                    return [
                        'menu_daily_id' => $dailyMenu->id,
                        'dishes_id' => $dish->dishes_id,
                        'json_params' => $dish->json_params,
                        'type' => $dish->type,
                        'status' => 'active',
                        'admin_created_id' => $adminId,
                        'admin_updated_id' => $adminId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
                // Chuẩn bị dữ liệu nguyên liệu
                $ingredientData = $planningMenu->menuIngredients->map(function ($ingredient) use ($dailyMenu, $adminId) {
                    return [
                        'menu_daily_id' => $dailyMenu->id,
                        'ingredient_id' => $ingredient->ingredient_id,
                        'value' => $ingredient->value,
                        'json_params' => $ingredient->json_params,
                        'status' => 'active',
                        'admin_created_id' => $adminId,
                        'admin_updated_id' => $adminId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
                if (!empty($dishData)) {
                    MealMenuDishesDaily::insert($dishData);
                }
                if (!empty($ingredientData)) {
                    MealMenuIngredientDaily::insert($ingredientData);
                }
            }

            DB::commit();
            return redirect()->back()->with('successMessage', 'Tạo thực đơn hàng ngày thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('errorMessage', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
