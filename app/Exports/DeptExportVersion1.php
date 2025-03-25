<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\tbClass;
use App\Models\UserClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DeptExportVersion1 implements FromCollection, WithHeadings, WithMapping, WithTitle
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
        $params_class['level_id'] = 4;
        $this->params['array_class_id'] = tbClass::getSqlClassEnding($params_class)
            ->havingRaw('total_schedules - total_attendance <= 15')
            ->havingRaw('total_schedules - total_attendance > 0')
            ->get()->pluck('id')->toArray();
            return UserClass::getSqlUserClassDept($this->params)->orderBy('class_id',"desc")->get();
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
            'Lớp học',
            'Trình độ',
            'Khóa',
            'Đã học',
            'Thực tế',
            'Còn lại',
            'Xác nhận'
        ];
    }
    public function map($row): array
    {
        static $stt = 0;
        $stt++;

        return [
            $stt,
            $row->user->admin_code,
            $row->user->name ?? '',
            $row->user->json_params->cccd ?? '',
            __($row->user->gender??""),
            $row->user->json_params->contract_type??"",
            $row->area_name ??"",
            $row->class->name ??"",
            $row->class->level->name ??"",
            $row->course_name??"",
            $row->total_attendance,
            $row->total_schedules,
            $row->total_schedules - $row->total_attendance,
            __($row->user->ketoan_xacnhan),
        ];
    }
}
