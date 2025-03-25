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

class ExamResultExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return ExamSessionUser::getSqlExamSessionUser($this->params)->orderBy('exam_id', 'desc')->get();
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
            'Buổi thi',
            'Ngày thi',
            'Giờ thi',
            'Trạng thái',
            'Kết quả',
        ];
    }
    public function map($user): array
    {
        $admission = Admin::find($user->student->admission_id);
        return [
            $user->student->admin_code??'',
            $user->student->name??"",
            $user->student->json_params->cccd??"",
            $user->exam->course->name??"",
            $admission->admin_code??"",
            $admission->name??"",
            $user->exam->title??"",
            $user->exam->day_exam??"",
            $user->exam->time_exam_start??"",
            __($user->status),
            $user->score?? 'Chưa cập nhật',
        ];
    }
}
