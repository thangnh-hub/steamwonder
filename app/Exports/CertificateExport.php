<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\tbClass;
use App\Models\Teacher;
use App\Models\Certificate;
use App\Http\Services\DataPermissionService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Consts;


class CertificateExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        $admin = Auth::guard('admin')->user();
        $student_ids = DataPermissionService::getPermissionStudents($admin->id); // Danh sách id_hocvien được xem
        $teacher_id = $admin->id;
        if ($admin->admin_type == 'teacher' || $admin->admin_type == 'admission') {
            $rows_all = Certificate::getSqlCertificate($this->params)
                ->where(function ($query) use ($student_ids, $teacher_id) {
                    $query->whereIn('tb_certificate.student_id', $student_ids)
                        ->orWhere('tb_certificate.teacher_id', $teacher_id);
                })
                ->get();
        }
        // Còn lại sẽ lấy cả các bản ghi student_id là null
        else {
            $rows_all = Certificate::getSqlCertificate($this->params)
                ->where(function ($query) use ($student_ids, $teacher_id) {
                    $query->whereIn('tb_certificate.student_id', $student_ids)
                        ->orWhereNull('tb_certificate.student_id')
                        ->orWhere('tb_certificate.teacher_id', $teacher_id);
                })
                ->get();
        }

        return $rows_all;
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã HV',
            'Họ và tên',
            'Lớp',
            'Cơ sở',
            'Hình thức thi',
            'Tổng KN',
            'Nghe',
            'Ngày báo điểm nghe',
            'Nói',
            'Ngày báo điểm nói',
            'Đọc',
            'Ngày báo điểm đọc',
            'Viết',
            'Ngày báo điểm viết',
            'GVCN',
            'Ghi chú',
        ];
    }
    private $index = 1;
    public function map($row): array
    {
        // thêm thông tin

        return [
            $this->index++,
            $row->students->admin_code ?? ($row->json_params->admin_code ?? ''),
            $row->students->name ?? ($row->json_params->student_name ?? ''),
            $row->class->name ?? ($row->json_params->class_name ?? ''),
            $row->class->area->name ?? '',
            $row->type,
            $row->total_skill ?? '',
            $row->score_listen ?? '',
            $row->day_score_listen != '' ? date('d/m/Y', strtotime($row->day_score_listen)) : '',
            $row->score_speak ?? '',
            $row->day_score_speak != '' ? date('d/m/Y', strtotime($row->day_score_speak)) : '',
            $row->score_read ?? '',
            $row->day_score_read != '' ? date('d/m/Y', strtotime($row->day_score_read)) : '',
            $row->score_write ?? '',
            $row->day_score_write != '' ? date('d/m/Y', strtotime($row->day_score_write)) : '',
            $row->teacher->name ?? ($row->json_params->teacher_name ?? ''),
            $row->json_params->note ?? '',
        ];
    }
}
