<?php

namespace App\Exports;

use App\Models\tbClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;
use App\Http\Services\AdminService;




class AdminExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return AdminService::getAdmins($this->params, false);
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã',
            'Họ tên',
            'Email',
            'Khu vực - Chi nhánh',
            'Phòng ban',
            'Loại người dùng',
        ];
    }
    public function map($user): array
    {
        $this->stt++;
        return [
            $this->stt,
            $user->admin_code ?? '',
            $user->name ?? "",
            ($user->email ?? "") . '/' . ($user->phone != '' ? ' / ' . $user->phone : ''),
            $user->area->name ?? "",
            $user->department->name ?? '',
            __($user->admin_type),
        ];
    }
}
