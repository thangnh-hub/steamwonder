<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\tbClass;
use App\Models\Area;
use App\Models\Schedule;
use Carbon\Carbon;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultiClassExportAttendance;
use App\Exports\MultiClassExportEvaluations;


class MultiClassNullController extends Controller
{
    public function __construct()
    {
        $this->viewPart = 'admin.pages.reports';
    }
    public function multiclassNull(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $params = $request->all();
        // xác định ngày trong tháng
        if (isset($params['month']) && $params['month'] != '') {
            $params['month'] = $params['month'];
        } else {
            $params['month'] = date('Y-m', time());
        }
        $params['type_class'] = 'lopchinh';
        $params['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        // $params['status'] = 'dang_hoc';

        $this->responseData['module_name'] = __('Thống kê lớp học chưa điểm danh - nhận xét theo tháng');
        // Lấy khu vực
        $area = Area::getsqlArea()->get();
        $this->responseData['area'] = $area;
        $this->responseData['class_status'] = Consts::CLASS_STATUS;
        $this->responseData['params'] = $params;
        // lọc theo loại
        if (isset($params['type'])) {
            if ($params['type'] == 'evaluations') {
                $this->responseData['rows'] = $this->getClassEvaluationNull($params);
            } elseif ($params['type'] == 'attendance') {
                $this->responseData['rows'] = $this->getClassAttendanceNull($params)->get();
            }
        }
        return $this->responseView($this->viewPart . '.class_evaluation_null');
    }

    public function getClassEvaluationNull($params = [])
    {
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
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                $query->where('tb_classs.id', $params['class_id']);
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
        return $result;
    }

    public function getClassAttendanceNull($params = [])
    {
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
        $query = tbClass::withCount(['schedules as total' => function ($query) use ($params) {
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

            ->when(!empty($params['type_class']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type_class']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_classs.id', $params['permission']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_classs.status', $params['status']);
            });
        return $query;
    }

    public function exportClassNull(Request $request)
    {
        $params = $request->all();
        $params['type_class'] = 'lopchinh';
        if ($params['type'] == 'attendance') {
            return Excel::download(new MultiClassExportAttendance($params), 'Thong_ke_lop_hoc_chua_diem_danh_trong_thang.xlsx');
        } elseif ($params['type'] == 'evaluations') {
            return Excel::download(new MultiClassExportEvaluations($params), 'Thong_ke_lop_hoc_chua_diem_danh_trong_thang.xlsx');
        }
    }
}
