<?php

namespace App\Exports;

use App\Models\StaffAdmission;
use App\Models\Area;
use App\Models\Score;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Consts;

class ScoreExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        return Score::getsqlScore($this->params)->get();
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'Mã HV',
            'Học viên',
            'Lớp chính',
            'Điểm nghe',
            'Điểm nói',
            'Điểm đọc',
            'Điểm viết',
            'Điểm trung bình',
            'Nhận xét đánh giá',
            'Xếp loại'
        ];
    }
    public function map($score): array
    {
        return [
            $score->student->admin_code ?? '',
            $score->student->name ?? "",
            $score->class->name ?? '',
            $score->score_listen ?? '0',
            $score->score_speak ?? '0',
            $score->score_read ?? '0',
            $score->score_write ?? '0',
            $score->json_params->score_average ?? '0',
            $score->json_params->note ?? '',
            $score->status != "" ? Consts::ranked_academic_total[$score->status] ?? $score->status : "Chưa xác định"
        ];
    }
}
