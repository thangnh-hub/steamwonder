<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CmsProduct;
use App\Models\CmsTaxonomy;
use App\Models\CmsRelationship;
use App\Consts;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CmsTaxonomyProductController extends Controller
{
    protected $is_type;
    public function __construct()
    {
        $this->is_type  = 'product';
        $this->routeDefault  = 'cms_products';
        $this->viewPart = 'admin.pages.cms_products';
        $this->responseData['module_name'] = __('Product Management');
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
            'relation' => 'required',
        ]);
        $params = $request->all();
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['is_type'] = $this->is_type;
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $arr_relation = $params['relation'];
        unset($params['relation']);
        // dd($params);
        $cmsProduct = CmsProduct::create($params);
        $arr_insert=[];
        foreach($arr_relation as $val){
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
        $this->responseData['parents'] = CmsTaxonomy::getSqlTaxonomy($paramTaxonomys)->get();
        $this->responseData['relationship'] = CmsRelationship::where('object_id',$cmsProduct->id)->get();
        $this->responseData['detail'] = $cmsProduct;
        $this->responseData['status'] = Consts::STATUS;
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
        $params = $request->all();
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['is_type'] = $this->is_type;
        $params['is_featured'] = $request->is_featured??'0';
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $arr_relation = $params['relation'];
        unset($params['relation']);
        $cmsProduct->fill($params);
        $cmsProduct->save();
        CmsRelationship::where('object_id',$cmsProduct->id)->delete();
        $arr_insert=[];
        foreach($arr_relation as $val){
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
            // Get list post with filter params
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
}
