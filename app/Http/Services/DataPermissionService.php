<?php

namespace App\Http\Services;

use App\Components\Recusive;
use App\Consts;
use App\Models\Admin;
use App\Models\Student;
use App\Models\tbClass;
use App\Models\TeacherClass;
use App\Models\UserClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DataPermissionService
{

    /**
     * Return array id toàn bộ khu vực (Chi nhánh) người dùng được phân quyền dữ liệu
     */
    public static function getPermisisonAreas($admin_id)
    {
        $admin = Admin::find($admin_id);
        return $admin->json_params->area_id ?? [];
    }

    /**
     * Return array id toàn bộ kho trong khu vực đc quản lý
     */
    public static function getPermisisonWarehouses($admin_id)
    {
        $area_ids = self::getPermisisonAreas($admin_id);
        $warehouse_by_area = DB::table('tb_warehouses')->selectRaw('GROUP_CONCAT("", id) warehouses_id')->whereIn('area_id', $area_ids)->first();
        $warehouse_ids_area = explode(",", $warehouse_by_area->warehouses_id);
        return array_unique($warehouse_ids_area);
    }
    /**
     * Return array id toàn bộ đơn đề xuất order + mua sắm trong khu vực đc quản lý
     */
    public static function getPermisisonOrderWarehouses($admin_id)
    {
        // Lấy theo khu vực quản lý
        $warehouse_ids = self::getPermisisonWarehouses($admin_id);
        $order_by_area = DB::table('tb_warehouse_order_products')
            ->selectRaw('GROUP_CONCAT("", id) order_id')
            // ->where('type', Consts::WAREHOUSE_TYPE_ORDER['order'])
            ->whereIn('warehouse_id', $warehouse_ids)
            ->first();
        $order_ids_area = explode(",", $order_by_area->order_id);

        // Đơn tạo
        $orders_by_self = DB::table('tb_warehouse_order_products')
            // ->where('type', Consts::WAREHOUSE_TYPE_ORDER['order'])
            ->where('admin_created_id', $admin_id)
            ->pluck('id')
            ->toArray();

        // Merge 2 mảng id trên sẽ ra danh sách id phiếu đề xuất order + buy theo khu vực và nó tạo
        $order_ids = array_merge($order_ids_area, $orders_by_self);
        return array_unique($order_ids);
    }
    /**
     * Return array id toàn bộ đơn entry trong khu vực đc quản lý
     */
    public static function getPermisisonEntryWarehouses($admin_id)
    {
        // lấy theo khu vực quản lý
        $warehouse_ids = self::getPermisisonWarehouses($admin_id);
        $order_by_area = DB::table('tb_warehouse_entry')
            ->selectRaw('GROUP_CONCAT("", id) entry_id')
            ->whereIn('warehouse_id', $warehouse_ids)
            ->orWhereIn('warehouse_id_deliver', $warehouse_ids)
            ->first();
        $order_ids_area = explode(",", $order_by_area->entry_id);
        return array_unique($order_ids_area);
    }
    /**
     * Return array id all user cấp dưới được quản lý
     */
    public static function getPermissionUsers($id)
    {
        $recusive = new Recusive;
        $data = Admin::select('id', 'parent_id')->where('status', Consts::USER_STATUS['active'])->where('admin_type', "<>", 'student')->get();
        return $recusive->staffAdmissionAllChild($data->toArray(), $id);
    }

    /**
     *  Return array id all user cấp dưới được quản lý và chính nó
     */
    public static function getPermissionUsersAndSelf($id)
    {
        $recusive = new Recusive;
        $data = Admin::select('id', 'parent_id')->where('status', Consts::USER_STATUS['active'])->where('admin_type', "<>", 'student')->get();
        $arrID = $recusive->staffAdmissionAllChild($data->toArray(), $id);
        array_unshift($arrID, $id);
        return $arrID;
    }
    public static function getPermissionUsersAndSelfAll($id)
    {
        $recusive = new Recusive;
        $data = Admin::select('id', 'parent_id')->where('admin_type', "<>", 'student')->get();
        $arrID = $recusive->staffAdmissionAllChild($data->toArray(), $id);
        array_unshift($arrID, $id);
        return $arrID;
    }

    /**
     *  Return array id all lớp học được quản lý tùy theo kiểu người dùng và dữ liệu (staff, teacher,...)
     */
    public static function getPermissionClasses($id)
    {
        $class_ids = [];

        // Lấy theo lớp được quản lý hoặc là giáo viên
        $class_ids = TeacherClass::where('teacher_id', $id)->pluck('class_id')->toArray();


        // Lấy theo khu vực và lớp thuộc khu vực được quản lý
        $area_ids = self::getPermisisonAreas($id);
        $classes_by_area = DB::table('tb_class')->selectRaw('GROUP_CONCAT("", id) class_id')->whereIn('area_id', $area_ids)->first();
        $class_ids_area = explode(",", $classes_by_area->class_id);
        // Merge 2 mảng lớp học
        $class_ids = array_merge($class_ids, $class_ids_area);

        return array_unique($class_ids);
    }

    /**
     * Return array id all học viên thuộc quyền quản lý và dữ liệu được xem (merge các điều kiện)
     */
    public static function getPermissionStudents($id)
    {
        $student_ids = [];

        // Lấy danh sách vùng - khu vực được thao tác dữ liệu
        $area_ids = self::getPermisisonAreas($id);
        // Lấy danh sách người dùng cấp dưới và self
        $user_ids = self::getPermissionUsersAndSelf($id);

        // Lấy danh sách học viên theo khu và cán bộ tuyển sinh
        $student_id_by_area = Student::selectRaw('GROUP_CONCAT("", id) student_id')
            ->whereIn('area_id', $area_ids)
            ->orWhere(function ($student_id_by_area) use ($user_ids) {
                return $student_id_by_area->whereIn('admission_id', $user_ids);
            })
            ->first();
        $student_ids = explode(",", $student_id_by_area->student_id);

        // Lấy danh sách lớp học được thao tác dữ liệu
        // $class_ids = self::getPermissionClasses($id);
        // Lấy danh sách học viên theo lớp
        // $student_id_by_class = DB::table('tb_user_class')->selectRaw('GROUP_CONCAT("", user_id) student_id')
        //     ->whereIn('class_id', $class_ids)->first();
        // $student_ids_class = explode(",", $student_id_by_class->student_id);

        // Merge 2 mảng học viên
        // $student_ids = array_merge($student_ids, $student_ids_class);

        return array_unique($student_ids);
    }

    /**
     * Return array id all người quản lý trực tiếp và quản lý cấp cao hơn nếu có
     */
    public static function getPermissionUsersAndParent($id)
    {
        $recusive = new Recusive;
        $data = Admin::select('id', 'parent_id')->where('status', Consts::USER_STATUS['active'])->where('admin_type', "<>", 'student')->get();
        $arrID = $recusive->staffAdmissionAllParent($data->toArray(), $id);
        // array_unshift($arrID, $id); // Bao gồm chính nó nếu cần
        return $arrID;
    }
}
