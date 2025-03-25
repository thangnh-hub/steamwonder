<?php

namespace App\Http\Controllers\Admin;

use App\Models\RankAcademic;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Subject;
use Exception;
use App\Consts;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
class RankAcademicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'ranked_academics';
        $this->viewPart = 'admin.pages.ranked_academics';
        $this->responseData['module_name'] = 'Quản lý thang điểm';
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['ranks'] = Consts::ranked_academic;
        $rows = RankAcademic::getSqlRankAcademic($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['level'] = Level::getSqlLevel()->get();
        $this->responseData['ranks'] = Consts::ranked_academic;
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
            'level_id' => 'required',
            'from_points' => 'required',
            'to_points' => 'required',
            'ranks' => 'required',
        ]);
        $params = $request->all();
        RankAcademic::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RankAcademic  $rankAcademic
     * @return \Illuminate\Http\Response
     */
    public function show(RankAcademic $rankAcademic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RankAcademic  $rankAcademic
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->responseData['detail'] = RankAcademic::find($id);
        $this->responseData['level'] = Level::getSqlLevel()->get();
        $this->responseData['ranks'] = Consts::ranked_academic;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RankAcademic  $rankAcademic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_id' => 'required',
            'from_points' => 'required',
            'to_points' => 'required',
            'ranks' => 'required',
        ]);
        
        $rankAcademic=RankAcademic::find($id);
        $params = $request->all();
        $rankAcademic->fill($params);
        $rankAcademic->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RankAcademic  $rankAcademic
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rankAcademic=RankAcademic::find($id);
        $rankAcademic->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
