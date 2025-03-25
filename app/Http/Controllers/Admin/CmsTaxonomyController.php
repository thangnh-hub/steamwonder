<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CmsTaxonomy;
use App\Consts;
use App\Models\Widget;
use App\Models\WidgetConfig;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class CmsTaxonomyController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_taxonomys';
        $this->viewPart = 'admin.pages.cms_taxonomys';
        $this->responseData['module_name'] = __('Taxonomy Management');
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
        $params['order_by'] = ['iorder' => 'ASC'];
        $this->responseData['rows'] =  CmsTaxonomy::getSqlTaxonomy($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = CmsTaxonomy::getSqlTaxonomy($params)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['taxonomy'] = Consts::TAXONOMY;
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
            'taxonomy' => 'required|max:255',
        ]);

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['name'][$lang] = $params['name'] ?? "";
        $taxonomy =  CmsTaxonomy::create($params);
        return redirect()->route($this->routeDefault . '.edit',$taxonomy);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function show(CmsTaxonomy $cmsTaxonomy)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsTaxonomy $cmsTaxonomy)
    {
        // Get all parents which have status is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['different_id'] = $cmsTaxonomy->id;

        $this->responseData['taxonomys'] = CmsTaxonomy::getSqlTaxonomy($params)->get();
        $this->responseData['detail'] = $cmsTaxonomy;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['taxonomy'] = Consts::TAXONOMY;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsTaxonomy $cmsTaxonomy)
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
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['json_params']['name'][$lang] = $params['name'] ?? "";
        $cmsTaxonomy->fill($params);
        $cmsTaxonomy->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsTaxonomy $cmsTaxonomy)
    {
        $cmsTaxonomy->status = Consts::STATUS_DELETE;
        $cmsTaxonomy->save();

        // Update delete status sub
        CmsTaxonomy::where('parent_id', '=', $cmsTaxonomy->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
