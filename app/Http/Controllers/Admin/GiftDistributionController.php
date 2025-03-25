<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Area;
use App\Models\WareHouseProduct;
use App\Models\Warehouse;
use App\Models\HistoryGift;
use App\Models\WareHouseEntry;
use App\Models\WareHouseEntryDetail;
use App\Http\Services\WarehouseService;
use App\Http\Services\DataPermissionService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GiftDistributionController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'gift_distribute';
        $this->viewPart = 'admin.pages.gift_distribution';
        $this->responseData['module_name'] = 'Cấp phát quà tặng';
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $params['status'] = Consts::STATUS['active'];
        $params['state'] = Consts::STUDENT_STATUS['try learning'];
        $students = isset($request->course_id) ? Student::getSqlStudent($params)->get() : collect();

        $params_gift['gift'] = Consts::GIFT_TYPE['hocvien'];
        $params_gift['status'] = Consts::STATUS['active'];
        $gifts = WareHouseProduct::getSqlWareHouseProduct($params_gift)->get();

        // Lấy danh sách quà đã cấp phát theo student_id
        $issuedGifts = HistoryGift::whereIn('student_id', $students->pluck('id'))->get()->groupBy('student_id');
        // danh sách quà đã nhận vào từng sinh viên
        foreach ($students as $student) {
            $student->issued_gifts = isset($issuedGifts[$student->id]) ? $issuedGifts[$student->id]->pluck('product_id')->toArray(): [];
        }
        $this->responseData['students'] = $students;
        $this->responseData['gifts'] = $gifts;
        $this->responseData['params'] = $params;
        $this->responseData['courses'] = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $this->responseData['module_name'] = 'Kế hoạch cấp phát quà tặng cho học viên';

        return $this->responseView($this->viewPart . '.index');
    }
    
    public function store(Request $request)
    {
        $giftData = $request->gifts; 
        if (!$giftData) {
            return redirect()->back()->with('errorMessage', 'Chưa chọn danh sách quà cần cấp phát!');
        }
        if($giftData){
            foreach ($giftData as $studentId => $giftIds) {
                foreach ($giftIds as $giftId) {
                    //check tồn tại trong lịch sử cấp phát quà chưa
                    $exists = HistoryGift::where('student_id', $studentId)->where('product_id', $giftId)->exists();
                    if (!$exists) {
                        HistoryGift::create([
                            'student_id' => $studentId,
                            'product_id' => $giftId,
                            'status' => Consts::GIFT_STATUS['danhan'],
                            'admin_created_id' => Auth::guard('admin')->user()->id,
                        ]);
                    }
                }
            }
        }
        // return redirect()->route('gift_distribute_entry', ['course_id' => $request->course_id])->with('successMessage', 'Lưu cấp phát quà thành công. Vui lòng tạo phiếu xuất kho!');
        return redirect()->back()->with('successMessage', 'Lưu cấp phát quà thành công. Vui lòng tạo phiếu xuất kho!');
    }
    public function indexEntry(Request $request)
    {   
        $user = Auth::guard('admin')->user();
        $params = $request->all();

        //lấy ra danh sách học viên đc phát quà
        $issuedGifts = HistoryGift::where('status', Consts::GIFT_STATUS['danhan'])->with('product')->get()->groupBy('student_id');
        //danh sách học viên đã đc phát quà
        $params['list_id'] = $issuedGifts->keys();
        $students = isset($request->course_id) ? Student::getSqlStudent($params)->get() : collect();
        //gắn list quà theo từng thằng hv
        foreach ($students as $student) {
            $student->issued_gifts = isset($issuedGifts[$student->id]) ? $issuedGifts[$student->id] : collect();
        }

        $this->responseData['students'] = $students;
        $this->responseData['params'] = $params;
        $this->responseData['courses'] = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();

        $params_area['id'] = DataPermissionService::getPermisisonAreas($user->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();

        $area_selected = $user->area_id;
        $this->responseData['area_selected'] = $area_selected;
        // $this->responseData['list_warehouse'] = WareHouse::where('area_id', $area_selected)->get();
        $this->responseData['list_warehouse'] = DB::table('tb_warehouses')->where('area_id', $area_selected)->get();

        $this->responseData['module_name'] = 'Tạo phiếu xuất kho';
        return $this->responseView($this->viewPart . '.index_entry');
    }

    public function storeEntry(Request $request)
    {
        $request->validate([
            'warehouse_id_deliver' => 'required',
        ]);
    
        $giftData = $request->gifts;
        if (empty($giftData)) {
            return redirect()->back()->with('errorMessage', 'Chưa có quà cần xuất!');
        }
    
        // Lấy danh sách ID lịch sử phát quà
        $historyIds = collect($giftData)->flatten()->toArray();
    
        // Lấy danh sách sản phẩm từ bảng HistoryGift
        $giftList = HistoryGift::whereIn('id', $historyIds)
            ->select('product_id', DB::raw('COUNT(*) as quantity'))
            ->groupBy('product_id')
            ->pluck('quantity', 'product_id');
        
        // Lấy tên sản phẩm
        $productNames = WareHouseProduct::whereIn('id', $giftList->keys())->pluck('name', 'id');
    
        // Chuẩn bị dữ liệu sản phẩm để tạo phiếu xuất
        $result = $giftList->map(function ($quantity, $productId) use ($productNames) {
            return [
                'id' => $productId,
                'name' => $productNames[$productId] ?? 'Không xác định',
                'quantity' => $quantity,
            ];
        })->values()->all();
    
        DB::beginTransaction();
        try {
            // Tạo phiếu xuất kho
            $params = $request->except('gifts') + [
                'type' => Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho'],
                'admin_created_id' => Auth::guard('admin')->id(),
                'staff_request' => Auth::guard('admin')->id(),
                'period' => date('Y-m', strtotime($request->day_deliver)),
                'json_params' => ['list_history_id' => $historyIds]
            ];
            $entry = WareHouseEntry::create($params);
            WarehouseService::autoUpdateCode($entry->id, 'QUATANG');
    
            // Xử lý từng sản phẩm
            $total_money = 0;
            foreach ($result as $gift) {
                $entryDetail = WarehouseService::createdWareHouseEntryDetail(
                    $entry->id, $gift['id'], $gift['quantity'], 
                    $entry->warehouse_id_deliver, $entry->period, 
                    $entry->staff_entry, Consts::WAREHOUSE_TYPE_ENTRY['xuat_kho']
                );
                $total_money += $entryDetail->subtotal_money;
    
                // Kiểm tra tồn kho và trừ số lượng
                if (!WarehouseService::minusQuantityAsset($gift['id'], $gift['quantity'], $entry->warehouse_id_deliver)) {
                    DB::rollBack();
                    throw new Exception("Số lượng tồn kho {$gift['name']} không đủ (Cần: {$gift['quantity']}, Tồn kho: " . WarehouseService::getTonkho($gift['id'], $entry->warehouse_id_deliver) . ")!");
                }
            }
    
            // Cập nhật tổng tiền và lưu lại
            $entry->update(['total_money' => $total_money]);
    
            // Cập nhật trạng thái lịch sử quà tặng
            HistoryGift::whereIn('id', $historyIds)->update([
                'status' => Consts::GIFT_STATUS['daxuat'],
                'admin_updated_id' => Auth::guard('admin')->id()
            ]);
    
            DB::commit();
            return redirect()->route('deliver_warehouse.show', $entry->id)->with('successMessage', 'Xuất kho thành công!');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', $ex->getMessage());
        }
    }

    public function listHistory(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = 'QUATANG';
        $rows = WareHouseEntry::getSqlWareHouseWareHouseEntry($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        // Lấy thông tin lớp học
       
        $this->responseData['rows'] = $rows;
        // $this->responseData['warehouses'] = WareHouse::getSqlWareHouse()->get();
        $this->responseData['warehouses'] = DB::table('tb_warehouses')->get();
        $this->responseData['module_name'] = 'Lịch sử cấp phát quà tặng';
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.list_history');
    }
    public function listHistoryDetail($id)
    {
        $entry = WareHouseEntry::find($id);

        // Lấy lịch sử phát quà và nhóm theo học viên
        $gift_distribution = HistoryGift::whereIn('id', $entry->json_params->list_history_id)
            ->with(['student', 'student.course', 'product']) // Tối ưu truy vấn với Eager Loading
            ->get()
            ->groupBy('student_id'); // Nhóm theo ID học viên

        $this->responseData['gift_distribution'] = $gift_distribution;
        $this->responseData['entry'] = $entry;
        $this->responseData['module_name'] = 'Chi tiết lịch sử cấp phát quà tặng';

        return $this->responseView($this->viewPart . '.detail_history');
    }
    public function listGiftDistributionStudent(Request $request)
    {
        $params = $request->all();
        $gift_distribution = HistoryGift::getSqlHistoryGift($params)->with(['student', 'student.course', 'product'])
            ->get()
            ->groupBy('student_id'); 

        $this->responseData['gift_distribution'] = $gift_distribution;
        $this->responseData['params'] = $params;
        $this->responseData['courses'] = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $this->responseData['module_name'] = 'Thống kê danh sách học viên đã nhận quà';
        return $this->responseView($this->viewPart . '.list_book_distribution_student');
    }

}
