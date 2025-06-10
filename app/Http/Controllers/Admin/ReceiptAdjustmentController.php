<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\ReceiptAdjustment;
use App\Models\Student;
use App\Models\tbClass;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use Carbon\Carbon;

class ReceiptAdjustmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'receipt_adjustment';
        $this->viewPart = 'admin.pages.receipt_adjustment';
        $this->responseData['module_name'] = 'Quản lý đối soát TBP';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'type','month']);
        $rows = ReceiptAdjustment::getSqlReceiptAdjustment($params)->orderBy('id','DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['students'] = Student::all();
        $this->responseData['status'] = Consts::STATUS_RECEIPT_DETAIL;
        $this->responseData['type'] = Consts::TYPE_RECEIPT_ADJUSTMENT;
        $this->responseData['list_class'] =  tbClass::orderBy('id', 'desc')->get();
        $this->responseData['list_area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
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
        $this->responseData['students'] = Student::all();
        $this->responseData['type'] = Consts::TYPE_RECEIPT_ADJUSTMENT;
        $this->responseData['module_name'] = "Thêm mới Truy thu/Hoàn trả";
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
            'student_id' => 'required',
            'type' => 'required',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;
        ReceiptAdjustment::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReceiptAdjustment  $receiptAdjustment
     * @return \Illuminate\Http\Response
     */
    public function show(ReceiptAdjustment $receiptAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceiptAdjustment  $receiptAdjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceiptAdjustment $receiptAdjustment)
    {
        // $receiptAdjustment = Policies::find($id);
        $this->responseData['students'] = Student::all();
        $this->responseData['type'] = Consts::TYPE_RECEIPT_ADJUSTMENT;
        $this->responseData['module_name'] = "Chỉnh sửa Truy thu/Hoàn trả";
        $this->responseData['detail'] = $receiptAdjustment;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceiptAdjustment  $receiptAdjustment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReceiptAdjustment $receiptAdjustment)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'student_id' => 'required',
            'type' => 'required',
        ]);
        $params = $request->all();
        if ($receiptAdjustment->receipt_id != '') {
            if ($receiptAdjustment->receipt->status != Consts::STATUS_RECEIPT['pending']) {
                return redirect()->back()->with('errorMessage', __('Đối soát đã gắn vào TBP đã duyệt'));
            }
        }
        $params['admin_updated_id'] = $admin->id;
        $receiptAdjustment->update($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceiptAdjustment  $receiptAdjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReceiptAdjustment $receiptAdjustment)
    {
        if ($receiptAdjustment->receipt_id != '') {
            if ($receiptAdjustment->receipt->status != Consts::STATUS_RECEIPT['pending']) {
                return redirect()->back()->with('errorMessage', __('Đối soát đã gắn vào TBP đã duyệt'));
            }
        }
        $receiptAdjustment->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
