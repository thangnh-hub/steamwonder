<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Exports\ReceiptExport;
use App\Http\Services\VietQrService;
use App\Http\Services\DataPermissionService;
use App\Http\Services\ReceiptService;
use App\Models\Receipt;
use App\Models\ReceiptTransaction;
use App\Models\Area;
use App\Models\Service;
use App\Models\Student;
use App\Models\PaymentCycle;
use App\Models\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptController extends Controller
{
    protected $day_start_receipt_yearly;

    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'receipt';
        $this->viewPart = 'admin.pages.receipt';
        $this->responseData['module_name'] = 'Quản lý TBP';
        $this->day_start_receipt_yearly = Carbon::createFromDate(null, 6, 1)->format('Y-m-d');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = Auth::guard('admin')->user();
        $params = $request->only(['keyword', 'status', 'area_id', 'type_receipt', 'student_id', 'created_at']);
        $params['permission_area'] = DataPermissionService::getPermisisonAreas($auth->id);
        $rows = Receipt::getSqlReceipt($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['areas'] = Area::all();
        $this->responseData['students'] = Student::getSqlStudent()->get();
        $this->responseData['status'] = Consts::STATUS_RECEIPT;
        $this->responseData['type_receipt'] = Consts::TYPE_RECEIPT;
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
        $this->responseData['due_date'] = Carbon::parse($detail->created_at)->endOfMonth()->format('Y-m-d');

        $this->responseData['payment_cycle'] = PaymentCycle::all();
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
            $json_params['due_date'] = $request->input('due_date');
            $receipt->status = Consts::STATUS_RECEIPT['paid'];
            $receipt->cashier_id = $admin->id;
            $receipt->admin_updated_id = $admin->id;
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
    public function approved(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        DB::beginTransaction();
        try {
            $receipt = Receipt::find($id);
            $receipt->status = Consts::STATUS_RECEIPT['approved'];
            $receipt->admin_updated_id = $admin->id;
            // Cập nhật trạng thái của receiptDetail nếu tồn tại
            if ($receipt->receiptDetail->count() > 0) {
                foreach ($receipt->receiptDetail as $item) {
                    $item->status = Consts::STATUS_RECEIPT['approved'];
                    $item->admin_updated_id = $admin->id;
                    $item->save();
                }
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
        // Cập nhật số dư kỳ trước
        $receipt->prev_balance = $prev_balance;
        // Cập nhật lại tổng tiền và số tiền còn phải thu
        $receipt->total_final = $receipt->total_amount - $receipt->total_discount - $receipt->prev_balance;
        $receipt->total_due = $receipt->total_final - $receipt->total_paid;
        $receipt->json_params = $json_params;
        $receipt->save();
        return $this->sendResponse($receipt, 'Cập nhật thành công!');
    }

    public function print(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        $this->responseData['detail'] = $receipt;

        $receiptDetails = $receipt->receiptDetail->load('services_receipt'); // Nạp quan hệ service
        // Gộp các receiptDetail theo service_id và tính tổng amount và discount_amount
        $groupDetails = $receiptDetails->groupBy('service_id')->map(function ($details) {
            return [
                'service' => optional($details->first()->services_receipt),
                'service_type' => optional($details->first()->services_receipt)->service_type, // Lấy service_type từ quan hệ service
                'total_amount' => $details->sum('amount'), // Tính tổng amount
                'total_discount_amount' => $details->sum('discount_amount'), // Tính tổng discount_amount
                'min_month' => $details->min('month'), // Tháng áp dụng nhỏ nhất
                'max_month' => $details->max('month'), // Tháng áp dụng lớn nhất
            ];
        });

        // Gộp theo service_type (Tháng năm, ... ) và tính tổng total_amount
        $groupByServiceType = $groupDetails->groupBy('service_type')->map(function ($details, $serviceType) {
            return [
                'service_type' => $serviceType, // Loại dịch vụ
                'total_amount' => $details->sum('total_amount'), // Tổng tiền của loại
                'services' => $details, // Chi tiết từng nhóm dịch vụ
            ];
        });


        // Gộp để lấy dịch vụ theo từng loại và ghi chú khác nhau để tính Discount
        $groupDetailsNote = $receiptDetails->groupBy(function ($detail) {
            return $detail->service_id . '-' . $detail->note; // Gộp theo service_id và note
        })->map(function ($details) {
            return [
                'service' => optional($details->first()->services_receipt),
                'service_type' => optional($details->first()->services_receipt)->service_type, // Lấy service_type từ quan hệ service
                'total_amount' => $details->sum('amount'), // Tính tổng amount
                'total_discount_amount' => $details->sum('discount_amount'), // Tính tổng discount_amount
                'min_month' => $details->min('month'), // Tháng áp dụng nhỏ nhất
                'max_month' => $details->max('month'), // Tháng áp dụng lớn nhất
                'note' => $details->first()->note, // Lấy ghi chú từ chi tiết đầu tiên
            ];
        });

        // Lấy thông tin giảm trừ có total_discount_amount > 0
        $listServiceDiscount = $groupDetailsNote
            ->filter(function ($detail) {
                return $detail['total_discount_amount'] > 0; // Lọc các dịch vụ có discount_amount > 0
            });

        // dd($listServiceDiscount);
        // dd($listServiceDiscount);
        $serviceMonthly = $groupByServiceType->get('monthly', collect()); // Dịch vụ loại monthly
        $serviceYearly = $groupByServiceType->get('yearly', collect()); // Dịch vụ loại monthly
        // Lấy các loại còn lại ngoài monthly và yearly
        $serviceOther = $groupByServiceType
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



        // Lấy từng từng nhóm
        $this->responseData['groupByServiceType'] = $groupByServiceType;
        $this->responseData['listServiceDiscount'] = $listServiceDiscount;
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


    // Cập nhật lại dịch vụ cho học sinh và tính lại phí cho học sinh
    public function updateStudentServiceAndFee(Request $request, ReceiptService $receiptService)
    {
        DB::beginTransaction();
        try {
            $receipt_id = $request->input('receipt_id');
            $student_id = $request->input('student_id');
            $student_services = $request->input('student_services');
            // Lấy thông tin học sinh và TBP
            $student = Student::find($student_id);
            $receipt = Receipt::find($receipt_id);
            // Cập nhật lại thông tin dịch vụ cho học sinh
            foreach ($student_services as $student_services_id => $item) {
                $studentService = StudentService::find($student_services_id);
                $studentService->payment_cycle_id = $item['payment_cycle_id'];
                $studentService->save();
            }
            // Lấy thông tin dịch vụ của học sinh
            $data['student_services'] = $student->studentServices()
                ->where('status', 'active')
                ->get();
            $data['enrolled_at'] = $receipt->period_start;
            // Xóa các TBP detail cũ
            $receipt->receiptDetail()->delete();
            // Tính lại phí cho học sinh
            $calcuReceipt = $receiptService->updateReceiptForStudent($receipt, $student, $data);
            DB::commit();
            return redirect()->back()->with('successMessage', __('Lưu thông tin thành công!'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    public function CrudReceiptTransaction(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $params = $request->only(['receipt_id', 'paid_amount', 'json_params']);
        $receipt_id = $request->input('receipt_id');
        $type = $request->input('type');
        $result = $message = '';
        switch ($type) {
            case 'create':
                $params['cashier'] = $admin->id;
                $params['admin_created_id'] = $admin->id;
                ReceiptTransaction::create($params);

                // Cập nhật lại số tiền trong bảng receipt
                $receipt = Receipt::find($receipt_id);
                // Lấy tổng tiền receipt_transaction
                $total_paid = $receipt->receiptTransaction->sum('paid_amount');
                $receipt->total_paid = $total_paid;
                $receipt->total_due = ($receipt->total_final - $total_paid > 0) ? $receipt->total_final - $total_paid : 0;
                $receipt->save();

                $result = "success";
                $message = 'Thêm mới thành công!';
                break;
            case 'update':
                break;
            case 'delete':
                break;
            default:
        }
        return $this->sendResponse($result, $message);
    }

    public function exportReceipt(Request $request)
    {
        $params = $request->all();
        $auth = Auth::guard('admin')->user();
        $params['permission_area'] = DataPermissionService::getPermisisonAreas($auth->id);

        return Excel::download(new ReceiptExport($params), 'Receipt.xlsx');
    }
}
