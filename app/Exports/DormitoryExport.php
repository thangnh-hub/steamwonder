<?php

namespace App\Exports;

use App\Models\Dormitory_user;
use App\Models\Staff;
use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DormitoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return Dormitory_user::getSqlDormitoryUser($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã HV',
            'Họ tên',
            'CBTS',
            'Giới tính',
            'Trạng thái HV',
            'Khóa',
            'Khu vực',
            'Phòng',
            'Trạng thái',
            'Đơn nguyên',
            'Ngày vào KTX',
            'Ngày ra KTX',
            'Ngày hết hạn KTX',
            'Đơn vào KTX',
            'Ghi chú'
        ];
    }
    public function map($user): array
    {
        $staff = Staff::find($user->admission_id ?? 0);
        $course = Course::find($user->course_id ?? 0);
        $this->stt++;
        return [
            $this->stt,
            $user->admin_code ?? '',
            $user->user_name ?? "",
            $staff->name ?? "",
            __($user->user_gender??''),
            $user->student->StatusStudent->name ?? '',
            $course->name ?? "",
            $user->dormitory->area->code ?? "",
            $user->dormitory->name ?? "",
            __($user->status ?? ""),
            $user->dormitory->don_nguyen??'',
            $user->time_in != '' ? date('d/m/Y', strtotime($user->time_in)) : '',
            $user->time_out != '' ? date('d/m/Y', strtotime($user->time_out)) : '',
            $user->time_expires != '' ? date('d/m/Y', strtotime($user->time_expires)) : '',
            $user->json_params->don_vao ?? '',
            $user->json_params->ghi_chu ?? '',
        ];
    }
}
