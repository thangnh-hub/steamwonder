<?php

namespace App\Exports;

use App\Models\tbClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;



class ClassExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return tbClass::getSqlClass($this->params)->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã lớp',
            'Tiêu đề',
            'Khu vực - Chi nhánh',
            'Phòng',
            'Sĩ số',
            'Hệ đào tạo',
            'Nhóm tuổi',
            'Năm cuối',
        ];
    }
    public function map($user): array
    {
        $this->stt++;
        return [
            $this->stt,
            $user->code ?? '',
            $user->name ?? "",
            $user->area->name ?? "",
            $user->room->name ?? '',
            count($user->students) . '/' . $user->slot,
            $user->education_programs->name ?? '',
            $user->education_ages->name ?? '',
            $user->is_lastyear ?? '',
        ];
    }
}
