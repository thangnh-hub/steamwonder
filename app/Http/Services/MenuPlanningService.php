<?php

namespace App\Http\Services;

use App\Models\MealMenuIngredient;
use App\Models\MealMenuIngredientDaily;
use App\Models\MealWarehouseIngredient;
use App\Models\MealMenuPlanning;
use App\Models\MealMenuDaily;
use App\Models\MealMenuDishes;
use App\Models\MealMenuDishesDaily;
use App\Models\MealWareHouseEntry;
use Illuminate\Support\Facades\DB;

class MenuPlanningService
{
    public function recalculateIngredients($menu_id)
    {
        try {
            DB::beginTransaction();
            MealMenuIngredient::where('menu_id', $menu_id)->delete();
            $menu = MealMenuPlanning::findOrFail($menu_id);
            $mealAgeCode = optional($menu->mealAge)->code; 
            $countStudent = (float)$menu->count_student;
            if (!$mealAgeCode || $countStudent <= 0) {
                throw new \Exception('Không có mã nhóm tuổi hoặc số lượng học sinh không hợp lệ.');
            }

            // Lấy các món ăn trong thực đơn
            $menuDishes = MealMenuDishes::with('dishes')->where('menu_id', $menu_id)->get();
            $ingredientTotals = [];

            foreach ($menuDishes as $menuDish) {
                $dish = $menuDish->dishes;
                if (!$dish || empty($dish->quantitative)) {
                    continue;
                }

                foreach ($dish->quantitative as $ingredientId => $ageQuantities) {
                    if (!isset($ageQuantities[$mealAgeCode])) {
                        continue;
                    }

                    $qtyPerStudent = (float) $ageQuantities[$mealAgeCode];
                    $totalQty = $qtyPerStudent * $countStudent;

                    if (!isset($ingredientTotals[$ingredientId])) {
                        $ingredientTotals[$ingredientId] = 0;
                    }
                    $ingredientTotals[$ingredientId] += $totalQty;
                }
            }

            foreach ($ingredientTotals as $ingredientId => $total) {
                MealMenuIngredient::create([
                    'menu_id'       => $menu_id,
                    'ingredient_id' => $ingredientId,
                    'value'         => $total,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function recalculateIngredientsDaily($menu_id)
    {
        try {
            DB::beginTransaction();
            MealMenuIngredientDaily::where('menu_daily_id', $menu_id)->delete();
            $menu = MealMenuDaily::findOrFail($menu_id);
            $mealAgeCode = optional($menu->mealAge)->code; 
            $countStudent = (float)$menu->count_student;
            if (!$mealAgeCode || $countStudent <= 0) {
                throw new \Exception('Không có mã nhóm tuổi hoặc số lượng học sinh không hợp lệ.');
            }

            // Lấy các món ăn trong thực đơn
            $menuDishes = MealMenuDishesDaily::with('dishes')->where('menu_daily_id', $menu_id)->get();
            $ingredientTotals = [];

            foreach ($menuDishes as $menuDish) {
                $dish = $menuDish->dishes;
                if (!$dish || empty($dish->quantitative)) {
                    continue;
                }

                foreach ($dish->quantitative as $ingredientId => $ageQuantities) {
                    if (!isset($ageQuantities[$mealAgeCode])) {
                        continue;
                    }

                    $qtyPerStudent = (float) $ageQuantities[$mealAgeCode];
                    $totalQty = $qtyPerStudent * $countStudent;

                    if (!isset($ingredientTotals[$ingredientId])) {
                        $ingredientTotals[$ingredientId] = 0;
                    }
                    $ingredientTotals[$ingredientId] += $totalQty;
                }
            }

            foreach ($ingredientTotals as $ingredientId => $total) {
                MealMenuIngredientDaily::create([
                    'menu_daily_id'       => $menu_id,
                    'ingredient_id' => $ingredientId,
                    'value'         => $total,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    //hàm tính toán tồn kho của thực phẩm
    public function calculateIngredientStock($ingredientId, $areaId)
    {
        try {
            $totalStock = MealWarehouseIngredient::where('ingredient_id', $ingredientId)
                ->where('area_id', $areaId)
                ->sum('quantity');

            return $totalStock;
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi tính toán tồn kho: ' . $e->getMessage());
        }
    }
    public static function autoUpdateCode($id, $type)
    {
        // demo NK-152451
        $date = date('my');
        $code = $type . '-' . $date . $id;

        $warehouseEntryTypes = ['NK', 'XK'];

        if (in_array($type, $warehouseEntryTypes)) {
            $warehouse_entry = MealWareHouseEntry::find($id);
            if ($warehouse_entry) {
                $warehouse_entry->code = $code;
                return $warehouse_entry->save();
            }
        }
        return false; // Trả về false nếu không cập nhật được
    }
}
