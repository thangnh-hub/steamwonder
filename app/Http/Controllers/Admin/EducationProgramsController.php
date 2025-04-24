<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\EducationPrograms;
use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EducationProgramsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'education_programs';
        $this->viewPart = 'admin.pages.education_programs';
        $this->responseData['module_name'] = __('Education Programs management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['order_by'] = ['iorder' => 'asc'];
        $this->responseData['params'] = $params;

        $rows = EducationPrograms::getSqlEducationPrograms($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['status'] = Consts::STATUS;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['areas'] = Area::all();
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
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $params = $request->only([
            'area_id',
            'name',
            'json_params',
            'status',
            'iorder',
        ]);
        $params['admin_updated_id'] = $admin->id;
        EducationPrograms::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EducationPrograms  $educationPrograms
     * @return \Illuminate\Http\Response
     */
    public function show(EducationPrograms $educationPrograms)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EducationPrograms  $educationPrograms
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $educationPrograms = EducationPrograms::find($id);
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['areas'] = Area::all();
        $this->responseData['detail'] = $educationPrograms;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EducationPrograms  $educationPrograms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        DB::beginTransaction();
        try {
            $educationAges = EducationPrograms::find($id);
            $params = $request->only([
                'area_id',
                'name',
                'json_params',
                'status',
                'iorder',
            ]);
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $educationAges->update($params);
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra, vui lòng thử lại!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EducationPrograms  $educationPrograms
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educationAges = EducationPrograms::find($id);
        $educationAges->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
