<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductCategory;
use App\Models\Widget;
use App\Models\WidgetConfig;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use App\Consts;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'product_category';
        $this->viewPart = 'admin.pages.product_category';
        $this->responseData['module_name'] = __('Category Product Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['taxonomy'] = Consts::TAXONOMY['product'];
        $this->responseData['params'] = $params;
        $this->responseData['rows'] =  ProductCategory::getSqlTaxonomy($params)->get();
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['status'] = Consts::STATUS['active'];
        $params['taxonomy'] = Consts::TAXONOMY['product'];
        // Config widgets for this page
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $widgets = Widget::getSqlWidget($params_widget)->get();
        $widgetConfig = WidgetConfig::all();
        $this->responseData['widgets'] = $widgets;
        $this->responseData['widgetConfig'] = $widgetConfig;
        $this->responseData['categorys'] = ProductCategory::getSqlTaxonomy($params)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['taxonomy'] = Consts::TAXONOMY['product'];
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget'], 'strlen');
        unset($params['widget']);
        $params['json_params']['name'][$lang] = $request['name'] ?? "";

        ProductCategory::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory  $productCategory)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory  $productCategory)
    {
        // Get all parents which have status is active
        $params['status'] = Consts::STATUS['active'];
        $params['taxonomy'] = Consts::TAXONOMY['product'];
        // Config widgets for this page
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $widgets = Widget::getSqlWidget($params_widget)->get();
        $widgetConfig = WidgetConfig::all();
        $this->responseData['widgets'] = $widgets;
        $this->responseData['widgetConfig'] = $widgetConfig;

        $this->responseData['categorys'] = ProductCategory::getSqlTaxonomy($params)->get();
        $this->responseData['detail'] = $productCategory;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory  $productCategory)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }

        $params['json_params']['name'][$lang] = $request['name'];
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget'], 'strlen');
        unset($params['widget']);
        $arr_insert = $params;

        if ($productCategory->json_params != "") {
            foreach ($productCategory->json_params as $key => $val) {
                if ($key == 'widget') {
                    continue;
                }
                if (isset($arr_insert['json_params'][$key])) {
                    if ($arr_insert['json_params'][$key] != null) {
                        if (isset($arr_insert['json_params'][$key])) {
                            if (!is_array($params['json_params'][$key])) {
                                $arr_insert['json_params'][$key] = $params['json_params'][$key];
                            } else {

                                $arr_insert['json_params'][$key] = array_merge((array)$val, $params['json_params'][$key]);
                            }
                        } else {
                            $arr_insert['json_params'][$key] = $val;
                        }
                    }
                }
            }
        }

        $productCategory->fill($arr_insert);
        $productCategory->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->status = Consts::STATUS_DELETE;
        $productCategory->save();

        // Update delete status sub
        ProductCategory::where('parent_id', '=', $productCategory->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'iorder';
            // Get list post with filter params
            $rows = ProductCategory::getSqlTaxonomy($params)->get();

            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
