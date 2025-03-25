<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\CmsPost;
use App\Models\CmsTaxonomy;
use App\Models\CmsRelationship;
use App\Models\Parameter;
use App\Models\Widget;
use App\Models\Language;
use App\Models\WidgetConfig;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CmsPostController extends Controller
{
    protected $is_type;

    public function __construct()
    {
        $this->is_type  = 'post';
        $this->routeDefault  = 'cms_posts';
        $this->viewPart = 'admin.pages.cms_posts';
        $this->responseData['module_name'] = __('Post Management');
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
        $rows = CmsPost::getsqlCmsPost($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['post'];
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['postStatus'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['post'];
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['tag'];
        $this->responseData['tags'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
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
            'relation' => 'required',

        ]);
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
        $params['json_params']['name'][$lang] = $request['name'];
        $arr_relation = $params['relation'];
        unset($params['relation']);
        $cmsPost = CmsPost::create($params);
        $arr_insert = [];
        foreach ($arr_relation as $val) {
            $params_relation['object_id'] = $cmsPost->id;
            $params_relation['taxonomy_id'] = $val;
            $params_relation['object_type'] = $cmsPost->is_type;
            array_push($arr_insert, $params_relation);
        }
        CmsRelationship::insert($arr_insert);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function show(CmsPost $cmsPost)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsPost $cmsPost, Request $request)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['post'];
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['relationship'] = CmsRelationship::where('object_id', $cmsPost->id)->get();
        $this->responseData['detail'] = $cmsPost;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;

        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['tag'];
        $this->responseData['tags'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        // $this->responseData['locale'] = Cookie::get('locale_admin');

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsPost $cmsPost)
    {
        $params = $request->all();

        $request->validate([
            'name' => 'required|max:255',
            'relation' => 'required',
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['is_featured'] = $request->is_featured ?? '0';
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['name'][$lang] = $params['name'];
        $arr_relation = $params['relation'];
        unset($params['relation']);

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($cmsPost->json_params != "") {
            foreach ($cmsPost->json_params as $key => $val) {
                if (in_array($key,['gallery_image','related_post,tag'])) {
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
        $cmsPost->fill($arr_insert);
        $cmsPost->save();

        // thêm vào bảng relation
        CmsRelationship::where('object_id', $cmsPost->id)->delete();
        $arr_addrelation = [];
        foreach ($arr_relation as $val) {
            $params_relation['object_id'] = $cmsPost->id;
            $params_relation['taxonomy_id'] = $val;
            $params_relation['object_type'] = $cmsPost->is_type;
            array_push($arr_addrelation, $params_relation);
        }
        CmsRelationship::insert($arr_addrelation);
        // kết thúc

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsPost $cmsPost)
    {
        // check is type post
        if ($cmsPost->is_type != $this->is_type) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $cmsPost->status = Consts::STATUS_DELETE;
        $cmsPost->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'id';
            // Get list post with filter params
            $rows = CmsPost::getsqlCmsPost($params)->get();

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
