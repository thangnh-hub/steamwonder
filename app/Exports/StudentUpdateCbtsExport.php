<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;



class StudentUpdateCbtsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        $user = Auth::guard('admin')->user()->id;
        $params['list_admission_id'] = DataPermissionService::getPermissionUsersAndSelfAll($user);
        return Student::getsqlStudent($params)->get();
    }

    public function headings(): array
    {
        return [
            'Mã HV',
            'Họ và tên',
            'CCCD',
            'Giới tính',
            'Mã KV',
            'Mã CBTS',
        ];
    }
    public function map($user): array
    {
        $staff = StaffAdmission::find($user->admission_id);
        $staff_name = isset($staff) ? $staff->admin_code : "";

        $area = Area::find($user->area_id);
        $area_code = isset($area) ? $area->code : "";

        return [
            $user->admin_code,
            $user->name ?? "",
            $user->json_params->cccd ?? "",
            __($user->gender),
            $area_code,
            $staff_name,
        ];
    }
}
