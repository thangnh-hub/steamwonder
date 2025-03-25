<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use App\Http\Services\ContentService;
use App\Models\CmsTaxonomy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'menus';
        $this->viewPart = 'admin.pages.menus';
        $this->responseData['module_name'] = __('Menu Public Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['order_by'] = [
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $rows = Menu::getSqlMenu($params)->where('tb_menus.parent_id', NULL)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;
        $this->responseData['menu_type'] =  Consts::MENU_TYPE;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['menu_type'] =  Consts::MENU_TYPE;
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
            'name' => 'required|max:255'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }

        $params['parent_id'] = $params['parent_id'] ?? NULL;
        $params['iorder'] = $params['iorder'] ?? (Menu::where('parent_id', $params['parent_id'])->where('status', '!=', Consts::STATUS_DELETE)->max('iorder') + 1);

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $params['json_params']['name'][$lang] = $request['name'] ?? "";
        $params['json_params']['description'][$lang] = $request['description'] ?? "";

        unset($params['menu_taxonomy']);
        unset($params['menu_page']);

        if (isset($request->menu_taxonomy)) {
            foreach ($request->menu_taxonomy as $val) {
                $taxonomys = CmsTaxonomy::where('id', $val)->first();
                $params['name'] = $taxonomys->name;
                $params['json_params']['name'][$lang] =$taxonomys->json_params->name->$lang??$taxonomys->name;
                $params['url_link'] = $taxonomys->alias;
                $menu = Menu::create($params);
            }
        } else if (isset($request->menu_page)) {
            foreach ($request->menu_page as $val) {
                $pages = Page::where('id', $val)->first();
                $params['name'] = $pages->title;
                $params['json_params']['name'][$lang] =$pages->json_params->title->$lang??$pages->title;
                $params['url_link'] = $pages->alias;
                $menu    = Menu::create($params);
            }
        } else {
            $menu = Menu::create($params);
        }
        $id_redirect = $menu->parent_id ?? $menu->id;
        $check_root_menu = Menu::find($id_redirect);
        if ($check_root_menu->parent_id > 0) {
            return redirect()->back()->with('successMessage', __('Add new successfully!'));
        }
        return redirect()->route($this->routeDefault . '.edit', $id_redirect)->with('successMessage', __('Add new successfully!'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        // Get all submenu
        $params = [];
        $params['order_by'] = [
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $menus = Menu::getSqlMenu($params)->where('tb_menus.parent_id', '!=', NULL)->orderBy('iorder', 'ASC')->get();
        $this->responseData['menus'] = $menus;
        $this->responseData['detail'] = $menu;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['menu_type'] =  Consts::MENU_TYPE;
        $this->responseData['style_header'] = Consts::STYLE_HEADER;
        $rows = Page::where('status', Consts::STATUS['active'])
            ->orderByRaw('status ASC, iorder ASC, id DESC')
            ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['pages'] =  $rows;
        $taxonomy =  CmsTaxonomy::where('status', Consts::STATUS['active'])->get();
        $this->responseData['taxonomy'] =  $taxonomy;
        // khi print menu sẽ check theo menu con có parent_id là id của menu hiện tại

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }

        $params['json_params']['description'][$lang] = $params['description']??"";
        $params['json_params']['name'][$lang] = $params['name']??"";
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ

        if ($menu->json_params != "") {
            foreach ($menu->json_params as $key => $val) {
                if(in_array($key,['widget','paramater','gallery_image'])){continue;}
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
        $menu->fill($arr_insert);
        $menu->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {

        // Update status to delete
        $menu->status = Consts::STATUS_DELETE;
        $menu->save();
        // Delete sub
        // DB::table('tb_menus')->where('parent_id', '=', $menu->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    /*
    Update menu resort
     */
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
                            if (!empty($level_3['children'])) {
                                $list_level_4 = $level_3['children'];
                                foreach ($list_level_4 as $key => $level_4) {
                                    $newTree[$level_4['id']] = [
                                        'parent_id' => $level_3['id'],
                                        'iorder' => ++$key,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        $response = (new Menu)->reSort($newTree);
        return $response;
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
            $check = Menu::where('parent_id', $id)->count();
            if ($check) {
                return response()->json(['error' => 1, 'msg' => __('This item has menu children!')]);
            } else {
                Menu::destroy($id);
            }
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
