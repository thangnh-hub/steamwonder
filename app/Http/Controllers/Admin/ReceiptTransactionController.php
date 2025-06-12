<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\ReceiptTransaction;

class ReceiptTransactionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'receipt_transaction';
        $this->viewPart = 'admin.pages.receipt_transaction';
        $this->responseData['module_name'] = 'Quản lý thanh toán TBP';
    }

    public function index(Request $request)
    {
        $params = $request->only(['keyword','keyword_student', 'from_date', 'to_date']);
        $rows = ReceiptTransaction::getSqlReceiptTransaction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.index');
    }
}
