<?php

namespace App\Exports;

use App\Models\WareHouseEntry;
use App\Models\HistoryBookDistribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookDistributionExportStudent implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {

        $this->params = $params;
    }
    public function collection()
    {
        $entrys = WareHouseEntry::getSqlWareHouseWareHouseEntry($this->params)->get();
        $list_id_his = [];
        if (isset($entrys)) {
            foreach ($entrys as $key => $row) {
                $history_book_distribution = $row->json_params->history_book_distribution ?? null;
                $list_id_his = array_merge($list_id_his, $history_book_distribution);
            }
        }
        $rows = HistoryBookDistribution::whereIn('id', $list_id_his)->get();
        return $rows;
    }

    public function headings(): array
    {
        // Define column headings for the Excel file
        return [
            'STT',
            'Mã HV',
            'Họ tên',
            'Khóa học',
            'Lớp',
            'Trình độ',
            'Sách đã phát',
            'Kỳ nhận sách'
        ];
    }
    public function map($val): array
    {
        $this->stt++;
        return [
            $this->stt,
            $val->student->admin_code ?? '',
            $val->student->name ?? '',
            $val->student->course->name ?? '',
            $val->class->name??'',
            $val->level->name??'',
            $val->product->name??'',
            date('m/Y', strtotime($this->params['period'])),
        ];
    }
}
