<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use App\Models\Widget;
use App\Models\Page;
use App\Models\WidgetConfig;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use App\Consts;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'post_category';
        $this->viewPart = 'admin.pages.post_category';
        $this->responseData['module_name'] = __('Category Post Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['taxonomy'] = Consts::TAXONOMY['post'];
        $this->responseData['params'] = $params;
        $this->responseData['rows'] =  PostCategory::getSqlTaxonomy($params)->get();

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
        $params['taxonomy'] = Consts::TAXONOMY['post'];
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
        $this->responseData['categorys'] = PostCategory::getSqlTaxonomy($params)->get();
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
        $params['taxonomy'] = Consts::TAXONOMY['post'];
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget'], 'strlen');
        unset($params['widget']);
        $params['json_params']['name'][$lang] = $params['name'] ?? "";

        PostCategory::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PostCategory  $postCategory)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PostCategory  $postCategory)
    {
        // Get all parents which have status is active
        $params['status'] = Consts::STATUS['active'];
        $params['taxonomy'] = Consts::TAXONOMY['post'];
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

        $this->responseData['categorys'] = PostCategory::getSqlTaxonomy($params)->get();
        $this->responseData['detail'] = $postCategory;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostCategory  $postCategory)
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
        $params['json_params']['name'][$lang] = $request['name'] ?? "";
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['widget'] = array_filter($params['widget'], 'strlen');
        unset($params['widget']);

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($postCategory->json_params != "") {
            foreach ($postCategory->json_params as $key => $val) {
                if (in_array($key,['widget','block_content'])){ continue; }
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

        $postCategory->fill($arr_insert);
        $postCategory->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCategory $postCategory)
    {
        $postCategory->status = Consts::STATUS_DELETE;
        $postCategory->save();

        // Update delete status sub
        PostCategory::where('parent_id', '=', $postCategory->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'iorder';
            // Get list post with filter params
            $rows = PostCategory::getSqlTaxonomy($params)->get();

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
