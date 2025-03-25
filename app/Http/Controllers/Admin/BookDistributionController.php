<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\tbClass;
use App\Models\Level;
use App\Models\Syllabus;
use App\Models\Course;
use App\Models\Area;
use App\Models\Student;
use App\Models\Admin;
use App\Models\HistoryBookDistribution;
use App\Models\WareHouseProduct;
use App\Models\WareHouse;
use App\Models\WareHouseOrder;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use App\Models\WarehouseAsset;
use App\Http\Services\DataPermissionService;
use App\Http\Services\BookDistributionService;
use App\Http\Services\WarehouseService;
use App\Models\WareHouseOrderDetail;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookDistributionExportStudent;
use App\Exports\BookDistributionExportEligibleStudents;

use Exception;



class BookDistributionController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'book_distribution';
        $this->viewPart = 'admin.pages.book_distribution';
        $this->responseData['module_name'] = 'Cấp phát sách';
    }

    /** Kế khoạc cấp phát sách cho các lớp gần đến buổi học cuối cùng cách hiện tại 7 buổi học */
    public function planBookDistribution(Request $request)
    {
        $params = $request->all();
        $params['type'] = 'lopchinh';
        $params['status'] = 'dang_hoc';
        $params['status_book_distribution'] = 'null';
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $service = new BookDistributionService();
        $list_data = $service->planBookDistribution($params);
        $this->responseData['areas'] = $list_data->areas;
        $this->responseData['levels'] =  $list_data->levels;
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Kế hoạch cấp phát sách');
        return $this->responseView($this->viewPart . '.plan');
    }
    public function listClassHasPublished(Request $request)
    {
        $params = $request->all();
        $params['type'] = 'lopchinh';
        $params['status'] = 'dang_hoc';
        $params['status_book_distribution'] = Consts::STATUS_BOOK_DISTRIBUTION['danginsach'];
        $params['permission'] = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
        $service = new BookDistributionService();
        $list_data = $service->listClassHasPublished($params);
        $this->responseData['areas'] = $list_data->areas;
        $this->responseData['levels'] =  $list_data->levels;
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Lớp học đang in sách');
        return $this->responseView($this->viewPart . '.class_has_published');
    }

    /** Danh sách học viên đủ điều kiện cấp phát sách */
    public function listEligibleStudents(Request $request)
    {
        $params = $request->all();
        $params['state'] = Consts::STUDENT_STATUS['main learning'];
        $service = new BookDistributionService();
        $students = $service->listEligibleStudents($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $all_students = $service->listEligibleStudents($params)->get();
        $class_id = $service->getUniqueObjectToData('class_id', $all_students);
        $this->responseData['students'] = $students;
        $this->responseData['params'] = $params;
        $this->responseData['classs'] =  tbClass::all();
        $this->responseData['areas'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();
        $this->responseData['levels'] =  Level::getSqlLevel()->whereIn('id', [1, 2, 3, 4, 5, 6])->get();
        $this->responseData['ranked_academic'] = Consts::ranked_academic;
        $this->responseData['status_book_distribution_student'] = Consts::STATUS_BOOK_DISTRIBUTION_STUDENT;
        $this->responseData['module_name'] = __('Danh sách học viên đủ điều kiện phát sách');
        return $this->responseView($this->viewPart . '.eligible_students');
    }

    /** Cấp phát sách cho học viên */
    public function index(Request $request)
    {
        $service = new BookDistributionService();
        $params['permission'] = DataPermissionService::getPermissionStudents(Auth::guard('admin')->user()->id);
        $data = $service->distributeBookToStudents($params);
        $this->responseData['students'] = $data->students;
        $this->responseData['classs'] =  $data->classs;
        $this->responseData['areas'] = $data->areas;
        $this->responseData['status_book_distribution_student'] = Consts::STATUS_BOOK_DISTRIBUTION_STUDENT;
        $this->responseData['status_book_distribution'] = Consts::STATUS_BOOK_DISTRIBUTION;
        $this->responseData['staff_request'] = Admin::where('status', 'active')->where('admin_type', '!=', 'student')->where('id', '!=', '1')->get();
        $this->responseData['course'] = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();

        // Lấy các khóa học từ students
        $courses = $data->students->map(function ($student) {
            return $student->student->course;
        })->unique()->sortByDesc(function ($course) {
            return $course->id;
        });
        $this->responseData['course'] = $courses;

        return $this->responseView($this->viewPart . '.index');
    }

    /** Đổi trạng thái lịch sử cấp phát sách */
    public function changeStatus(Request $request)
    {
        try {
            $id = $request->only('id')['id'];
            $status = $request->only('status')['status'] ?? null;
            $service = new BookDistributionService();
            $change = $service->changeStatus($id, $status);
            return $this->sendResponse('success', 'Lưu thông tin thành công');
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }

    /** Thực hiện cấp phát sách */
    public function activeBookDistribution(Request $request)
    {
        DB::beginTransaction();
        try {
            $book_service = new BookDistributionService();
            $params = $request->except(['book']);
            $book_student = $request->only('book')['book'] ?? null;
            if (empty($book_student)) {
                DB::rollBack();
                throw new Exception(__('Cần chọn học viên để cấp sách!'));
            }
            $arr_history_id = array_unique(array_map(fn($val) => explode("-", $val)[0], $book_student));
            $arr_book_id = array_map(fn($val) => explode("-", $val)[1], $book_student);
            $arr_class_id = array_map(fn($val) => explode("-", $val)[2], $book_student);
            // Lấy sản phẩm từ mảng id
            $list_book = WareHouseProduct::whereIn('id', $arr_book_id)->get();
            // Đếm số lượng từng mã sách
            $book_counts = array_count_values($arr_book_id);
            // Thêm vào bảng entry
            $params['name'] = 'Cấp phát sách học viên';
            $params['type'] = Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'];
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['period'] = date('Y-m', strtotime($params['day_deliver']));
            $params['json_params']['history_book_distribution'] = $arr_history_id;
            $params['json_params']['list_class_id'] = array_values(array_unique($arr_class_id));
            $entry = WareHouseEntry::create($params);
            WarehouseService::autoUpdateCode($entry->id, 'PHATSACH');
            // Thêm các sản phẩm vào bảng entry detail
            $total_money = 0;
            foreach ($list_book as $book) {
                $entry_detail = WarehouseService::createdWareHouseEntryDetail($entry->id, $book->id, $book_counts[$book->id], $entry->warehouse_id_deliver, $entry->period, $entry->staff_entry, Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho']);
                $total_money += $entry_detail->subtotal_money;
                // Trừ số lượng tài sản trong kho
                $minus_quantity = WarehouseService::minusQuantityAsset($book->id, $book_counts[$book->id], $entry->warehouse_id_deliver);
                if ($minus_quantity == false) {
                    DB::rollBack(); // Hoàn tác giao dịch
                    throw new Exception(__('Số lượng ' . $book->name . ' tồn kho không đủ!'));
                }
            }
            $entry->total_money = $total_money;
            $entry->save();

            // Lấy danh sách lớp theo list id class
            $classs = tbClass::whereIn('id', $arr_class_id)->get();
            // cập nhật trạng thái lớp ->daphatsach nếu tất cả học viên trong lớp đã phát sách
            foreach ($classs as $items) {
                // check status trong history_book_distribution theo lớp đã 'dudieukien' hết chưa, đủ hết rồi thì update trạng thái lớp
                $check_count = HistoryBookDistribution::where('class_id', $items)
                    ->where(function ($where) {
                        return $where->where('tb_history_book_distribution.status', '!=', Consts::STATUS_BOOK_DISTRIBUTION_STUDENT['daphatsach'])
                            ->orWhereNull('tb_history_book_distribution.status');
                    })->count();

                // Đủ điều hiện kết rồi thì update lại json_params của class
                if ($check_count <= 0) {
                    $items->status_book_distribution =  Consts::STATUS_BOOK_DISTRIBUTION['daphatsach'];
                    $items->save();
                }
            }
            // cập nhật trạng thái học viên->daphatsach trong bảng lịch sử
            foreach ($arr_history_id as $id) {
                $book_service->changeStatus($id, Consts::STATUS_BOOK_DISTRIBUTION_STUDENT['daphatsach'], $entry->day_deliver);
            }
            DB::commit();
            return redirect()->route($this->routeDefault . '.detail_history', $entry->id)->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /** Tạo đề xuất in sách (loại: mua sắm)   */
    //(Chỉ đổi trạng thái lớp k tạo phiếu đề xuất mua sắm nữa)
    public function createOrderProductBuy(Request $request)
    {
        DB::beginTransaction();
        try {
            $data =  $request->only('levelData')['levelData'] ?? '';
            $params = $request->only('params')['params'] ?? '';
            $service = new BookDistributionService();
            $classs  = $service->getSqlAreaClassEnd($params);
            if (count($classs) <= 0 || $data == '') {
                DB::rollBack();
                return $this->sendResponse('warning', 'Hiện tại chưa có lớp nào cần in giáo trình!');
            }
            // sửa trạng thái các lớp thành đang in sách
            foreach ($classs as $items) {
                $items->status_book_distribution =  Consts::STATUS_BOOK_DISTRIBUTION['danginsach'];
                $items->save();
            }

            // Thêm vào bảng order_products
            // $params['name'] = 'Đề xuất in sách';
            // $params['warehouse_id'] = 3;  // Trả về kho mỹ đình
            // $params['type'] = 'buy';
            // $params['period'] = date('Y-m');
            // $params['status'] = 'approved';  // Duyệt luôn
            // $params['department_request'] = 2;  // Phòng đào tạo
            // $params['staff_request'] = Auth::guard('admin')->user()->id;
            // $params['day_create'] = date('Y-m-d');
            // $params['json_params']['note'] = null;
            // $params['json_params']['related_order'] = null;
            // $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            // $WareHouseOrder = WareHouseOrder::create($params);

            // Thêm vào bảng order_detail_products
            // $total_money = 0;
            // foreach ($data as $level_id => $val) {
            //     if ($val != null) {
            //         // lấy giáo trình tương ứng
            //         $product = WareHouseProduct::whereJsonContains('json_params->level', (string)($level_id ?? ''))->first();
            //         // đếm tổng số lượng
            //         $total = collect($val)->sum('val');
            //         $params_detail['order_id'] = $WareHouseOrder->id;
            //         $params_detail['product_id'] = $product->id;
            //         $params_detail['price'] = $product->price;
            //         $params_detail['quantity'] = $total;
            //         $params_detail['subtotal_money'] = $product->price * $total;
            //         $params_detail['department'] = 2;  // Phòng đào tạo
            //         $params_detail['type'] = 'buy';
            //         $params_detail['status'] = 'approved';
            //         $params_detail['admin_created_id'] = Auth::guard('admin')->user()->id;
            //         $WareHouseOrderDetail = WareHouseOrderDetail::create($params_detail);
            //         $total_money += $product->price * $total;
            //     }
            // }
            // $WareHouseOrder->total_money =  $total_money;
            // $WareHouseOrder->code =  'PHATSACH-' . date('my') . $WareHouseOrder->id;
            // $WareHouseOrder->save();
            DB::commit();
            return $this->sendResponse('success', 'Lưu thông tin thành công');
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendResponse('warning', __($ex->getMessage()));
        }
    }

    public function getViewBookDistribution(Request $request)
    {
        $params['area_id_class'] = $request->only('class_id')['class_id'] ?? '';
        $service = new BookDistributionService();
        $data = $service->distributeBookToStudents($params);
        $students = $data->students;
        $classs =  $data->classs;
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $result['view'] = view($this->viewPart . '.view_book_distribution', compact('students', 'course', 'classs'))->render();
        return $this->sendResponse($result, 'Thấy thông tin thành công');
    }

    /** Danh sách lịch sử cấp phát sách */
    public function listHistory(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = 'PHATSACH';
        $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        // Lấy thông tin lớp học
        $rows->getCollection()->transform(function ($item) {
            if (isset($item->json_params->list_class_id)) {
                $list_class_id = (array) $item->json_params->list_class_id;
                $list_class = tbClass::whereIn('id', $list_class_id)->get();
                $item['list_class'] = $list_class ?? null;
            }
            return $item;
        });
        $this->responseData['rows'] = $rows;
        $this->responseData['syllabuss'] = Syllabus::getSqlSyllabus()->get();
        $this->responseData['warehouses'] = WareHouse::getSqlWareHouse()->get();
        $this->responseData['module_name'] = 'Lịch sử cấp phát sách';
        $this->responseData['params'] = $params;
        $this->responseData['classs'] =  tbClass::all();
        return $this->responseView($this->viewPart . '.list_history');
    }
    /** Chi tiết lịch sử cấp phát sách */
    public function detailHistory($id)
    {
        $entry = WareHouseEntry::find($id);
        // Lấy danh sách entry_detail
        $entry_detail = WareHouseEntryDetail::where('entry_id', $id)->get();
        // Lấy lịch sử học viên cấp phát sách
        $book_distribution = HistoryBookDistribution::whereIN('id', $entry->json_params->history_book_distribution)->get();
        // Lấy tên sách từ book_distribution
        $list_product = $book_distribution->map(function ($book) {
            return $book->product;
        })->unique()->sortBy(function ($product) {
            return $product->id;
        });
        $product_names = $list_product->pluck('name')->implode(';');
        // Lấy tên lớp từ book_distribution
        $list_class = $book_distribution->map(function ($book) {
            return $book->class;
        })->unique()->sortBy(function ($class) {
            return $class->id;
        });
        $class_names = $list_class->pluck('name')->implode(';');
        // Lấy danh sách giáo viên chủ nhiệm
        $arr_teacher = [];
        $teacher_name = '';
        foreach ($list_class as $val) {
            $teacher = Teacher::where('id', $val->json_params->teacher)->first();
            array_push($arr_teacher, $val->json_params->teacher);
            $teacher_name .= $teacher->name . ' ';
        }
        $this->responseData['product_names'] = $product_names;
        $this->responseData['class_names'] = $class_names;
        $this->responseData['entry'] = $entry;
        $this->responseData['entry_detail'] = $entry_detail;
        $this->responseData['book_distribution'] = $book_distribution;
        $this->responseData['arr_teacher'] = $arr_teacher;
        $this->responseData['teacher_name'] = $teacher_name;
        $this->responseData['module_name'] = 'Chi tiết lịch sử cấp phát sách';

        return $this->responseView($this->viewPart . '.detail_history');
    }

    /** Danh sách học viên đã nhận sách */
    public function listBookDistribution(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = 'PHATSACH';
        $params['type'] = 'xuat_kho';
        $params['period'] = isset($params['period']) ? $params['period'] : Carbon::now()->format('Y-m');
        $entrys = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->get();
        $list_id_his = [];
        if (isset($entrys)) {
            foreach ($entrys as $key => $row) {
                $history_book_distribution = $row->json_params->history_book_distribution ?? null;
                $list_id_his = array_merge($list_id_his, $history_book_distribution);
            }
        }
        $rows = HistoryBookDistribution::whereIn('id', $list_id_his)->get();
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = 'Danh sách học viên đã nhận sách trong kỳ ' . Carbon::createFromFormat('Y-m', $params['period'])->format('m-Y');
        return $this->responseView($this->viewPart . '.list_book_distribution_student');
    }
    public function confirmTeacher(Request $request)
    {
        $id = $request->only('id')['id'];
        $entry = WareHouseEntry::find($id);
        if ($entry) {

            $json_params = (array)$entry->json_params;
            $json_params['confirmed_name'] = Auth::guard('admin')->user()->name;
            $json_params['confirmed_code'] = Auth::guard('admin')->user()->admin_code;
            $entry->confirmed = 'da_nhan';
            $entry->json_params = $json_params;
            $entry->save();
        }
        return $this->sendResponse('success', 'Lưu thông tin thành công');
    }
    public function exportListBookDistribution(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = 'PHATSACH';
        $params['type'] = 'xuat_kho';
        $params['period'] = isset($params['period']) ? $params['period'] : Carbon::now()->format('Y-m');
        return Excel::download(new BookDistributionExportStudent($params), 'Danh sách học viên đã nhận sách.xlsx');
    }
    public function exportEligibleStudents(Request $request)
    {
        $params = $request->all();
        $params['state'] = Consts::STUDENT_STATUS['main learning'];

        return Excel::download(new BookDistributionExportEligibleStudents($params), 'Danh sách học viên đủ điều kiện.xlsx');
    }

    public function changeClassBookDistribution(Request $request)
    {
        $id = $request->only('id')['id'];
        $class = tbClass::find($id);
        if ($class) {
            $class->status_book_distribution = Consts::STATUS_BOOK_DISTRIBUTION['daphatsach'];
            $class->save();
            session()->flash('successMessage', 'Cập nhật thành công!');
            return $this->sendResponse('success', __('Cập nhật thành công!'));
        }
        return $this->sendResponse('warning', __('Không tìm thấy thông tin lớp!'));
    }
}
