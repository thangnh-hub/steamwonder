<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealWarehouseIngredient;
use App\Models\MealIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Consts;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use App\Http\Services\MenuPlanningService;
use App\Models\MealWareHouseEntryDetail;
use Exception;

class MealWarehouseIngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'warehouse_ingredients';
        $this->viewPart = 'admin.pages.meal.warehouse_ingredients';
        $this->responseData['module_name'] = 'Quản lý kho thực phẩm theo cơ sở';
    }

    public function index(Request $request) 
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $permisson_area_id = DataPermissionService::getPermisisonAreas($admin->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $params['permisson_area_id'] = $permisson_area_id;
        $params['status'] = 'new';
        $params['order_by'] = 'ingredient_id';

        $rows = MealWarehouseIngredient::getsqlMealWarehouseIngredient($params)->get();

        $groupedByIngredient = $rows->groupBy('ingredient_id');
        $areaIdsInRows = $rows->pluck('area_id')->unique();
        $areasFromRows = Area::whereIn('id', $areaIdsInRows)->get();

        $ingredientsData = $groupedByIngredient->map(function ($items) use ($areasFromRows) {
            $firstItem = $items->first();
            $areaQuantities = [];

            foreach ($areasFromRows as $area) {
                $match = $items->firstWhere('area_id', $area->id);
                $areaQuantities[$area->id] = $match ? $match->quantity : "";
            }

            return [
                'ingredient_name' => $firstItem->ingredients->name ?? 'Không rõ',
                'ingredient_unit' => $firstItem->ingredients->unitDefault->name ?? '',
                'area_quantities' => $areaQuantities,
                'note' => $firstItem->json_params->note ?? '',
            ];
        });

        $this->responseData['ingredients_data'] = $ingredientsData;
        $this->responseData['areas_from_rows'] = $areasFromRows;
        $this->responseData['params'] = $params;
        $this->responseData['list_status'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MealWarehouseIngredient  $mealWarehouseIngredient
     * @return \Illuminate\Http\Response
     */
    public function show(MealWarehouseIngredient $mealWarehouseIngredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MealWarehouseIngredient  $mealWarehouseIngredient
     * @return \Illuminate\Http\Response
     */
    public function edit(MealWarehouseIngredient $mealWarehouseIngredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MealWarehouseIngredient  $mealWarehouseIngredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MealWarehouseIngredient $mealWarehouseIngredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MealWarehouseIngredient  $mealWarehouseIngredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(MealWarehouseIngredient $mealWarehouseIngredient)
    {
        //
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
