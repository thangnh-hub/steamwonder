<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentRequest;
use App\Models\PaymentRequestDetail;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Consts;
use App\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentRequestController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'payment_request';
    $this->viewPart = 'admin.pages.payment_request';
    $this->responseData['module_name'] = 'Quản lý đề nghị thanh toán';
  }
  public function index(Request $request)
  {

    $params = $request->all();
    // Get list post with filter params

    $rows = PaymentRequest::getSqlPaymentRequest($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    foreach ($rows as $paymentRequest) {
      $paymentRequest->total_money_vnd_finally =  ((int)$paymentRequest->total_money_vnd ?? 0) + ((int) $paymentRequest->total_vat_10_vnd ?? 0) + ((int) $paymentRequest->total_vat_8_vnd ?? 0)  - ($paymentRequest->total_money_vnd_advance ?? 0);
      $paymentRequest->total_money_euro_finally = ((int) $paymentRequest->total_money_euro ?? 0) + ((int) $paymentRequest->total_vat_10_euro ?? 0) + ((int) $paymentRequest->total_vat_8_euro ?? 0) - ($paymentRequest->total_money_euro_advance ?? 0);
    }
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;
    $this->responseData['status'] = Consts::PAYMENT_REQUEST_STATUS;
    $this->responseData['department'] = Department::get();

    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $this->responseData['status'] = Consts::PAYMENT_REQUEST_STATUS;
    $this->responseData['department'] = Department::get();
    $this->responseData['admin'] = Auth::guard('admin')->user();
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
      'content' => 'required',
      'total_money_vnd_advance' => 'numeric',
      'total_money_euro_advance' => 'numeric',
    ]);
    $params = $request->all();
    $params['user_id'] = Auth::guard('admin')->user()->id;
    $PaymentRequest = PaymentRequest::create($params);
    return redirect()->route($this->routeDefault . '.edit', $PaymentRequest->id)->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\PaymentRequest  $paymentRequest
   * @return \Illuminate\Http\Response
   */

  public function show(PaymentRequest $paymentRequest)
  {
    $this->responseData['detail'] = $paymentRequest;
    $this->responseData['paymentRequestDetail'] = $paymentRequest->paymentDetails()->get();
    $this->responseData['module_name'] = 'Đề nghị thanh toán';

    if (($paymentRequest->paymentDetails()->get())) {
      $total_money_vnd_before_vat =  ((int)$paymentRequest->total_money_vnd ?? 0) + ((int) $paymentRequest->total_vat_10_vnd ?? 0) + ((int) $paymentRequest->total_vat_8_vnd ?? 0);
      $total_money_euro_before_vat = ((int) $paymentRequest->total_money_euro ?? 0) + ((int) $paymentRequest->total_vat_10_euro ?? 0) + ((int) $paymentRequest->total_vat_8_euro ?? 0);

      $total_money_vnd_finally =  ((int)$paymentRequest->total_money_vnd ?? 0) + ((int) $paymentRequest->total_vat_10_vnd ?? 0) + ((int) $paymentRequest->total_vat_8_vnd ?? 0)  - ($paymentRequest->total_money_vnd_advance ?? 0);
      $total_money_euro_finally = ((int) $paymentRequest->total_money_euro ?? 0) + ((int) $paymentRequest->total_vat_10_euro ?? 0) + ((int) $paymentRequest->total_vat_8_euro ?? 0) - ($paymentRequest->total_money_euro_advance ?? 0);

      $this->responseData['total_money_vnd_finally'] = $total_money_vnd_finally;
      $this->responseData['total_money_euro_finally'] = $total_money_euro_finally;

      $this->responseData['total_money_vnd_before_vat'] = $total_money_vnd_before_vat;
      $this->responseData['total_money_euro_before_vat'] = $total_money_euro_before_vat;

      $this->responseData['total_money_vnd_finally_word'] = Helpers::convert_number_to_words_vi((int)$total_money_vnd_finally);
      $this->responseData['total_money_euro_finally_word'] = Helpers::convert_number_to_words_vi((int)$total_money_euro_finally);
    }
    if ($paymentRequest->is_entry == 0) return $this->responseView($this->viewPart . '.show');
    else {
      $this->responseData['entry_details'] = $paymentRequest->entry->entryDetails ?? null;
      $total_money_vnd_finally = ((int)$paymentRequest->total_money_vnd ?? 0) - ($paymentRequest->total_money_vnd_advance ?? 0);
      $this->responseData['total_money_vnd_finally'] = $total_money_vnd_finally;
      $this->responseData['total_money_vnd_finally_word'] = Helpers::convert_number_to_words_vi((int)$total_money_vnd_finally);
      return $this->responseView($this->viewPart . '.show_entry');
    }
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\PaymentRequest  $paymentRequest
   * @return \Illuminate\Http\Response
   */
  public function edit(PaymentRequest $paymentRequest)
  {
    if ($paymentRequest->status == Consts::PAYMENT_REQUEST_STATUS['paid']) {
      return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($paymentRequest->status) . ' không được phép sửa!');
    }

    $this->responseData['status'] = Consts::PAYMENT_REQUEST_STATUS;
    $this->responseData['type_khoan'] = Consts::PAYMENT_REQUEST_TYPE;
    $this->responseData['department'] = Department::get();
    $this->responseData['admin'] = Auth::guard('admin')->user();
    $this->responseData['detail'] = $paymentRequest;

    $this->responseData['paymentRequestDetail'] = $paymentRequest->paymentDetails()->get();

    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\PaymentRequest  $paymentRequest
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, PaymentRequest $paymentRequest)
  {
    if ($paymentRequest->status == Consts::PAYMENT_REQUEST_STATUS['paid']) {
      return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($paymentRequest->status) . ' không được phép sửa!');
    }

    $request->validate([
      'content' => 'required',
      'total_money_vnd_advance' => 'numeric',
      'total_money_euro_advance' => 'numeric',
    ]);


    DB::beginTransaction();
    try {
      $params = $request->except(['payment_detail']);
      $paymentRequest->fill($params);
      $paymentRequest->save();
      // Xóa toàn bộ khoản thanh toán cũ
      $paymentRequest->paymentDetails()->delete();

      $total_money_vnd = 0;
      $total_money_euro = 0;
      $total_vat_10_vnd = 0;
      $total_vat_8_vnd = 0;

      $total_vat_10_euro = 0;
      $total_vat_8_euro = 0;

      if (!empty($request->payment_detail)) {
        foreach ($request->payment_detail as $item) {
          $money_vnd = ($item['price_vnd'] ?? 0) * ($item['quantity'] ?? 1) * ($item['number_times'] ?? 1);
          $money_euro = ($item['price_euro'] ?? 0) * ($item['quantity'] ?? 1) * ($item['number_times'] ?? 1);

          PaymentRequestDetail::create([
            'payment_id' => $paymentRequest->id,
            'type_payment' => $item['type_payment'] ?? null,
            'date_arise' => $item['date_arise'] ?? null,
            'doc_number' => $item['doc_number'] ?? null,
            'content' => $item['content'] ?? null,
            'quantity' => $item['quantity'] ?? 1,
            'number_times' => $item['number_times'] ?? 1,
            'price_vnd' => $item['price_vnd'] ?? 0,
            'price_euro' => $item['price_euro'] ?? 0,
            'money_vnd' => $money_vnd,
            'money_euro' => $money_euro,
            'vat_10_number_vnd' => $item['vat_10_number_vnd'] ?? 0,
            'vat_8_number_vnd' => $item['vat_8_number_vnd'] ?? 0,
            'vat_10_number_euro' => $item['vat_10_number_euro'] ?? 0,
            'vat_8_number_euro' => $item['vat_8_number_euro'] ?? 0,
            'note' => $item['note'] ?? null,
          ]);

          // Tính tổng VAT
          $total_vat_10_vnd += ($item['vat_10_number_vnd'] ?? 0);
          $total_vat_8_vnd += ($item['vat_8_number_vnd'] ?? 0);
          $total_vat_10_euro += ($item['vat_10_number_euro'] ?? 0);
          $total_vat_8_euro += ($item['vat_8_number_euro'] ?? 0);

          // Tính tổng tiền dựa trên type_payment
          if (($item['type_payment'] ?? '') === Consts::PAYMENT_REQUEST_TYPE['thanhtoan']) {
            $total_money_vnd += $money_vnd;
            $total_money_euro += $money_euro;
          } elseif (($item['type_payment'] ?? '') === Consts::PAYMENT_REQUEST_TYPE['hoantra']) {
            $total_money_vnd -= $money_vnd;
            $total_money_euro -= $money_euro;
          }
        }

        // Cập nhật lại payment_requests
        $paymentRequest->update([
          'total_money_vnd' => $total_money_vnd,
          'total_money_euro' => $total_money_euro,
          'total_vat_10_vnd' => $total_vat_10_vnd,
          'total_vat_8_vnd' => $total_vat_8_vnd,
          'total_vat_10_euro' => $total_vat_10_euro,
          'total_vat_8_euro' => $total_vat_8_euro,
        ]);
      }

      DB::commit();
      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    } catch (Exception $ex) {
      // throw $ex;
      return redirect()->back()->with('errorMessage', $ex->getMessage());
      abort(422, __($ex->getMessage()));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\PaymentRequest  $paymentRequest
   * @return \Illuminate\Http\Response
   */
  public function destroy(PaymentRequest $paymentRequest)
  {
    if ($paymentRequest->status == Consts::PAYMENT_REQUEST_STATUS['paid']) {
      return redirect()->back()->with('errorMessage', 'Trạng thái ' . __($paymentRequest->status) . ' không được phép xóa!');
    }
    DB::beginTransaction();
    try {
      $paymentRequest->delete();
      $paymentRequest->paymentDetails()->delete();
      DB::commit();
      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    } catch (Exception $ex) {
      DB::rollBack();
      throw $ex;
    }
  }
  public function approve(Request $request)
  {
    try {
      $paymentRequest = PaymentRequest::find($request->id);
      if (isset($paymentRequest)) {
        if ($paymentRequest->status == Consts::PAYMENT_REQUEST_STATUS['new']) {
          $updateResult =  $paymentRequest->update([
            'status' => Consts::PAYMENT_REQUEST_STATUS['paid'],
            'approved_id' => Auth::guard('admin')->user()->id,
          ]);
        }
        if ($updateResult) {
          session()->flash('successMessage', __('Duyệt đề nghị thành công!'));
          return $this->sendResponse("", 'success');
        }

        session()->flash('errorMessage', __('Duyệt đề nghị không thành công! Bạn không có quyền thao tác dữ liệu!'));
        return $this->sendResponse('', __('No records available!'));
      }

      session()->flash('errorMessage', __('Duyệt đề nghị không thành công! Bạn không có quyền thao tác dữ liệu!'));
      return $this->sendResponse('', __('No records available!'));
    } catch (Exception $ex) {
      // throw $ex;
      abort(422, __($ex->getMessage()));
    }
  }
}
