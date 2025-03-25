<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TrialStudentExport implements FromCollection, WithHeadings, WithMapping
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
            'Mã học thử',
            'Mã HV mới',
            'Họ và tên',
            'CCCD',
            'Giới tính',
            'Khu vực',
            'Lớp học',
            'CB tuyển sinh',
            'Tình trạng học',
        ];
    }
    public function map($user): array
    {
        $staff = StaffAdmission::find($user->admission_id);
        $staff_name = isset($staff) ? $staff->admin_code : "";

        $area = Area::find($user->area_id);
        $area_code = isset($area) ? $area->code : "";

        $class = '';
        $new_code = isset($user->json_params->trial_code) && $user->json_params->trial_code != '' ? $user->admin_code : '';

        if (isset($user->classs)) {
            foreach ($user->classs as $i) {
                $class .= $i->name . ', ';
            }
        }


        return [
            isset($user->json_params->trial_code) && $user->json_params->trial_code != '' ? $user->json_params->trial_code : $user->admin_code,
            $new_code,
            $user->name ?? "",
            __($user->json_params->cccd ?? ""),
            __($user->gender),
            $area_code,
            $class,
            $staff_name,
            $user->status_study_name ?? 'Chưa cập nhật',
        ];
    }
}
