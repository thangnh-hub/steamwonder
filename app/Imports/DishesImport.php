<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\MealDishes;
use App\Models\MealIngredient;

class DishesImport implements ToModel, WithHeadingRow
{
    protected $params = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function model(array $row)
    {
        $dishName = ucfirst(trim($row['ten_mon_an'] ?? ''));
        $ingredientsRaw = trim($row['nguyen_lieu'] ?? '');

        if (empty($dishName) || empty($ingredientsRaw)) {
            return null; // Bỏ qua dòng không hợp lệ
        }

        // Tạo món ăn nếu chưa có
        $mealDish = MealDishes::firstOrCreate(
            ['name' => $dishName],
            [
                'description' => null,
                'dishes_type' => null,
                'dishes_time' => null,
                'status' => 'active',
                'admin_created_id' => auth()->id(),
                'admin_updated_id' => auth()->id(),
            ]
        );

        // Lấy danh sách nguyên liệu từ chuỗi
        $ingredientsList = array_map(function ($item) {
            $item = trim($item);
            return mb_strtoupper(mb_substr($item, 0, 1), 'UTF-8') . mb_substr($item, 1, null, 'UTF-8');
        }, explode(',', $ingredientsRaw));


        $jsonParams = json_decode(json_encode($mealDish->json_params), true); 
        $jsonParams['quantitative'] = $jsonParams['quantitative'] ?? [];

        foreach ($ingredientsList as $ingredientNameRaw) {
            $ingredient = MealIngredient::firstOrCreate(
                ['name' => $ingredientNameRaw],
                [
                    'description' => null,
                    'default_unit_id' => 5,
                    'convert_to_gram' => null,
                    'ingredient_category_id' => null,
                    'type' => 'save_warehouse',
                    'status' => 'active',
                    'admin_created_id' => auth()->id(),
                    'admin_updated_id' => auth()->id(),
                ]
            );

            $id_ingredient = $ingredient->id;

            // Nếu chưa có định lượng thì thêm mặc định
            if (!isset($jsonParams['quantitative'][$id_ingredient])) {
                $jsonParams['quantitative'][$id_ingredient] = [
                    'mam_non' => 0,
                    'nha_tre' => 0,
                    'CBNV' => 0,
                ];
            }
        }

        $mealDish->json_params = $this->arrayToObject($jsonParams);
        $mealDish->save();

    return null;
}

/**
 * Đệ quy chuyển array sang stdClass
 */
private function arrayToObject($array)
{
    if (is_array($array)) {
        return (object) array_map([$this, 'arrayToObject'], $array);
    }
    return $array;
}


}
