<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\tbClass;
use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class EvaluationExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function title(): string
    {
        return 'LỚP-'.$this->params['class_name'];
    }
    public function collection()
    {
        return Evaluation::getsqlEvaluation($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Từ ngày',
            'Đến ngày',
            'Mã học viên',
            'Tên học viên',
            'Học lực',
            'Ý thức',
            'Kiến thức',
            'Kỹ năng',
            'Ngày đánh giá'
        ];
    }
    public function map($row): array
    {
        static $stt = 0;
        $stt++;
        $student = Student::find($row->student_id );
        $student_name = isset($student) ? $student->name : "";
        $student_code = isset($student) ? $student->admin_code : "";

        return [
            $stt,
            isset($row->from_date)?date('d-m-Y', strtotime($row->from_date)):"",
            isset($row->to_date)?date('d-m-Y', strtotime($row->to_date)):"",
            $student_code,
            $student_name,
            $row->json_params->ability ?? '',
            $row->json_params->consciousness ?? '',
            $row->json_params->knowledge ?? '',
            $row->json_params->skill ?? '',
            $row->updated_at,
        ];
    }
}
