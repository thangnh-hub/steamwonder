<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Policies;
use App\Models\Area;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PoliciesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'policies';
        $this->viewPart = 'admin.pages.policies';
        $this->responseData['module_name'] = 'Quản lý chính sách';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'area_id']);
        $rows = Policies::getSqlPolicies($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['areas'] = Area::all();
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
        $this->responseData['type'] = Consts::TYPE_POLICIES;
        $this->responseData['areas'] = Area::all();
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['module_name'] = "Thêm chính sách";
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
            'code' => 'required',
            'name' => 'required|max:255',
        ]);
        $params = $request->only([
            'area_id',
            'code',
            'name',
            'json_params',
            'status',
            'description',

        ]);
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;
        Policies::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Policies  $policies
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = Policies::find($id);
        $serviceIds = collect(optional($detail->json_params)->services)->keys();
        // Định nghĩa mối quan hệ tùy chỉnh
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');
        $data_service = collect($detail->json_params->services)->map(function ($val, $key) use ($services) {
            $val->detail = $services->get($key); // Gắn thêm chi tiết service
            return $val;
        })->toArray();
        $result['view'] = view($this->viewPart . '.show', compact('detail', 'data_service'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Policies  $policies
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $policies = Policies::find($id);
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['areas'] = Area::all();
        $this->responseData['type'] = Consts::TYPE_POLICIES;
        $this->responseData['service'] = Service::getSqlService()->get();
        $this->responseData['module_name'] = "Sửa chính sách";
        $this->responseData['detail'] = $policies;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policies  $policies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'code' => 'required',
            'name' => 'required|max:255',
        ]);
        $policies = Policies::find($id);
        $params = $request->only([
            'area_id',
            'code',
            'name',
            'json_params',
            'status',
            'description',
        ]);
        $params['admin_updated_id'] = $admin->id;
        $policies->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policies  $policies
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $policies = Policies::find($id);
        $policies->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
