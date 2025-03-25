<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Services\AdminService;

class UserExport implements FromCollection, WithHeadings, WithMapping 
{
    protected $params;
    public function __construct($params) {
        $this->params = $params;
    }
    public function collection()
    {
        return AdminService::getAdmins($this->params,false);
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [ 'Mã CBTS', 
        'Email',
        'Tên', 
        'SĐT',
        'Quyền'
    ];
    }
    public function map($user): array
    {
        return [
            __($user->admin_code),
            $user->email,
            $user->name,
            $user->phone,
            $user->role_name,
        ];
    }
}
