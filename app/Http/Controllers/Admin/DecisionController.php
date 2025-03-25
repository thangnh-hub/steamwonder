<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\AdminService;
use App\Models\Language;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Decision;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Imports\DecisionImport;
use Illuminate\Support\Facades\App;
use stdClass;

class DecisionController extends Controller
{
    private $adminService;
    public function __construct()
    {
        $this->adminService = new AdminService();
        $this->routeDefault  = 'decisions';
        $this->viewPart = 'admin.pages.decisions';
        $this->responseData['module_name'] = __('Quản lý đơn biến động học viên');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = Decision::getsqlDecision($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] =  $params;
        $this->responseData['type'] = Consts::DECISION_TYPE;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->responseData['type'] = Consts::DECISION_TYPE;
        $this->responseData['students'] = Student::where('admin_type', 'student')->where('status', 'active')->get();

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
            'code' => 'required',
            'json_params' => "required",
            'active_date' => "required",
            'is_type' => "required",
        ]);
        $params = $request->all();
        $id = $params['json_params']['student']['id'];
        $student = Student::find($id);
        if (empty($student)) {
            return redirect()->back()->with('errorMessage', __('Học viên không tồn tại! Vui lòng kiểm tra lại thông tin!'));
        }
        $params['json_params']['student']['name'] = $student->name;
        $params['json_params']['student']['admin_code'] = $student->admin_code;

        $params['signer'] = Auth::guard('admin')->user()->name;

        Decision::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Decision $decision)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Decision $decision)
    {
        $this->responseData['type'] = Consts::DECISION_TYPE;
        $this->responseData['detail'] = $decision;
        $this->responseData['students'] = Student::where('admin_type', 'student')->where('status', 'active')->get();

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Decision $decision)
    {
        $request->validate([
            'code' => 'required',
            'json_params' => "required",
            'active_date' => "required",
            'is_type' => "required",
        ]);
        dd($request->all());
        $params = $request->all();
        $id = $params['json_params']['student']['id'];
        $student = Student::find($id);
        if (empty($student)) {
            return redirect()->back()->with('errorMessage', __('Học viên không tồn tại! Vui lòng kiểm tra lại thông tin!'));
        }
        $params['json_params']['student']['name'] = $student->name;
        $params['json_params']['student']['admin_code'] = $student->admin_code;
        $arr_insert = $params;
        $decision->fill($arr_insert);
        $decision->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Decision $decision)
    {

        $decision->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function importDecision(Request $request)
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
            $import = new DecisionImport($params);
            Excel::import($import, request()->file('file'));

            return redirect()->back()->with('successMessage', 'Import đơn biến động thành công');
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
