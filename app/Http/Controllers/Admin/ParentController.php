<?php

namespace App\Http\Controllers\Admin;

use App\Models\tbParent;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Http\Services\DataPermissionService;
use App\Models\Area;
use App\Models\Admin;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'parents';
        $this->viewPart = 'admin.pages.parents';
        $this->responseData['module_name'] = 'Quản lý phụ huynh';
    }


    public function index(Request $request)
    {
        $params = $request->all(); 
        $rows = tbParent::getSqlParent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_sex'] = Consts::GENDER;

        $admission = Admin::where('status', Consts::STATUS_ACTIVE)
            ->where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->get();
        $this->responseData['admission'] = $admission;

        return $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:tb_parents,phone',
            'email' => 'required|email|unique:tb_parents,email',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;

        tbParent::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function show(tbParent $parent)
    {
        $this->responseData['detail'] = $parent;
        $this->responseData['childStudentIds'] = $parent->parentStudents->pluck('student_id')->toArray();
        return $this->responseView($this->viewPart . '.show');
    }

    public function edit(tbParent $parent)
    {
        $this->responseData['detail'] = $parent;

        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $this->responseData['list_sex'] = Consts::GENDER;

        $admission = Admin::where('status', Consts::STATUS_ACTIVE)
            ->where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->get();
        $this->responseData['admission'] = $admission;

        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, tbParent $parent)
    {
        $request->validate([
            'area_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:tb_parents,phone,' . $parent->id,
            'email' => 'required|email|unique:tb_parents,email,' . $parent->id,
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $parent->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy(tbParent $parent)
    {
        $parent->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
