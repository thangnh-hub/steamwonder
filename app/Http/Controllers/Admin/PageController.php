<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\PageBuilderService;
use App\Models\BlockContent;
use App\Models\Page;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Language;
use Illuminate\Support\Facades\App;
class PageController extends Controller
{
    private $page;
    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->routeDefault  = 'pages';
        $this->viewPart = 'admin.pages.pages';
        $this->responseData['module_name'] = __('Page Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Page::where('status', "!=", Consts::STATUS_DELETE)
            ->orderByRaw('status ASC, iorder ASC, id DESC')
            ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'title' => 'required|max:255',
            'route_name' => 'required|max:255',
            'alias' => 'max:100'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['alias'] = Str::slug($params['alias'] ?? $params['title']);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['title'][$lang] = $request['title'];
        $params['json_params']['description'][$lang] = $request['description'];
        $params['json_params']['content'][$lang] = $request['content'];
        Page::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {

        $params['template'] = $page->json_params->template;
        $params['status'] = Consts::STATUS['active'];
        $params['order_by'] = [
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];

        $blockContents = BlockContent::getSqlBlockContent($params)->get();
        // Reorder blockContents setting of this widget
        $block_setting = $page->json_params->block_content ?? [];
        // Filter selected blockContents
        $block_selected = $blockContents->filter(function ($item) use ($block_setting) {
            return in_array($item->id, $block_setting);
        });
        // Reorder selected blockContents
        $block_selected = $block_selected->sortBy(function ($item) use ($block_setting) {
            return array_search($item->id, $block_setting);
        });

        // Config widgets for this page
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];

        $this->responseData['block_selected'] = $block_selected;
        $this->responseData['blockContents'] = $blockContents;
        $this->responseData['detail'] = $page;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|max:255',
            'route_name' => 'required|max:255',
            'alias' => 'max:100'
        ]);

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['alias'] = Str::slug($params['alias'] ?? $params['title']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $block_content = json_decode($params['output_block']);

        $arr_block = [];
        if (!empty($block_content) && count($block_content) > 0) {
            foreach ($block_content as $val) {
                if (!isset($val->id)) {
                    continue;
                }
                $arr_block[] = $val->id;
            }
        }
        $params['json_params']['block_content'] = $arr_block;

        // update Sort block
        $data = $params['output_block'] ?? [];
        unset($params['output_block']);
        $root_id = null;
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
        $response = (new BlockContent)->reSort($newTree);
        // end

        $params['json_params']['title'][$lang] = $params['title']??"";
        $params['json_params']['description'][$lang] = $params['description']??"";
        $params['json_params']['content'][$lang] = $params['content']??"";

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($page->json_params != "") {
            foreach ($page->json_params as $key => $val) {
                if (in_array($key, ['block_content', 'widget'])) {
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
        $page->fill($arr_insert);
        $page->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        // Update status to delete
        $page->status = Consts::STATUS_DELETE;
        $page->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
    public function loadStatus($id)
    {
        // dd($this->page);
        $page   =  $this->page->find($id);
        $status = $page->status;
        if ($status == "active") {
            $statusUpdate = 'deactive';
        } else {
            $statusUpdate = 'active';
        }
        $updateResult =  $page->update([
            'status' => $statusUpdate,
        ]);
        // dd($updateResult);
        $page   =  $this->page->find($id);
        if ($updateResult) {
            return response()->json([
                "code" => 200,
                "html" => view('admin.components.load-change-status', ['data' => $page, 'type' => 'danh mục'])->render(),
                "message" => "success"
            ], 200);
        } else {
            return response()->json([
                "code" => 500,
                "message" => "fail"
            ], 500);
        }
    }
}
