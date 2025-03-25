<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CmsProduct;
use App\Models\CmsTaxonomy;
use App\Models\CmsRelationship;
use App\Models\Widget;
use App\Models\Language;
use App\Models\WidgetConfig;
use App\Models\Brand;
use App\Models\Parameter;
use App\Consts;
use App\Exports\Eexport;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use App\Imports\Eimport;
use Maatwebsite\Excel\Facades\Excel;

class CmsProductController extends Controller
{

    protected $is_type;
    public function __construct()
    {

        $this->is_type  = 'product';
        $this->routeDefault  = 'cms_products';
        $this->viewPart = 'admin.pages.cms_products';
        $this->responseData['module_name'] = __('Product Management');
        $this->responseData['setting'] = Controller::getSetting();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['is_type'] = $this->is_type;
        // Get list post with filter params
        $rows = CmsProduct::getsqlCmsProduct($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['product'];
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['postStatus'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paramTaxonomys['status'] = Consts::STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['product'];
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
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['parameter'] = Parameter::where('is_type', '=', Consts::TAXONOMY['product'])->where('status', '!=', Consts::STATUS_DELETE)->get();
        // dd($this->responseData);
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

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();

        if (isset($params['import']) && isset($params['file'])) {

            Excel::import(new Eimport($params), request()->file('file'));
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        }
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['is_type'] = $this->is_type;
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget']);
        $params['json_params']['name'][$lang] = $request['name'];
        unset($params['widget']);
        $arr_relation = $params['relation'];
        unset($params['relation']);
        $cmsProduct = CmsProduct::create($params);
        $arr_insert = [];
        foreach ($arr_relation as $val) {
            $params_relation['object_id'] = $cmsProduct->id;
            $params_relation['taxonomy_id'] = $val;
            $params_relation['object_type'] = $cmsProduct->is_type;
            array_push($arr_insert, $params_relation);
        }
        CmsRelationship::insert($arr_insert);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsProduct  $cmsProduct
     * @return \Illuminate\Http\Response
     */
    public function show(CmsProduct  $cmsProduct)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsProduct  $cmsProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsProduct  $cmsProduct)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['product'];
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
        $this->responseData['parameter'] = Parameter::where('is_type', '=', Consts::TAXONOMY['product'])->where('status', '!=', Consts::STATUS_DELETE)->get();
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['relationship'] = CmsRelationship::where('object_id', $cmsProduct->id)->get();
        $this->responseData['detail'] = $cmsProduct;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['relateds'] = CmsProduct::getsqlCmsProduct([
            'related_post' => $cmsProduct->json_params->related_post ?? [""],
            'order_by' => 'id'
        ])->get();
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['tags'] ?? '';
        $this->responseData['tags'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsProduct  $cmsProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsProduct  $cmsProduct)
    {
        $request->validate([
            'name' => 'required|max:255',
            'relation' => 'required',
        ]);
        $arr_lang_code = [];
        $all_lang = Language::where('status', Consts::STATUS['active'])->get();
        foreach ($all_lang as $val) {
            $arr_lang_code[] = $val->lang_code;
        }

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['is_type'] = $this->is_type;
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget']);
        $params['json_params']['name'][$lang] = $params['name'];
        unset($params['widget']);
        $arr_relation = $params['relation'];
        unset($params['relation']);
        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($cmsProduct->json_params != "") {
            foreach ($cmsProduct->json_params as $key => $val) {
                // if(in_array($key,['widget','paramater',])){continue;}
                if (isset($arr_insert['json_params'][$key])) {
                    if ($arr_insert['json_params'][$key] != null) {
                        if (isset($arr_insert['json_params'][$key])) {
                            if (is_array($params['json_params'][$key])) {
                                $key_lang = collect($params['json_params'][$key])->filter(function ($item, $key) use ($arr_lang_code) {
                                    return in_array($key, $arr_lang_code);
                                });
                                if (count($key_lang) > 0) {
                                    $arr_insert['json_params'][$key] = array_merge((array)$val, $params['json_params'][$key]);
                                } else {
                                    $arr_insert['json_params'][$key] = $params['json_params'][$key] ?? $val;
                                }
                            }
                        } else {
                            $arr_insert['json_params'][$key] = $val;
                        }
                    }
                }
            }
        }
        // dd($arr_insert);
        $cmsProduct->fill($arr_insert);
        $cmsProduct->save();
        CmsRelationship::where('object_id', $cmsProduct->id)->delete();
        $arr_insert = [];
        foreach ($arr_relation as $val) {
            $params_relation['object_id'] = $cmsProduct->id;
            $params_relation['taxonomy_id'] = $val;
            $params_relation['object_type'] = $cmsProduct->is_type;
            array_push($arr_insert, $params_relation);
        }
        CmsRelationship::insert($arr_insert);

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsProduct  $cmsProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsProduct  $cmsProduct)
    {
        // check is type post
        if ($cmsProduct->is_type != $this->is_type) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $cmsProduct->status = Consts::STATUS_DELETE;
        $cmsProduct->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'id';
            $params['arr_id'] = [];
            // Get list post with filter params
            if ($params['taxonomy_id'] != "") {
                $arr_id = CmsRelationship::select('object_id')->where('taxonomy_id', $params['taxonomy_id'])->where('object_type', $params['is_type'])->get();
                foreach ($arr_id as $val) {
                    $params['arr_id'][] = $val->object_id;
                }
            }

            $rows = CmsProduct::getsqlCmsProduct($params)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function export_excel()
    {
        $lang = App::getLocale();
        $params['is_type'] = $this->is_type;
        $rows = CmsProduct::getsqlCmsProduct($params)->get();
        return Excel::download(new Eexport($rows, $lang), 'product.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
