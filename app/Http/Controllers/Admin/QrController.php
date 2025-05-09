<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\VietQrService;
use Illuminate\Support\Facades\Request;

class QrController extends Controller
{
    public function showQr(VietQrService $vietQr)
    {
        $qrBase64 = $vietQr->generateQrImage('970407', '26266886', 100000, 'THANH TOAN HOA DON HOC PHI THANG 5');

        return view('qr-view', compact('qrBase64'));
    }
}
