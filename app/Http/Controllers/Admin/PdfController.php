<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PdfController extends Controller
{
    public static function generatePDF(Request $request)
    {
        $view = $request->view;
        $data = json_decode($request->data, true);
        $pdf = PDF::loadView($view, $data);
        //Nếu muốn hiển thị file pdf theo chiều ngang
        // $pdf->setPaper('A4', 'landscape');

        //Nếu muốn download file pdf
        return $pdf->download($request->namePDF ?? 'BangDiem.pdf');

        //Nếu muốn preview in pdf
        //return $pdf->stream('myPDF.pdf');
    }
}
