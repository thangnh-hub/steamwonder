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
use App\Models\MealWareHouseEntryDetail;
use Exception;

class MealWarehouseEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault = 'warehouse_ingredients_entry';
        $this->viewPart = 'admin.pages.meal.warehouse_ingredients_entry';
        $this->responseData['module_name'] = 'Quản lý nhập kho';
    }

    public function index(Request $request) 
    {
        $params=$request->all();
        $admin = Auth::guard('admin')->user();
        $permisson_area_id = DataPermissionService::getPermisisonAreas($admin->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $params['permisson_area_id'] = $permisson_area_id;
        $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
        $rows = MealWareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] =  $params;
        return $this->responseView($this->viewPart . '.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $permisson_area_id = DataPermissionService::getPermisisonAreas($admin->id);
        if (empty($permisson_area_id)) $permisson_area_id = [-1];
        $params_area['id'] = $permisson_area_id;
        $params_area['status'] = Consts::STATUS['active'];
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $this->responseData['category_products'] = MealIngredientCategory::getSqlIngredientCategory(Consts::STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.create');
        
    }
    
    public function store(Request $request)
    {
         $request->validate([
        'name' => 'required',
        'area_id' => 'required',
        'cart' => 'required|array|min:1',
        'cart.*.ingredient_id' => 'required|integer|exists:tb_meal_ingredients,id',
        'cart.*.quantity' => 'nullable|integer|min:1',
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::guard('admin')->user();
            $params = $request->except('cart');
            $params['admin_created_id'] = $user->id;
            $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
            $wareHouseEntry = MealWareHouseEntry::create($params);
            MenuPlanningService::autoUpdateCode($wareHouseEntry->id, 'NK');
            
            $data = [];
            $cart = $request->cart;
            foreach ($cart as $details) {
                // Check and store order_detail
                $order_detail_params['entry_id'] = $wareHouseEntry->id;
                $order_detail_params['ingredient_id'] = $details['ingredient_id'];
                $order_detail_params['type'] =  Consts::WAREHOUSE_TYPE_ENTRY['nhap_kho'];
                $order_detail_params['quantity'] = $details['quantity'] ?? 1;
                $order_detail_params['area_id'] = $request->area_id ?? null;
                $order_detail_params['admin_created_id'] = $user->id;
                $order_detail_params['created_at'] = Carbon::now();
                array_push($data, $order_detail_params);

                //Lưu thực phẩm vào kho
                $detail_product = MealIngredient::find($details['ingredient_id']);
                if ($detail_product) {
                    $quantity = $details['quantity'] ?? 1;
                    // Kiểm tra nếu mã sản phẩm đã tồn tại
                    $existingAsset = MealWarehouseIngredient::where('ingredient_id', $details['ingredient_id'])
                    ->where('area_id', $request->area_id ?? null)->first();
                    if ($existingAsset) {
                        // Cộng dồn số lượng
                        $existingAsset->quantity += $quantity;
                        $existingAsset->updated_at = Carbon::now();
                        $existingAsset->save();
                    }
                    else {
                        $params_asset = [
                            'entry_id' => $wareHouseEntry->id,
                            'ingredient_id' => $details['ingredient_id'],
                            'quantity' => $details['quantity'],
                            'area_id' => $request->area_id ?? null,
                            'status' => Consts::WAREHOUSE_ASSET_STATUS['new'],
                            'admin_created_id' => $user->id,
                        ];
                        $existingAsset = MealWarehouseIngredient::create($params_asset);
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
            MealWareHouseEntryDetail::insert($data);

            DB::commit();
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MealWarehouseIngredient  $mealWarehouseIngredient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entry = MealWareHouseEntry::find($id);
        $this->responseData['detail'] = $entry;
        $this->responseData['entry_details'] = $entry->mealEntryDetails ?? null;
        $this->responseData['module_name'] = __("CHI TIẾT PHIẾU NHẬP KHO THỰC PHẨM");
        return $this->responseView($this->viewPart . '.show');
    }

}
