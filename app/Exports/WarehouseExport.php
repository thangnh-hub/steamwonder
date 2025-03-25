<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarehouseExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rows;
    private $stt = 1;
    public function __construct($rows)
    {
        $this->rows = $rows;
    }
    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            ['STT', 'Tên TS', 'Danh mục', 'Loại', 'ĐVT', 'Nhập', '', '', 'Xuất', '', '', 'Điều chuyển', '', 'Thu hồi', '', '', 'Tồn kho', '', ''],
            ['', '', '', '', '', 'Số lượng (A)', 'Đơn giá', 'Thành tiền', 'Số lượng (B)', 'Đơn giá', 'Thành tiền', 'SL giao (C)', 'SL nhận (D)', 'Số lượng thu hồi', 'Đầu kỳ (E)', 'Cuối kỳ (F)', 'Hiện tại'],
        ];
    }
    public function map($row): array
    {
        return [
            $this->stt++,             // STT
            $row->product->name ?? '',           // Tên TS
            $row->product->category_product->name ?? '',       // Danh mục
            __($row->product->warehouse_type ?? ''),           // Loại
            __($row->product->unit ?? ''),           // ĐVT
            $row->nhap_kho_quantity ?? '',     // Số lượng nhập (A)
            isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0,   // Đơn giá nhập
            isset($row->nhap_kho_subtotal_money) && is_numeric($row->nhap_kho_subtotal_money) ? number_format($row->nhap_kho_subtotal_money, 0, ',', '.') : 0,   // Thành tiền nhập
            $row->xuat_kho_quantity ?? '',     // Số lượng xuất (B)
            isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0,   // Đơn giá xuất
            isset($row->xuat_kho_subtotal_money) && is_numeric($row->xuat_kho_subtotal_money) ? number_format($row->xuat_kho_subtotal_money, 0, ',', '.') : 0,   // Thành tiền xuất
            $row->dieu_chuyen_giao_quantity ?? '',   // SL giao (C)
            $row->dieu_chuyen_nhan_quantity ?? '',    // SL nhận (D)
            $row->thu_hoi_quantity ?? '',  // Số lượng thu hồi
            $row->ton_kho_truoc_ky_quantity,    // Tồn đầu kỳ (E)
            $row->ton_kho_trong_ky_quantity,      // Tồn cuối kỳ (F)
            $row->ton_kho_quantity ,  // Tồn hiện tại
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet;

                // Gộp ô tiêu đề
                $sheet->mergeCells('A1:A2'); // STT
                $sheet->mergeCells('B1:B2'); // Tên TS
                $sheet->mergeCells('C1:C2'); // Danh mục
                $sheet->mergeCells('D1:D2'); // Loại
                $sheet->mergeCells('E1:E2'); // ĐVT
                $sheet->mergeCells('F1:H1'); // Nhập
                $sheet->mergeCells('I1:K1'); // Xuất
                $sheet->mergeCells('L1:M1'); // Điều chuyển
                $sheet->mergeCells('N1:N2'); // Thu hồi
                $sheet->mergeCells('O1:Q1'); // Tồn kho

                // Căn chỉnh ô
                $sheet->getStyle('A1:Q2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}
