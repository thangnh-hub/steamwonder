<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\MealDishes;
use App\Models\MealIngredient;
use App\Models\MealMenuPlanning;
use App\Models\MealMenuIngredient;
use App\Models\MealMenuDishes;

class MenuesImport implements ToModel, WithHeadingRow
{
    protected $params = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function model(array $row)
    {
        $menuName     = trim($row['ten_thuc_don'] ?? '');
        $season       = null;
        $description  = null;
        $countStudent = 100; // Mặc định là 100, có thể thay đổi nếu cần
        $mealType     = trim($row['bua_an'] ?? '');
        $dishName     = trim($row['ten_mon_an'] ?? '');
        $ingredientsRaw = trim($row['nguyen_lieu'] ?? '');

        if (!$menuName || !$dishName) return null;
        // Thực đơn
        $menu = MealMenuPlanning::firstOrCreate(
            [
                'name' => $menuName,
            ],
            [
                'meal_age_id' => 3,
                'season' => $season,
                'description' => $description,
                'count_student' => $countStudent,
                'status' => 'active',
                'admin_created_id' => auth()->id(),
                'admin_updated_id' => auth()->id(),
            ]
        );

        // Cập nhật mã thực đơn nếu chưa có
        if (!$menu->code) {
            $menu->code = 'TDM' . str_pad($menu->id, 5, '0', STR_PAD_LEFT);
            $menu->save();
        }

        // Món ăn
        $dish = MealDishes::firstOrCreate(['name' => $dishName]);

        // Gắn món ăn vào thực đơn
        MealMenuDishes::firstOrCreate([
            'menu_id' => $menu->id,
            'dishes_id' => $dish->id,
            'type' => $this->mapMealType($mealType),
        ], [
            'status' => 'active',
            'admin_created_id' => auth()->id(),
            'admin_updated_id' => auth()->id(),
        ]);

        // Nguyên liệu (chuẩn hóa hoa chữ cái đầu)
        $ingredientsList = array_map(function ($item) {
            return mb_convert_case(trim($item), MB_CASE_TITLE, "UTF-8");
        }, explode(',', $ingredientsRaw));

        foreach ($ingredientsList as $ingredientName) {
            if (!$ingredientName) continue;

            $ingredient = MealIngredient::firstOrCreate(
                ['name' => $ingredientName],
                [
                    'default_unit_id' => 5,
                    'type' => 'save_warehouse',
                    'status' => 'active',
                    'admin_created_id' => auth()->id(),
                    'admin_updated_id' => auth()->id(),
                ]
            );

            MealMenuIngredient::firstOrCreate([
                'menu_id' => $menu->id,
                'ingredient_id' => $ingredient->id,
            ], [
                'status' => 'active',
                'admin_created_id' => auth()->id(),
                'admin_updated_id' => auth()->id(),
            ]);
        }

        return null; // Vì chúng ta không cần trả về Model để lưu
    }

    protected function mapMealType($key)
    {
        return [
            'breakfast' => 'breakfast',
            'demo_breakfast' => 'demo_breakfast',
            'lunch' => 'lunch',
            'brunch' => 'brunch',
            'demo_brunch' => 'demo_brunch',
        ][$key] ?? $key;
    }

}
