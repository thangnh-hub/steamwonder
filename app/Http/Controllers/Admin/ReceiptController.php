<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Receipt;
use App\Models\Area;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;


class ReceiptController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'receipt';
        $this->viewPart = 'admin.pages.receipt';
        $this->responseData['module_name'] = 'Quản lý TBP';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['keyword', 'status', 'area_id', 'type']);
        $rows = Receipt::getSqlReceipt($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        $detail = $receipt;
        $this->responseData['detail'] = $detail;
        $this->responseData['module_name'] = 'Thanh toán TBP';
        return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        //
    }

    public function viewIndex($id)
    {
        $detail = Receipt::find($id);
        $result['view'] = view($this->viewPart . '.view', compact('detail'))->render();
        return $this->sendResponse($result, __('Lấy thông tin thành công!'));
    }
    public function payment(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        DB::beginTransaction();
        try {
            $receipt = Receipt::find($id);
            $receipt->status = Consts::STATUS_RECEIPT['paid'];
            $receipt->total_paid = $receipt->total_amount;
            $receipt->cashier_id = $admin->id;

            // Cập nhật trạng thái của prev_receipt nếu tồn tại
            if ($receipt->prev_receipt) {
                $receipt->prev_receipt->status = Consts::STATUS_RECEIPT['completed'];
                $receipt->prev_receipt->save(); // Lưu thay đổi
            }
            $receipt->save();
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    public function updateJsonExplanation(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        $prev_balance = $request->input('prev_balance') ?? 0;
        $explanation = $request->input('explanation');
        $json_params = json_decode(json_encode($receipt->json_params), true);
        $json_params['explanation'] = $explanation;
        $receipt->prev_balance = $prev_balance;
        $receipt->json_params = $json_params;
        $receipt->save();
        return $this->sendResponse('success', 'Cập nhật thành công!');
    }
}
