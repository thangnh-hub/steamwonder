<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Receipt;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $params;

    /**
     * Create a new export instance.
     *
     * @param array $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = Receipt::getSqlReceipt($this->params)->whereIn('tb_receipt.area_id', $this->params['permission_area'] ?? [])->get();

        return $rows;
    }

    /**
     * Define the headings for the exported Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            __('Mã TBP'),
            __('Tên TBP'),
            __('Loại TBP'),
            __('Mã học sinh'),
            __('Tên học sinh'),
            __('Lớp'),
            __('Khu vực'),
            __('Thành tiền'),
            __('Tổng giảm trừ'),
            __('Số dư kỳ trước'),
            __('Tổng tiền thực tế'),
            __('Đã thu'),
            __('Cần thu'),
            __('Trạng thái'),
            __('Ghi chú'),
            __('Người tạo'),
            __('Ngày tạo')
        ];
    }

    /**
     * Apply styles to the headings.
     *
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Hàng 1 in đậm
        ];
    }

    /**
     * Map the user data to the columns defined in the headings.
     *
     * @param mixed $user
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->receipt_code ?? '',
            $row->receipt_name ?? '',
            __($row->type_receipt) ?? '',
            (optional($row->student)->student_code ?? ''),
            (optional($row->student)->first_name ?? '') . ' ' . (optional($row->student)->last_name ?? ''),
            optional($row->student->currentClass)->name ?? '',
            optional($row->area)->name ?? '',
            $row->total_amount ?? '',
            $row->total_discount ?? '',
            $row->prev_balance ?? '',
            $row->total_final ?? '',
            $row->total_paid ?? '',
            $row->total_due ?? '',
            __($row->status ?? ''),
            $row->note ?? '',
            optional($row->adminCreated)->name ?? '',
            $row->created_at ? $row->created_at->format('d/m/Y') : ''
        ];
    }
}
