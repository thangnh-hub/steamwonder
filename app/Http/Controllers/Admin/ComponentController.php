<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\Component;
use App\Models\ComponentConfig;
use App\Models\Menu;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class ComponentController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'components';
        $this->viewPart = 'admin.pages.components';
        $this->responseData['module_name'] = __('Component Management');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['params'] = $params;

        $params['order_by'] = [
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];

        $rows = Component::getSqlComponent($params)->get();
        $this->responseData['rows'] =  $rows;

        // Get all component_configs which have status is active
        $component_configs = ComponentConfig::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['component_configs'] = $component_configs;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all component_configs which have status is active
        $component_configs = ComponentConfig::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['component_configs'] = $component_configs;

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
            'title' => 'required|max:255'
        ]);

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }

        $params['parent_id'] = $params['parent_id'] ?? NULL;
        $params['iorder'] = $params['iorder'] ?? (Component::where('parent_id', $params['parent_id'])->where('status', '!=', Consts::STATUS_DELETE)->max('iorder') + 1);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['title'][$lang] = $params['title'] ?? "";
        $params['json_params']['brief'][$lang] = $params['brief'] ?? "";
        $params['json_params']['content'][$lang] = $params['content'] ?? "";

        $component = Component::create($params);

        $id_redirect = $component->parent_id ?? $component->id;

        return redirect()->route($this->routeDefault . '.edit', $id_redirect)->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        // Get all child items
        $items = Component::where('status', 'active')->where('parent_id', $component->id)->orderByRaw('iorder ASC, id DESC')->get();

        $this->responseData['items'] = $items;
        $this->responseData['detail'] = $component;


        $params['order_by'] = [
            'iorder' => 'ASC',
            'id' => 'DESC',
        ];
        $menus = Menu::getSqlMenu($params)
            ->where('tb_menus.parent_id', null)
            ->where('tb_menus.status', Consts::STATUS['active'])
            ->get();
        $this->responseData['menus'] = $menus;

        $this->responseData['parameter'] = Parameter::where('parent_id', null)
            ->where('status', Consts::STATUS['active'])
            ->get();
        // Get all component_configs which have status is active
        $component_configs = ComponentConfig::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['component_configs'] = $component_configs;

        if (View::exists($this->viewPart . '.edit.' . $component->component_code)) {
            return $this->responseView($this->viewPart . '.edit.' . $component->component_code);
        }
        return $this->responseView($this->viewPart . '.edit.default');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['title'][$lang] = $params['title'] ?? "";
        $params['json_params']['brief'][$lang] = $params['brief'] ?? "";
        $params['json_params']['content'][$lang] = $params['content'] ?? "";

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($component->json_params != "") {
            foreach ($component->json_params as $key => $val) {
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
        $component->fill($arr_insert);
        $component->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        // Update status to delete
        $component->status = Consts::STATUS_DELETE;
        $component->save();

        // Delete sub
        // DB::table('tb_components')->where('parent_id', '=', $component->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    /*
    Delete item
     */
    public function delete()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => __('Method not allowed!')]);
        } else {
            $id = request('id');
            $check = Component::where('parent_id', $id)->count();
            if ($check) {
                return response()->json(['error' => 1, 'msg' => __('This item has menu children!')]);
            } else {
                Component::destroy($id);
            }
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    public function updateSort()
    {
        $data = request('menu') ?? [];
        $root_id = request('root_id') ?? null;
        $reSort = json_decode($data, true);
        $newTree = [];
        foreach ($reSort as $key => $level_1) {
            $newTree[$level_1['id']] = [
                'parent_id' => $root_id,
                'iorder' => ++$key,
            ];
            if (!empty($level_1['children'])) {
                $list_level_2 = $level_1['children'];
                foreach ($list_level_2 as $key => $level_2) {
                    $newTree[$level_2['id']] = [
                        'parent_id' => $level_1['id'],
                        'iorder' => ++$key,
                    ];
                    if (!empty($level_2['children'])) {
                        $list_level_3 = $level_2['children'];
                        foreach ($list_level_3 as $key => $level_3) {
                            $newTree[$level_3['id']] = [
                                'parent_id' => $level_2['id'],
                                'iorder' => ++$key,
                            ];
                        }
                    }
                }
            }
        }
        $response = (new Component)->reSort($newTree);
        return $response;
    }
}
