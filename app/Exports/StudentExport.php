<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return Student::getsqlStudent($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'Mã SV',
            'Mã CBTS',
            'Mã KV',
            'Email',
            'SĐT',
            'Họ và tên',
            // 'Tên đệm',
            // 'Tên',
            'Địa chỉ',
            'Ngày sinh',
            'Giới tính',
            'Hình thức đào tạo',
            'Họ tên cha',
            'Sđt cha',
            'Họ tên mẹ',
            'Sđt mẹ',
            'CCCD',
            'Ngày cấp',
            'Cấp bởi',
            'Loại hợp đồng',
            'Trạng thái hợp đồng',
            'Tình trạng thực hiện hợp đồng',
            'Lớp học',
            'Khóa học',
            'Tình trạng',
            'Tình trạng học',
            'Version',
        ];
    }
    public function map($user): array
    {
        $staff = StaffAdmission::find($user->admission_id);
        $staff_name = isset($staff) ? $staff->admin_code : "";

        $area = Area::find($user->area_id);
        $area_code = isset($area) ? $area->code : "";

        // Lấy danh sách lớp học và ghép theo dòng mới
        $class_list = $user->classs->map(function ($class) {
            return $class->name . ' (' . __($class->pivot->status ?? '') . ')';
        })->implode("\n"); // Dùng "\n" để xuống dòng

        return [
            __($user->admin_code),
            $staff_name,
            $area_code,
            $user->email,
            $user->phone,
            $user->name ?? "",
            // $user->json_params->middle_name ?? "",
            // $user->json_params->first_name ?? "",
            $user->json_params->address ?? "",
            $user->birthday ? date('d/m/Y', strtotime($user->birthday)) : '',
            __($user->gender),
            __($user->json_params->forms_training ?? ""),
            __($user->json_params->dad_name ?? ""),
            __($user->json_params->dad_phone ?? ""),
            __($user->json_params->mami_name ?? ""),
            __($user->json_params->mami_phone ?? ""),
            __($user->json_params->cccd ?? ""),
            __($user->json_params->date_range ?? ""),
            __($user->json_params->issued_by ?? ""),
            __($user->json_params->contract_type ?? ""),
            __($user->json_params->contract_status ?? ""),
            __($user->json_params->contract_performance_status ?? ""),
            $class_list,
            $user->course->name ?? '',
            __($user->state ?? ''),
            __($user->status_study_name ?? 'Chưa cập nhật'),
            __($user->version ?? ''),
        ];
    }
}
