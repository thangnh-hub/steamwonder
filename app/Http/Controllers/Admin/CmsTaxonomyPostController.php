<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\CmsPost;
use App\Models\CmsTaxonomy;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CmsTaxonomyPostController extends Controller
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
        $this->responseData['status'] = Consts::STATUS;
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

        $params = $request->all();
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['is_type'] = $this->is_type;
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsPost::create($params);

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
    public function edit(CmsPost $cmsPost)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['post'];
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $cmsPost;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['relateds'] = CmsPost::getsqlCmsPost([
            'related_post' => $cmsPost->json_params->related_post ?? [""],
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
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsPost $cmsPost)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $params = $request->all();
        $params['is_featured'] = $request->is_featured??'0';
        $params['alias'] = Str::slug($params['alias'] ?? $params['title']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;


        $cmsPost->fill($params);
        $cmsPost->save();

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
