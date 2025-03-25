<?php

namespace App\Exports;

use App\Consts;
use App\Models\Dormitory_user;
use App\Models\Staff;
use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DormitoryReportExportStudent implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return Dormitory_user::getSqlDormitoryUser($this->params)->whereNotNull('id_dormitory')->get();
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
            'Phòng',
            'Trạng thái',
            'Ngày vào KTX',
            'Ngày ra KTX',
        ];
    }
    public function map($user): array
    {
        $staff = Staff::find($user->admission_id ?? 0);
        $this->stt++;
        return [
            $this->stt,
            $user->admin_code ?? '',
            $user->user_name ?? "",
            $staff->name ?? "",
            __($user->user_gender??''),
            $user->dormitory->name ?? "",
            ($user->time_out == '' || date('Y-m', strtotime($user->time_out)) > $this->params['months_come_leave'])?__(Consts::STATUS_DORMITORY_USER['already']):__(Consts::STATUS_DORMITORY_USER['leave']),
            $user->time_in != '' ? date('d/m/Y', strtotime($user->time_in)) : '',
            $user->time_out != '' ? date('d/m/Y', strtotime($user->time_out)) : '',
        ];
    }
}
