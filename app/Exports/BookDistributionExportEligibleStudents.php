<?php

namespace App\Exports;

use App\Models\WareHouseEntry;
use App\Models\HistoryBookDistribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Services\BookDistributionService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class BookDistributionExportEligibleStudents implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {

        $this->params = $params;
    }
    public function collection()
    {
        $service = new BookDistributionService();
        $all_students = $service->listEligibleStudents($this->params)->get();
        return $all_students;
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã học viên',
            'Họ và tên',
            'CB tuyển sinh',
            'Loại hợp đồng',
            'Khu vực',
            'Trình độ',
            'Lớp đã học',
            'Lớp đang học',
            'Ngày vào lớp',
            'Sách đã lấy',
            'Sách chưa lấy',
            'Các GD nộp tiền',
            'Điều kiện'
        ];
    }
    public function map($row): array
    {
        $this->stt++;

        $day_in_class = '';
        $list_class = $row->student->classs->map(function ($class) use ($row, &$day_in_class) {
            if ($row->class_id == $class->id && isset($class->pivot->json_params)) {
                $day_in_class = json_decode($class->pivot->json_params)->day_in_class ?? '';
            }
            // Gắn cờ in đậm nếu trùng class_id
            $class_name = $class->name . ' (' . __($class->pivot->status ?? '') . ')';
            return [
                'name' => $class_name,
                'bold' => $row->class_id == $class->id, // Đánh dấu nếu trùng class_id
            ];
        });

        // Chuyển danh sách thành chuỗi để hiển thị trong Excel
        $list_class_plain = $list_class->pluck('name')->implode("\n");

        $list_book = $row->student->history_book_active->map(function ($his) {
            return $his->product->name;
        })->implode("\n");

        $list_revenue = $row->student->AccountingDebt->map(function ($revenue) {
            return $revenue->type_revenue;
        })->implode("\n");

        return [
            $this->stt,
            $row->student->admin_code ?? '',
            $row->student->name ?? '',
            $row->student->admission->admin_code ?? '',
            $row->student->json_params->contract_type ?? '',
            $row->student->area->code ?? '',
            $row->level->name ?? '',
            $list_class_plain,
            $row->class->name,
            $day_in_class != '' ? Carbon::parse($day_in_class)->format('d/m/Y') : '',
            $list_book,
            $row->product->name ?? '',
            $list_revenue,
            __($row->status),
        ];
    }
}
