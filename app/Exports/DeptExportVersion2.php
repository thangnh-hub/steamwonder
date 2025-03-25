<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\tbClass;
use App\Models\UserClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DeptExportVersion2 implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function title(): string
    {
        return 'Công nợ học viên';
    }
    public function collection()
    {
        $this->params['day_official'] = true;
        return Student::getsqlStudent($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã Học Viên',
            'Họ và tên',
            'CCCD',
            'Giới tính',
            'Loại hợp đồng',
            'Khu vực',
            'Khóa',
            'Ngày vào học chính thức',
            'Số ngày đã học',
            'Tình trạng học',
            'Xác nhận'
        ];
    }
    public function map($row): array
    {
        static $stt = 0;
        $stt++;

        return [
            $stt,
            $row->admin_code,
            $row->name ?? '',
            $row->json_params->cccd ?? '',
            __($row->gender??""),
            $row->json_params->contract_type??"",
            $row->area_name ??"",
            $row->course->name??"",
            $row->day_official!=""? date("d-m-Y",strtotime($row->day_official)):"Chưa cập nhật",
            $row->days_since_official,
            __($row->status_study_name ?? 'Chưa cập nhật'),
            __($row->ketoan_xacnhan),
        ];
    }
}
