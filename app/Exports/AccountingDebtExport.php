<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use App\Models\Level;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccountingDebtExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        if (isset($this->params['from_date']) && $this->params['from_date'] != '') {
            $this->params['from_date_official'] = Carbon::parse($this->params['from_date'])->subDays(150)->format('Y-m-d');
        }
        if (isset($this->params['to_date']) && $this->params['to_date'] != '') {
            $this->params['to_date_official'] = Carbon::parse($this->params['to_date'])->subDays(150)->format('Y-m-d');
        }
        return Student::getsqlStudentAccounting($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã Học viên',
            'Họ và tên',
            'CCCD',
            'Giới tính',
            'Khu vực',
            'Lớp',
            'Lớp đang học',
            'Khóa học',
            'Trình độ',
            'Ngày học chính thức',
            'Số ngày đã học chính thức',
            'Ngày công nợ đến hạn',
            'CBTS',
            'Tình trạng',
            'Tình trạng học',
            'Loại hợp đồng',
            'Hợp đồng',
            'Version',
            'Tài chính',
            'Ghi chú GD',
            // 'Trạng thái',
        ];
    }
    private $index = 1;
    public function map($user): array
    {
        $staff = StaffAdmission::find($user->admission_id);
        $staff_name = isset($staff) ? $staff->admin_code : "";

        $area = Area::find($user->area_id);
        $area_code = isset($area) ? $area->code : "";
        $list_class = '';
        $current_class = '';
        foreach ($user->classs as $i) {
            if ($i->status == 'dang_hoc') {
                $current_class = $i->name;
            }
            $list_class .= $i->name . '(' . __($i->pivot->status ?? '') . ')' . "\n";
        }
        $list_class = rtrim($list_class, "\n");

        $note = '';
        $type_revenue = '';
        foreach ($user->AccountingDebt as $val) {
            $note .= $val->json_params->note . ';';
            $type_revenue .= __($val->type_revenue) . ';';
        };
        rtrim(';', $note);
        rtrim(';', $type_revenue);
        $level = null;
        if ($user->level_id == null || $user->level_id == '') {
            $level = Level::find(1);
        } elseif ($user->level_id < 6) {
            $level = Level::find($user->level_id + 1);
        }

        return [
            $this->index++,
            $user->admin_code,
            $user->name ?? "",
            $user->json_params->cccd ?? "",
            __($user->gender),
            $area_code,
            $list_class,
            $current_class,
            $user->course->name ?? '',
            $level->name ?? ($user->level->name ?? ''),
            $user->day_official != '' ? date('d-m-Y', strtotime($user->day_official)) : '',
            Carbon::parse($user->day_official)->diffInDays(Carbon::today()),
            $user->day_official != '' ? Carbon::parse($user->day_official)->addDays(150)->format('d-m-Y') : '',
            $staff_name,
            __($user->state),
            __($user->status_study_name),
            $user->json_params->contract_type ?? '',
            $user->json_params->contract_status ?? '',
            $user->version ?? '',
            $type_revenue,
            $note,
            // isset($user->json_params->status_accounting_debt) && $user->json_params->status_accounting_debt == 1 ? 'Đã thanh toán TC' : '',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('G')->getAlignment()->setWrapText(true); // 'G' là cột chứa list_class
    }
}
