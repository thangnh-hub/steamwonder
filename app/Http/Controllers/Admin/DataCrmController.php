<?php

namespace App\Http\Controllers\Admin;

use App\Models\DataCrm;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Http\Services\DataPermissionService;
use App\Models\Area;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Imports\DataCrmImport;
use Maatwebsite\Excel\Facades\Excel;

class DataCrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
       parent::__construct();
       $this->routeDefault  = 'data_crms';
       $this->viewPart = 'admin.pages.data_crms';
       $this->responseData['module_name'] = 'Quản lý dữ liệu CRM';
    }     

    public function index(Request $request)
    {
        $params = $request->all(); 
        // Get list post with filter params
        $rows = DataCrm::getSqlDataCrm($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS_DATACRM;

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
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $admission = Admin::where('status', Consts::STATUS_ACTIVE)->where("admin_type",Consts::ADMIN_TYPE['admission'])->get();  
        $this->responseData['admission'] = $admission;
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
            'area_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:tb_data_crms,phone',
            'email' => 'required|email|unique:tb_data_crms,email',
        ]);
        $params = $request->all();
        $crm = DataCrm::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function show(DataCrm $dataCrm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function edit(DataCrm $dataCrm)
    {
        $this->responseData['detail'] = $dataCrm;
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS;
        $admission = Admin::where('status', Consts::STATUS_ACTIVE)->where("admin_type",Consts::ADMIN_TYPE['admission'])->get();  
        $this->responseData['admission'] = $admission;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataCrm $dataCrm)
    {
        $request->validate([
            'area_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:tb_data_crms,phone,' . $dataCrm->id,
            'email' => 'required|email|unique:tb_data_crms,email,' . $dataCrm->id,
        ]);

        $params = $request->all();
        $dataCrm->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataCrm $dataCrm)
    {
        $dataCrm->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function importDataCrm(Request $request)
    {
        $params = $request->all();
        // Kiểm tra và validate file đầu vào
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if (!isset($params['file'])) {
            return redirect()->back()->with('errorMessage', __('Cần chọn file để Import!'));
        }

        try {
            // Import file
            $import = new DataCrmImport($params);
            Excel::import($import, request()->file('file'));

            return redirect()->back()->with('successMessage', 'Import data thành công');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Lỗi tại dòng " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->back()->with('errorMessage', implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            // Bắt lỗi chung khác
            return redirect()->back()->with('errorMessage', 'Lỗi khi import: ' . $e->getMessage());
        }
    }
}
