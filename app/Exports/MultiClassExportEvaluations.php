<?php

namespace App\Exports;

use App\Consts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\tbClass;
use App\Models\Schedule;
use App\Models\Teacher;





class MultiClassExportEvaluations implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        $params = $this->params;
        // list id khu vực
        $admin = Auth::guard('admin')->user();
        $params['list_id_class'] = DataPermissionService::getPermissionClasses($admin->id);

        $year = explode('-', $params['month'])[0];
        $month = explode('-', $params['month'])[1];
        // lấy id các lớp có lịch học trong tháng
        $list_class = tbClass::select('tb_classs.*')
            ->Join('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where('tb_classs.name', 'like', '%' . $keyword . '%');
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_classs.id', $params['permission']);
            })
            ->when(!empty($params['type_class']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type_class']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_classs.status', $params['status']);
            })
            ->whereMonth('tb_schedules.date', $month)
            ->whereYear('tb_schedules.date', $year)
            ->groupBy('class_id')->get();

        // lấy danh sách lớp có nhận xét trong tháng
        // Subquery
        $subQuery = DB::table('tb_evaluations')
            ->select('class_id', 'from_date', 'to_date')
            ->whereMonth('tb_evaluations.from_date', $month)
            ->whereYear('tb_evaluations.from_date', $year)
            ->whereMonth('tb_evaluations.to_date', $month)
            ->whereYear('tb_evaluations.to_date', $year)
            ->groupBy('class_id', 'from_date', 'to_date');
        // Main query
        $list_evaluations_class = DB::table(DB::raw("({$subQuery->toSql()}) as t"))
            ->mergeBindings($subQuery)
            ->leftJoin('tb_classs', 'tb_classs.id', '=', 't.class_id')
            ->select('t.class_id', 'tb_classs.name', DB::raw('COUNT(*) as total'))
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where('tb_classs.name', 'like', '%' . $keyword . '%');
            })

            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['list_class']), function ($query) use ($params) {
                return $query->whereIn('tb_classs.id', $params['list_class']);
            })
            ->groupBy('t.class_id', 'tb_classs.name')
            ->get();
        $result = [];
        $list_class = $list_class->reject(function ($items) use ($list_evaluations_class) {
            $evaluations = $list_evaluations_class->first(function ($item) use ($items) {
                return $item->class_id == $items->id; // Đảm bảo class_id khớp với id của items
            });
            // Điều kiện loại bỏ: nếu evaluations có giá trị và tổng >= 2
            return $evaluations && $evaluations->total >= 2;
        });
        foreach ($list_class as $keys => $items) {
            $evaluations =  $list_evaluations_class->first(function ($item, $key) use ($items) {
                return $item->class_id == $items->id;
            });
            $items['total'] = $evaluations->total ?? 0;
            array_push($result, $items);
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên lớp',
            'Giáo viên',
            'Khu vực',
            'Sĩ số',
            'Trạng thái',
            'Số lần nhận xét trong tháng'
        ];
    }
    public function map($user): array
    {
        $this->stt++;
        $teacher = Teacher::where(
            'id',
            $user->json_params->teacher ?? 0,
        )->first();
        return [
            $this->stt,
            $user->name ?? '',
            $teacher->name ?? '',
            $user->area->name ?? '',
            count($user->students ?? []),
            Consts::CLASS_STATUS[$user->status],
            $user->total ?? 0,
        ];
    }
}
