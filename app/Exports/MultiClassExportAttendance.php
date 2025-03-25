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
use App\Models\Teacher;




class MultiClassExportAttendance implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $stt = 0;
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function collection()
    {
        // list id khu vực
        $params = $this->params;
        $admin = Auth::guard('admin')->user();
        $params['list_class'] = DataPermissionService::getPermissionClasses($admin->id);
        $params['status_schedule'] = Consts::SCHEDULE_STATUS['chuahoc'];

        $year = explode('-', $params['month'])[0];
        $month = explode('-', $params['month'])[1];
        if ($params['month'] == date('Y-m', time())) {
            $params['from_date'] = $params['month'] . '-01';
            $params['to_date'] = $params['month'] . '-' . date('d');
        } else {
            $params['from_date'] = $params['month'] . '-01';
            $params['to_date'] = Carbon::create($year, $month, 01)->endOfMonth()->toDateString();
        }
        // lấy danh sách lớp chưa điểm danh trong tháng
        $query = tbClass::where('status', 'dang_hoc')
            ->withCount(['schedules as total' => function ($query) use ($params) {
                $query->where('status', 'chuahoc')
                    ->when(!empty($params['from_date']), function ($query) use ($params) {
                        return $query->where('tb_schedules.date', '>=', $params['from_date']);
                    })
                    ->when(!empty($params['to_date']), function ($query) use ($params) {
                        return $query->where('tb_schedules.date', '<=', $params['to_date']);
                    });
            }])
            ->with(['schedules' => function ($query) use ($params) {
                $query->where('status', 'chuahoc')
                    ->when(!empty($params['from_date']), function ($query) use ($params) {
                        return $query->where('tb_schedules.date', '>=', $params['from_date']);
                    })
                    ->when(!empty($params['to_date']), function ($query) use ($params) {
                        return $query->where('tb_schedules.date', '<=', $params['to_date']);
                    })
                    ->select('id', 'class_id', 'date'); // Chỉ lấy các cột cần thiết
            }])
            ->having('total', '>=', 1)
            ->orderBy('total', "asc")
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_classs.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['list_class']), function ($query) use ($params) {
                return $query->whereIn('tb_classs.id', $params['list_class']);
            })
            ->when(!empty($params['type_class']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type_class']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            });
        return $query->get();
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
            'Số buổi chưa điểm danh trong tháng'
        ];
    }
    public function map($user): array
    {
        $this->stt++;
        $teacher = Teacher::where(
            'id',
            $user->json_params->teacher ?? 0,
        )->first();
        $unAttendanceDates = $user->schedules
            ->pluck('date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d/m/Y'); // Định dạng ngày tháng năm
            })
            ->toArray();
        return [
            $this->stt,
            $user->name ?? '',
            $teacher->name ?? '',
            $user->area->name ?? '',
            count($user->students ?? []),
            Consts::CLASS_STATUS[$user->status],
            $user->total . ' (' . implode(', ', $unAttendanceDates) . ')',
        ];
    }
}
