<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Student;
use App\Models\Admin;
use App\Models\ExamSessionUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamResultStudentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return ExamSessionUser::getSqlExamResult($this->params)->orderBy('exam_id', 'desc')->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'Mã học viên',
            'Tên học viên',
            'CCCD',
            'Khóa',
            'Mã CBTS',
            'CB tuyển sinh',
            'Kết quả Test IQ',
            'Kết quả Test ngôn ngữ',
            'Trung bình',
            'Xếp loại',
        ];
    }
    public function map($user): array
    {
        if ($user->student) {
            $admission = Admin::find($user->student->admission_id);
        }
        $diem_tb = round($user->diem_iq * 0.3 + $user->diem_acceptance * 0.7, 2);
        $xep_loai = $diem_tb >= 65 ? 'Đạt' : 'Không đạt';

        return [
            $user->student->admin_code ?? '',
            $user->student->name ?? "",
            $user->student->json_params->cccd ?? "",
            $user->course->name ?? "",
            $admission->admin_code ?? "",
            $admission->name ?? "",
            $user->diem_iq ?? 'Chưa cập nhật',
            $user->diem_acceptance ?? 'Chưa cập nhật',
            $diem_tb,
            $xep_loai
        ];
    }
}
