<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\VietQrService;
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
            $json_params = json_decode(json_encode($receipt->json_params), true);
            // Cập nhật lại thông tin
            $json_params['payment_deadline'] = $request->input('payment_deadline');
            $receipt->status = Consts::STATUS_RECEIPT['paid'];
            $receipt->total_paid = $request->input('total_paid');
            $receipt->total_due = $receipt->total_final - $request->input('total_paid');
            $receipt->cashier_id = $admin->id;
            $receipt->json_params = $json_params;

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

    public function print(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        $this->responseData['detail'] = $receipt;

        $receiptDetails = $receipt->receiptDetail->load('services_receipt'); // Nạp quan hệ service

        // Gộp các receiptDetail theo service_id và tính tổng amount và discount_amount
        $groupedDetails = $receiptDetails->groupBy('service_id')->map(function ($details) {
            return [
                'service' => optional($details->first()->services_receipt),
                'service_type' => optional($details->first()->services_receipt)->service_type, // Lấy service_type từ quan hệ service
                'total_amount' => $details->sum('amount'), // Tính tổng amount
                'total_discount_amount' => $details->sum('discount_amount'), // Tính tổng discount_amount
                'min_month' => $details->min('month'), // Tháng áp dụng nhỏ nhất
                'max_month' => $details->max('month'), // Tháng áp dụng lớn nhất
            ];
        });
        // Gộp theo service_type và tính tổng total_amount
        $groupedByServiceType = $groupedDetails->groupBy('service_type')->map(function ($details, $serviceType) {
            return [
                'service_type' => $serviceType, // Loại dịch vụ
                'total_amount' => $details->sum('total_amount'), // Tổng tiền của loại
                'services' => $details, // Chi tiết từng nhóm dịch vụ
            ];
        });
        // Lấy thông tin giảm trừ
        $listtServiceDiscoun = $groupedDetails
            ->filter(function ($detail) {
                return $detail['total_discount_amount'] > 0; // Lọc các dịch vụ có discount_amount > 0
            });



        $serviceMonthly = $groupedByServiceType->get('monthly', collect()); // Dịch vụ loại monthly
        $serviceYearly = $groupedByServiceType->get('yearly', collect()); // Dịch vụ loại monthly
        // Lấy các loại còn lại ngoài monthly và yearly
        $serviceOther = $groupedByServiceType
            ->reject(function ($item, $key) {
                return in_array($key, ['monthly', 'yearly']); // Loại trừ monthly và yearly
            })
            ->reduce(function ($carry, $item) {
                // Gộp các nhóm khác lại
                $carry['service_type'] = 'other'; // Đặt tên nhóm
                $carry['total_amount'] = ($carry['total_amount'] ?? 0) + $item['total_amount']; // Tính tổng
                $carry['services'] = ($carry['services'] ?? collect())->merge($item['services']); // Gộp chi tiết
                return $carry;
            }, []);



        // Lọc từng nhóm
        $this->responseData['groupedByServiceType'] = $groupedByServiceType;
        $this->responseData['listtServiceDiscoun'] = $listtServiceDiscoun;
        $this->responseData['serviceMonthly'] = $serviceMonthly;
        $this->responseData['serviceYearly'] = $serviceYearly;
        $this->responseData['serviceOther'] = $serviceOther;

        // For get QR code
        $bank_amount = $receipt->total_due;
        $bank_bin = optional($receipt->area)->json_params->bank_bin ?? null;
        $bank_stk = optional($receipt->area)->json_params->bank_stk ?? null;
        if ($bank_amount > 0 && $bank_bin && $bank_stk) {
            $this->responseData['qrCode'] = (new VietQrService())->generateQrImage(
                $bank_bin,
                $bank_stk,
                $bank_amount,
                $receipt->student->student_code . '_' . $receipt->student->first_name . ' ' . $receipt->student->last_name . '_' . $receipt->receipt_code
            );
        }
        return $this->responseView($this->viewPart . '.print');
    }
}
