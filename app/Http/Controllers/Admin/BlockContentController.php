<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\PageBuilderService;
use App\Models\Block;
use App\Models\Page;
use App\Models\BlockContent;
use App\Models\CmsTaxonomy;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class BlockContentController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'block_contents';
        $this->viewPart = 'admin.pages.block_contents';
        $this->responseData['module_name'] = __('Block Management');
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

        $rows = BlockContent::getSqlBlockContent($params)->get();
        $this->responseData['rows'] =  $rows;

        // Get all blocks which have status is active
        $blocks = Block::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['blocks'] = $blocks;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = $request->get('page');
        // Get all parents which have status is active
        $parents = BlockContent::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();

        // Get all blocks which have status is active
        $blocks = Block::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();

        $this->responseData['parents'] = [];
        $this->responseData['blocks'] = $blocks;
        $this->responseData['page'] = $page;
        if (!empty($page)) {
            $page_default = Page::find($page);
            if (isset($page_default->json_params->block_content)) {
                $data_parents = $parents->filter(function ($item, $key) use ($page_default) {
                    return (in_array($item->id, $page_default->json_params->block_content) || in_array($item->parent_id, $page_default->json_params->block_content));
                });
                $this->responseData['parents'] = $data_parents;
            }
        }
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
            'block_code' => 'required|max:255'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code??App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $page_id = $params['page'] ?? '';
        unset($params['page']);

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['title'][$lang] = $request['title']??"";
        $params['json_params']['url_link_title'][$lang] = $request['url_link_title']??"";
        $block = BlockContent::create($params);

        if ($page_id != '') {
            if ($params['parent_id'] == 'null' || $params['parent_id'] == '') {
                // add block to page
                $page = Page::find($page_id);
                // update witget page
                $json_params = $page->json_params;
                if (isset($json_params->block_content)) {
                    array_push($json_params->block_content, $block->id);
                } else {
                    $json_params->block_content = [$block->id];
                }
                // dd($json_params);
                $page->json_params = $json_params;
                $page->save();
            }
            return redirect()->route('block_contents.edit', [$block->id,'page'=>$page_id]);
            // return redirect()->route('pages.edit', [$page_id])->with('successMessage', __('Add new successfully!'));
        }

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlockContent  $blockContent
     * @return \Illuminate\Http\Response
     */
    public function show(BlockContent $blockContent)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlockContent  $blockContent
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockContent $blockContent)
    {
        // Get all parents which have status is active
        $parents = BlockContent::where('status', 'active')->where('id', '!=', $blockContent->id)->orderByRaw('iorder ASC, id DESC')->get();
        // Get all blocks which have status is active
        $blocks = Block::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        // Get all blocks child which have status is active
        $child = BlockContent::where('status', 'active')->where('parent_id', $blockContent->id)->orderBy('iorder', 'ASC')->get();
        $parents_child = BlockContent::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();

        // get all taxonomy
        $data_taxonomy['status'] = Consts::STATUS['active'];
        $data_taxonomy['order_by'] = ['iorder' => 'ASC'];
        $this->responseData['taxonomys'] = CmsTaxonomy::getSqlTaxonomy($data_taxonomy)->get();

        $this->responseData['parents'] = $parents;
        $this->responseData['blocks'] = $blocks;
        $this->responseData['detail'] = $blockContent;

        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['blocks'] = $blocks;
        $this->responseData['child'] = $child;
        $this->responseData['parents_child'] = $parents_child;

        if (View::exists($this->viewPart . '.edit.' . $blockContent->block_code)) {
            return $this->responseView($this->viewPart . '.edit.' . $blockContent->block_code);
        } else {
            return $this->responseView($this->viewPart . '.edit.default');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockContent  $blockContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockContent $blockContent)
    {
        $request->validate([
            'title' => 'required|max:255',
            'block_code' => 'required|max:255'
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $page_id = $params['page'] ?? '';
        unset($params['page']);
        $params['json_params']['title'][$lang] = $request['title'] ?? "";
        $params['json_params']['url_link_title'][$lang] = $request['url_link_title'] ?? "";
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($blockContent->json_params != "") {
            foreach ($blockContent->json_params as $key => $val) {
                if (in_array($key, ['gallery_image','taxonomy_blog'])) {
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
        $blockContent->fill($arr_insert);
        $blockContent->save();
        if ($page_id != '') {
            return redirect()->route('pages.edit', [$page_id])->with('successMessage', __('Successfully updated!'));
        }

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlockContent  $blockContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockContent $blockContent)
    {
        // Update status to delete
        $blockContent->status = Consts::STATUS_DELETE;
        $blockContent->save();

        // Delete sub
        DB::table('tb_block_contents')->where('parent_id', '=', $blockContent->id)->update(['status' => Consts::STATUS_DELETE]);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
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
            $page_id = request('page') ?? '';

            $block = BlockContent::find($id);
            $block->status = Consts::STATUS_DELETE;
            $block->save();
            // Delete sub
            DB::table('tb_block_contents')->where('parent_id', '=', $id)->update(['status' => Consts::STATUS_DELETE]);
            if ($page_id != '') {
                // add block to page
                $page = Page::find($page_id);
                // update witget page
                $json_params = $page->json_params;
                array_diff($json_params->block_content, [$id]);
                $page->json_params = $json_params;
                $page->save();
            }
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Get all block_content by params
     */
    public function getBlockContentsByTemplate(Request $request)
    {
        try {
            $request->validate([
                'template' => 'required|string|max:255'
            ]);
            $params = $request->only('template');
            $params['status'] = Consts::STATUS['active'];
            $params['order_by'] = [
                'iorder' => 'ASC',
                'id' => 'DESC'
            ];

            $rows = BlockContent::getSqlBlockContent($params)->where('tb_block_contents.parent_id', NULL)->get();

            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }

            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function addBlock(Request $request)
    {
        $request->validate([
            'list_block' => 'required',
        ]);
        $params = $request->all();
        $parents = BlockContent::whereIn('id',$params['list_block'])->get();
        $arr_insert=[];
        foreach($parents as $item){
            $data['parent_id'] = $params['parent_id'];
            $data['title'] = $item->title;
            $data['block_code'] = $params['block_code'];
            $data['json_params'] = $item['json_params'];
            $data['brief'] = $item['brief'];
            $data['content'] = $item['content'];
            $data['url_link'] = $item['url_link'];
            $data['url_link_title'] = $item['url_link_title'];
            $data['icon'] = $item['icon'];
            $data['image'] = $item['image'];
            $data['image_background'] = $item['image_background'];
            $data['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $data['admin_updated_id'] = Auth::guard('admin')->user()->id;
            array_push($arr_insert, $data);

        }
        BlockContent::insert($arr_insert);
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function updateSort(Request $request)
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

        $response = (new BlockContent)->reSort($newTree);
        return $response;
    }

}
