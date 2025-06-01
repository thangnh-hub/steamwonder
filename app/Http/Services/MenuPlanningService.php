<?php

namespace App\Http\Services;

use App\Models\MealMenuIngredient;
use App\Models\MealMenuPlanning;
use App\Models\MealMenuDishes;
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

}
