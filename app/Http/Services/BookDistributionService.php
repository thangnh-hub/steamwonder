<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\tbClass;
use App\Models\Level;
use App\Models\Student;
use App\Models\Score;
use App\Models\WareHouseProduct;
use App\Models\WarehouseAsset;
use App\Models\HistoryBookDistribution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class BookDistributionService
{
    protected $arr_lervel;
    protected $lervel_a;
    protected $lervel_b;
    protected $rank;
    protected $levels;

    public function __construct()
    {
        $this->arr_lervel = ['1', '2', '3', '4', '5'];
        $this->lervel_a = ['1', '2', '3', '4'];
        $this->lervel_b = ['5'];
        $this->rank = ['pass', 'level_up'];
        $this->levels = $this->getLevel();
    }

    public function getLevel()
    {
        $levels = Level::getSqlLevel()->whereIn('id', $this->arr_lervel)->get();
        return $levels;
    }
    /** Tạo lịch sử cấp phát sách học viên */
    public function addHistoryBookDistribution($params = [])
    {
        /**
         * Thay đổi lại phần tạo lịch sử cấp phát sách
         * Các học viên khi đc add vào lớp sẽ check để phát sách nếu chưa nhận sách level đó
         */
        // Lấy quyển sách thuộc level lớp hiện tại
        $product = WareHouseProduct::whereJsonContains('json_params->level', (string)($params['level_id']))->first();
        // Kiểm tra HV lớp đấy đã nhận sách hay chưa
        $params['product_id'] = $params['product_id'] ?? ($product->id ?? null);
        $check = HistoryBookDistribution::where('student_id', $params['student_id'])
            ->where('product_id', $params['product_id'])
            ->first();
        if (!$check) {
            $history = HistoryBookDistribution::create($params);
            return $history;
        } elseif ($check->status != 'daphatsach') {
            $check->fill($params);
            $check->save();
            return $check;
        }
        return false;
    }
    /** Tạo lịch sử cấp phát sách học viên */
    public function deleteHistoryBookDistribution($params = [])
    {
        /**
         * Truyền vào các tham số: level_id, student_id, class_id
         *  Xóa bản ghi nếu chưa phát sách
         */
        $level_id = $params['level_id'];
        $student_id = $params['student_id'];
        $class_id = $params['class_id'];
        $check = HistoryBookDistribution::where('student_id', $student_id)
            ->where('level_id', $level_id)
            ->where('class_id', $class_id)
            ->first();
        if (isset($check) && $check->status != 'daphatsach') {
            $check->delete();
            return true;
        }
        return false;
    }

    /** Lấy các lớp học sắp kết thúc (Còn 7 buổi) */
    public function getSqlAreaClassEnd($params = [])
    {
        $rows = tbClass::getSqlClassEnding($params)->whereIn('level_id', $this->arr_lervel)
        ->havingRaw('total_schedules - total_attendance <= 7')->get();
        return $rows;
    }
    /** Lấy danh sách lớp đã in sách */
    public function getSqlClassHasPublished($params = [])
    {
        $rows = tbClass::getSqlClassEnding($params)->whereIn('level_id', $this->arr_lervel)
            ->get();
        return $rows;
    }

    /** Lấy các khu vực từ danh sách lớp */
    public function getAreaToClass($classs)
    {
        $list_areas = $classs->map(function ($class) {
            return $class->area;
        })->unique()->sortBy(function ($area) {
            return $area->id;
        });
        return $list_areas;
    }

    /**  Tổng hợp học viên, trình độ và giáo trình theo khu vực*/
    public function getlevelFromClassArea($classs, $area)
    {
        $levels = $this->getLevel();
        foreach ($levels as $val) {
            // Tính tổng số sinh viên của từng trình độ trong khu vực
            $countStudents = $classs
                ->filter(function ($class) use ($val, $area) {
                    return $class->level_id == $val->id && $class->area_id == $area->id;
                })
                ->sum(function ($class) {
                    return count($class->students);
                });
            $val->count_student = $countStudents;

            $nextLevel = Level::find($val->id + 1);
            $val->next_level = $nextLevel;
            // lấy giáo trình tương ứng và tồn kho
            $product = WareHouseProduct::whereJsonContains('json_params->level', (string)($nextLevel->id ?? ''))->first();
            $val->product_name = $product->name ?? '';
            $params['product_id'] = $product->id;
            $params['area_id'] = $area->id;
            $product_asset = WarehouseAsset::getSqlWareHouseAsset($params)->first();
            $val->product_quantity = (int)($product_asset->quantity ?? 0);
        }
        return $levels;
    }

    /**  Lấy trình độ tiếp theo*/
    public function getNextLevelAndProduct($area_id = '')
    {
        $levels = $this->levels;
        foreach ($levels  as $val) {
            // Lấy trình độ tiếp theo dựa trên chỉ số
            $nextLevel = Level::find($val->id + 1);
            $val->next_level = $nextLevel;
            // lấy giáo trình tương ứng
            $product = WareHouseProduct::whereJsonContains('json_params->level', (string)($nextLevel->id ?? ''))->first();
            $val->product_name = $product->name ?? '';
        }
        return $levels;
    }
    /** Kế hoạch cấp phát sách */
    public function planBookDistribution($params = [])
    {
        $data =  new stdClass();
        $class = $this->getSqlAreaClassEnd($params);
        $areas = $this->getAreaToClass($class);

        // Tổng hợp các lớp theo khu vựa trả ra view
        foreach ($areas as $area) {
            $area->class = $class->filter(function ($item, $key) use ($area) {
                return $item->area_id == $area->id;
            });
            $area->data = $this->getlevelFromClassArea($class, $area);
        }
        $data->levels = $this->getNextLevelAndProduct();
        $data->areas = $areas;
        return $data;
    }
    /** Kế hoạch cấp phát sách */
    public function listClassHasPublished($params = [])
    {
        $data =  new stdClass();
        $class = $this->getSqlClassHasPublished($params);
        $areas = $this->getAreaToClass($class);

        // Tổng hợp các lớp theo khu vựa trả ra view
        foreach ($areas as $area) {
            $area->class = $class->filter(function ($item, $key) use ($area) {
                return $item->area_id == $area->id;
            });
            $area->data = $this->getlevelFromClassArea($class, $area);
        }
        $data->levels = $this->getNextLevelAndProduct();
        $data->areas = $areas;
        return $data;
    }

    /** Học viên đủ điều kiện cấp phát sách */
    public function getEligibleStudents($params = [])
    {
        $students = HistoryBookDistribution::select('tb_history_book_distribution.*')
            ->Join('admins', 'tb_history_book_distribution.student_id', '=', 'admins.id')
            // ->Join('tb_user_class', 'tb_history_book_distribution.student_id', '=', 'tb_user_class.user_id')
            ->leftJoin('tb_classs', 'tb_history_book_distribution.class_id', '=', 'tb_classs.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('admins.area_id', $params['area_id']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_history_book_distribution.class_id', $params['class_id']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('admins.id', $params['permission']);
            })
            ->when(!empty($params['area_id_class']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id_class']);
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_history_book_distribution.level_id', $params['level_id']);
            })
            ->groupby('tb_history_book_distribution.id');
        return $students;
    }

    /** Danh sách học viên đủ điều kiện cấp phát sách */
    public function listEligibleStudents($params = [])
    {
        $student = $this->getEligibleStudents($params)
            ->where(function ($where) {
                return $where->where('tb_history_book_distribution.status', '!=', Consts::STATUS_BOOK_DISTRIBUTION_STUDENT['daphatsach'])
                    ->orWhereNull('tb_history_book_distribution.status');
            })->orderBy('class_id', 'DESC');
        return $student;
    }
    /** Lấy các lớp từ danh sách học viên */
    public function getClassToStudent($students)
    {
        $list_class = $students->map(function ($student) {
            return $student->class;
        })->unique()->sortBy(function ($class) {
            return $class->id;
        });
        return $list_class;
    }
    /** Cấp phát sách cho học viên */
    public function distributeBookToStudents($params = [])
    {
        $data =  new stdClass();
        $students = $this->getEligibleStudents($params)->where('tb_history_book_distribution.status', Consts::STATUS_BOOK_DISTRIBUTION_STUDENT['dudieukien'])->orderBy('class_id', 'DESC')->get();
        $classs = $this->getClassToStudent($students);
        $areas = $this->getAreaToClass($classs);
        $data->students = $students;
        $data->classs = $classs;
        $data->areas = $areas;
        return $data;
    }

    public function changeStatus($id, $status, $date_received = null)
    {
        $history = HistoryBookDistribution::find($id);
        if ($history) {
            if ($date_received != null) {
                $history->date_received = $date_received;
            }
            $history->status = $status;
            $history->save();
        }
        return $history;
    }
    /** Lấy thuộc tính từ collection*/
    public function getUniqueObjectToData($property, $collection)
    {
        $list_property = $collection->map(function ($data) use ($property) {
            return $data->{$property};
        })->unique();
        return $list_property;
    }
}
