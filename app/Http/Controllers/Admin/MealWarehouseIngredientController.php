<?php

namespace App\Http\Controllers\Admin;

use App\Models\MealWarehouseIngredient;
use App\Models\MealWareHouseEntry;
use App\Models\MealIngredientCategory;
use App\Models\MealIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Consts;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use App\Http\Services\MenuPlanningService;
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
        $params['status'] = Consts::STATUS['active'];
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
    public function viewWarehouseIncredientEntry()
    {
        $admin = Auth::guard('admin')->user();
        $permisson_area_id = DataPermissionService::getPermisisonAreas($admin->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $this->responseData['category_products'] = MealIngredientCategory::getSqlIngredientCategory(Consts::STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.entry_create');
        
    }
    
    public function storeWarehouseIncredientEntry(Request $request)
    {
        $request->validate([
        'name' => 'required',
        'area_id' => 'required',
        'cart' => 'required|array|min:1',
        'cart.*.product_id' => 'required|integer|exists:tb_warehouse_product,id',
        'cart.*.quantity' => 'nullable|integer|min:1',
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::guard('admin')->user();
            $params = $request->except('cart');
            $params['admin_created_id'] = $user->id;
            $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
            $wareHouseEntry = MealWarehouseIngredient::create($params);
            MenuPlanningService::autoUpdateCode($wareHouseEntry->id, 'NK');
            
            $data = [];
            $cart = $request->cart;
            foreach ($cart as $details) {
                // Check and store order_detail
                $order_detail_params['entry_id'] = $wareHouseEntry->id;
                $order_detail_params['product_id'] = $details['product_id'];
                $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
                $order_detail_params['quantity'] = $details['quantity'] ?? 1;
                $order_detail_params['price'] = $details['price'] ?? null;
                $order_detail_params['subtotal_money'] =  $details['quantity'] * $details['price'] ?? null;
                $order_detail_params['warehouse_id'] = $request->warehouse_id ?? null;
                $order_detail_params['admin_created_id'] = $user->id;
                $order_detail_params['created_at'] = Carbon::now();
                array_push($data, $order_detail_params);

                $detail_product = WareHouseProduct::find($details['product_id']);
                if ($detail_product) {
               
                if ($detail_product->warehouse_type == Consts::WAREHOUSE_PRODUCT_TYPE['vattutieuhao']) {
                    $quantity = $details['quantity'] ?? 1;
                    // Kiểm tra nếu mã sản phẩm đã tồn tại
                    $existingAsset = WarehouseAsset::where('product_id', $details['product_id'])
                    ->where('warehouse_id', $request->warehouse_id ?? null)->first();
                    if ($existingAsset) {
                    // Cộng dồn số lượng
                    $existingAsset->quantity += $quantity;
                    $existingAsset->updated_at = Carbon::now();
                    $existingAsset->save();
                    } else {
                    $params_asset = [
                        'entry_id' => $wareHouseEntry->id,
                        'product_id' => $details['product_id'],
                        'product_type' => $detail_product->warehouse_type ?? "",
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                        'warehouse_id' => $request->warehouse_id ?? null,
                        'code' => $detail_product->code,
                        'name' => $detail_product->name,
                        'status' => Consts::WAREHOUSE_ASSET_STATUS['new'],
                        'admin_created_id' => $user->id,
                        'department_id' => $request->department_id ?? ($user->department_id ?? null)
                    ];
                    $existingAsset = WarehouseAsset::create($params_asset);
                    }
                    // Tạo lịch sử tài sản trong bảng asset history
                    $params_asset_history['type'] = Consts::WAREHOUSE_TYPE_ASSET_HISTORY['nhapkho'];
                    $params_asset_history['asset_id'] = $existingAsset->id;
                    $params_asset_history['quantity'] = $existingAsset->quantity;
                    $params_asset_history['position_id'] = $existingAsset->position_id;
                    $params_asset_history['department_id'] = $existingAsset->department_id;
                    $params_asset_history['state'] = $existingAsset->state;
                    $params_asset_history['product_id'] = $existingAsset->product_id;
                    $params_asset_history['warehouse_id'] = $existingAsset->warehouse_id;
                    WarehouseService::createdWarehouseAssetHistory($params_asset_history);
                }
                }
            }
            WareHouseEntryDetail::insert($data);

            DB::commits();
            return redirect()->route('entry_warehouse.show', $wareHouseEntry->id)->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
        
    }

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
