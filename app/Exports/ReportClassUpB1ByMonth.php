<?php

namespace App\Exports;

use App\Consts;
use App\Models\tbClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Str;


class ReportClassUpB1ByMonth implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        $this->params['level_id'] = $this->params['level_id'] ?? 4;
        // Lấy danh sách lớp đang học trình độ A2.2 (level_id = 4) => bổ sung điều kiện lọc cả level
        $list_class_all = tbClass::select('tb_classs.*')
            ->selectRaw('MIN(tb_schedules.date) AS day_start, MAX(tb_schedules.date) AS day_end')
            ->selectRaw('MAX(CASE WHEN tb_schedules.is_add_more IS NULL THEN tb_schedules.date END) AS day_end_expected')
            ->where('tb_classs.level_id', '=', $this->params['level_id'])
            ->where('tb_classs.type', '!=', 'elearning') // k lấy lớp học elearning
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->groupBy('tb_classs.id')
            ->orderBy('day_end')
            ->get();
        // Tổng hợp các lớp trong tháng hiện tại (mặc định)
        $startOfMonth = Carbon::now()->startOfMonth(); // Ngày đầu tiên của tháng hiện tại
        $endOfMonth = Carbon::now()->endOfMonth(); // Ngày cuối cùng của tháng hiện tại
        // Các tham số filter
        $this->params['class_id'] = $this->params['class_id'] ?? null;
        $this->params['keyword'] = $this->params['keyword'] ?? null;
        $this->params['from_date'] = !empty($this->params['from_date']) ? Carbon::parse($this->params['from_date']) : $startOfMonth;
        $this->params['to_date'] = !empty($this->params['to_date']) ? Carbon::parse($this->params['to_date']) : $endOfMonth;
        // Lọc ra các lớp có ngày kết thúc hoặc dự kiến trong tháng
        $list_class = $list_class_all->filter(function ($class) {
            $day_end = Carbon::parse($class['day_end'])->addDay(); // Lấy thêm mỗi ngày kết thúc + 1 ngày
            if ($this->params['class_id'] > 0) {
                return $day_end >= $this->params['from_date'] && $day_end <= $this->params['to_date'] && $class->id == $this->params['class_id'];
            }
            return $day_end >= $this->params['from_date'] && $day_end <= $this->params['to_date'];
        });
        // Duyệt để lọc ra danh sách học viên theo lớp và bổ sung dữ liệu theo học viên
        $list_class = $list_class->map(function ($class) {
            $class['day_start'] = Carbon::parse($class['day_start'])->format('d/m/Y');
            $class['day_end_level'] = Carbon::parse($class['day_end'])->addDay()->format('d/m/Y');
            $class['day_end_level_expected'] = Carbon::parse($class['day_end_expected'])->addDay()->format('d/m/Y');
            // Lọc các học viên nếu có điều kiện tìm kiếm
            $students = $class->students->filter(function ($student) {
                if ($this->params['keyword'] != null) {
                    return Str::contains($student->admin_code, $this->params['keyword']) || Str::contains($student->name, $this->params['keyword']);
                }
                return $student;
            });
            // Duyệt điểm theo từng học viên trong lớp
            $students = $students->map(function ($student) use ($class) {
                $score = $class->scores->first(function ($score) use ($student) {
                    return $score->user_id == $student->id;
                }, null);
                $student['xep_loai'] = $score->status ?? null;
                return $student;
            });
            $class->students = $students;
            return $class;
        });

        return $list_class;
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã học viên',
            'Họ tên',
            'CBTS',
            'Lớp',
            'Trình độ',
            'Kết quả thi',
            'Ngày lên trình dự kiến',
            'Ngày lên trình thực tế',
            'Hợp đồng',
        ];
    }
    public function map($class): array
    {
        $data = [];
        foreach ($class->students as $item) {
            $this->stt++;
            $data[] = [
                $this->stt,
                $item->admin_code,
                $item->name,
                $item->admission->admin_code ?? '',
                $class->name,
                $class->level->name ?? '',
                __($item->xep_loai ?? 'Chưa thi'),
                $class->day_end_level_expected,
                $class->day_end_level,
                isset($item->json_params->contract_type) && $item->json_params->contract_type != null ? $item->json_params->contract_type : __('Chưa cập nhật'),
            ];
        }
        return $data;
    }
}
