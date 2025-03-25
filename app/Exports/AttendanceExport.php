<?php

namespace App\Exports;

use App\Models\Student;
use App\Consts;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function title(): string
    {
        return 'Chi tiết điểm danh';
    }
    public function collection()
    {
        return Attendance::getsqlAttendance($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Lớp học',
            'Học viên',
            'Bài tập về nhà',
            'Cập nhật',
            'Trạng thái',
            'Ghi chú trạng thái',
            'Ghi chú nhận xét (Giáo viên nhập)',
            'Ghi chú đào tạo',
        ];
    }
    public function map($row): array
    {
        static $stt = 0;$stt++;
        $note_status=""; 
        $is_homework=isset($row->is_homework)?__(Consts::IS_HOMEWORK[$row->is_homework]):"Chưa chọn";
        $status=isset($row->status)?__(Consts::ATTENDANCE_STATUS[$row->status]):"";

        if($row->status == Consts::ATTENDANCE_STATUS['attendant'])
            $note_status=$row->json_params->value ?? '';
        elseif($row->status == Consts::ATTENDANCE_STATUS['absent'])
            $note_status= (isset($row->json_params->value) && $row->json_params->value!="") ? __(Consts::OPTION_ABSENT[$row->json_params->value]):"";
        else
            $note_status= (isset($row->json_params->value) && $row->json_params->value!="") ? $row->json_params->value." phút" ?? '':"" ;

        return [
            $stt,
            $row->class->name ?? '' ,
            $row->student->name ?? ''.($row->student->admin_code ?? '' ),
            $is_homework,
            $row->updated_at,
            $status,
            $note_status ?? '' ,
            $row->note_teacher ?? '' ,
            $row->note ?? '' ,
        ];
    }
}
