<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Student;
use App\Models\tbParent;
use App\Models\Policies;
use App\Models\StudentPolicie;
use App\Models\Relationship;
use App\Models\StudentParent;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\StudentImport;
use App\Models\PaymentCycle;
use App\Models\Receipt;
use App\Models\Service;
use App\Models\StudentService;
use App\Models\Promotion;
use App\Models\StudentPromotion;
use App\Http\Services\ReceiptService;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'students';
        $this->viewPart = 'admin.pages.students';
        $this->responseData['module_name'] = __('Quản lý học sinh');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        // $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // Get list post with filter params
        $rows = Student::getSqlStudent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS_STUDY;
        $this->responseData['list_sex'] = Consts::GENDER;

        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id'    => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['student_code'] = 'TEMP';

        // Tạo học sinh
        $student = Student::create($params);

        // Gán lại student_code đúng chuẩn
        $student->student_code = 'HS' . str_pad($student->id, 3, '0', STR_PAD_LEFT);
        $student->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $this->responseData['detail'] = $student;
        $this->responseData['module_name'] = "Chi tiết học sinh";
        return $this->responseView($this->viewPart . '.detail');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $params_area['id'] = DataPermissionService::getPermisisonAreas(Auth::guard('admin')->user()->id);
        $this->responseData['list_area'] = Area::getsqlArea($params_area)->get();
        $this->responseData['list_status'] = Consts::STATUS_STUDY;
        $this->responseData['list_sex'] = Consts::GENDER;
        $this->responseData['detail'] = $student;
        //lấy ra danh sách tài khoản parent
        $params_active['status'] = Consts::STATUS_ACTIVE;
        $allParents = tbParent::getSqlParent($params_active)->get();
        $this->responseData['allParents'] = $allParents;
        //lấy ra danh sách mqh của học sinh
        $this->responseData['studentParentIds'] = $student->studentParents->pluck('parent_id')->toArray();

        // lấy ra danh sách  dịch vụ còn lại (chưa đăng ký)
        $studentServiceIds = $student->studentServices()->where('status', 'active')->pluck('service_id')->toArray();
        $params_service['status'] = Consts::STATUS_ACTIVE;
        $params_service['different_id'] = $studentServiceIds;
        $this->responseData['unregisteredServices'] =  Service::getSqlService($params_service)->get();
        //danh sách mqh
        $this->responseData['list_relationship'] = Relationship::getSqlRelationship($params_active)->get();
        //list chu kỳ
        $this->responseData['list_payment_cycle'] = PaymentCycle::getSqlPaymentCycle()->get();
        //list policies
        $this->responseData['list_policies'] = Policies::getSqlPolicies($params_active)->get();
        //list promotion
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['services'] = Service::where('status', 'active')->get();
        $this->responseData['promotion_active'] = StudentPromotion::where('student_id',$student->id)->where('status', 'active')->get();
        $this->responseData['list_promotion'] = Promotion::getSqlPromotion($params_active)->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'area_id'    => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'student_code' => 'unique:students,student_code,' . $student->id,
        ]);
        $params = $request->except(['includeCurrentMonth', 'policies', 'promotion_student', 'radio_promotion']);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $student->update($params);
        try {
            // Xoá các chính sách cũ
            $student->studentPolicies()->delete();

            // Thêm mới nếu có chính sách gửi lên
            if ($request->has('policies') && is_array($request->policies)) {
                foreach ($request->policies as $policyId) {
                    StudentPolicie::create([
                        'student_id' => $student->id,
                        'policy_id'  => $policyId,
                        'status'  => Consts::STATUS_ACTIVE,
                        'admin_created_id' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }

            // CT Khuyến mãi
            if ($request->has('promotion_student')){
                $params_promotion = $request->input('promotion_student');
                $params_promotion['student_id'] = $student->id;
                $params_promotion['promotion_id'] = $request->input('radio_promotion');
                StudentPromotion::create($params_promotion);
            }
            

            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
        } catch (\Exception $e) {
            return back()->with('errorMessage', __('Có lỗi xảy ra: ') . $e->getMessage());
        }

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->studentParents()->delete();
        $student->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function addParent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->studentParents()->delete();

        $parentsInput = $request->input('parents', []);
        foreach ($parentsInput as $data) {
            if (!empty($data['id'])) {
                StudentParent::create([
                    'student_id'      => $student->id,
                    'parent_id'       => $data['id'],
                    'relationship_id' => $data['relationship_id'] ?? null,
                    'admin_created_id' => Auth::guard('admin')->user()->id,
                ]);
            }
        }

        return redirect()->back()->with('successMessage', __('Cập nhật người thân thành công!'));
    }
    public function addService(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        if ($student->payment_cycle_id == "") {
            return redirect()->back()->with('errorMessage', __('Học sinh chưa chọn chu kỳ thanh toán!'));
        }
        $parentsInput = $request->input('services', []);
        foreach ($parentsInput as $data) {
            if (!empty($data['id'])) {
                StudentService::create([
                    'student_id'      => $student->id,
                    'service_id'       => $data['id'],
                    'payment_cycle_id' => $data['payment_cycle_id'] ?? null,
                    'json_params'       => [
                        'note' => $data['note'] ?? "",
                    ],
                    'status' => Consts::STATUS_ACTIVE,
                    'admin_created_id' => Auth::guard('admin')->user()->id,
                ]);
            }
        }
        return redirect()->back()->with('successMessage', __('Cập nhật đăng ký dịch vụ thành công!'));
    }

    public function importDataStudent(Request $request)
    {
        $params = $request->all();
        // Kiểm tra và validate file đầu vào
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if (!isset($params['file'])) {
            return redirect()->back()->with('errorMessage', __('Cần chọn file để Import!'));
        }

        try {
            // Import file
            $import = new StudentImport($params);
            Excel::import($import, request()->file('file'));

            return redirect()->back()->with('successMessage', 'Import data thành công');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Lỗi tại dòng " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->back()->with('errorMessage', implode("<br>", $errorMessages));
        } catch (\Exception $e) {
            // Bắt lỗi chung khác
            return redirect()->back()->with('errorMessage', 'Lỗi khi import: ' . $e->getMessage());
        }
    }


    public function deleteStudentService(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        try {
            $studentService = StudentService::find($request->id);
            if (isset($studentService)) {
                $updateResult =  $studentService->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'admin_updated_id' => $admin->id,
                ]);
                if ($updateResult) {
                    session()->flash('successMessage', __('Hủy thành công dịch vụ khỏi học sinh!'));
                    return $this->sendResponse("", 'success');
                }
            }
            session()->flash('errorMessage', __('Hủy dịch vụ không thành công! Bạn không có quyền thao tác dữ liệu!'));
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
    public function deleteStudentReceipt(Request $request)
    {
        try {
            $receipt = Receipt::find($request->id);
            if (isset($receipt)) {
                $receipt->receiptDetail()->delete();
                $receipt->delete();
                session()->flash('successMessage', __('Xóa thành công TBP của học sinh!'));
                return $this->sendResponse("", 'success');
            }
            session()->flash('errorMessage', __('Xóa không thành công! Bạn không có quyền thao tác dữ liệu!'));
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }

    public function getStudentServiceInfo(Request $request)
    {
        $id = $request->id;
        $studentService = StudentService::find($id);

        if (!$studentService) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy dịch vụ']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'note' => $studentService->json_params->note ?? "",
                'payment_cycle_id' => $studentService->payment_cycle_id ?? null, // đảm bảo có cột này
            ]
        ]);
    }

    public function updateServiceNoteAjax(Request $request)
    {
        try {
            $studentService = StudentService::find($request->id);
            if (!$studentService) {
                session()->flash('errorMessage', __('Không tìm thấy dịch vụ đăng ký!'));
            }
            $params['json_params']['note'] = $request->note ?? "";
            $params['payment_cycle_id'] = $request->payment_cycle_id ?? "";
            $studentService->update($params);

            if ($studentService->save()) {
                session()->flash('successMessage', __('Cập nhật dịch vụ thành công!'));
            }

            return $this->sendResponse("", 'success');
        } catch (\Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }

    // public function calculReceiptStudent(Request $request , ReceiptService $receiptService)
    // {
    //     $params = $request->all();
    //     $student = Student::findOrFail($params['student_id']);
    //     $data['services'] = $student->studentServices()->with('services') ->where('status', 'active')
    //     ->get()
    //     ->pluck('services');
    //     $data['include_current_month']=true;
    //     $createReceiptForStudent=$receiptService->createReceiptForStudent($student, $data);
    //     return redirect()->back()->with('successMessage', __('Tạo hóa đơn thành công!'));
    // }
    public function calculReceiptStudent(Request $request, ReceiptService $receiptService)
    {
        try {
            $params = $request->all();
            $student = Student::findOrFail($params['student_id']);

            $data['services'] = $student->studentServices()->with('services')
                ->where('status', 'active')
                ->get()
                ->pluck('services');

            if ($request->has('payment_cycle_id')) {
                $student->update([
                    'payment_cycle_id' => $request->input('payment_cycle_id', null),
                ]);
            }

            $data['include_current_month'] = $request->input('include_current_month', 0) == 1 ? true : false;
            $data['enrolled_at'] = $request->input('enrolled_at', null);
            $calcuReceipt = $receiptService->createReceiptForStudent($student, $data);
            // if ($calcuReceipt) {
            //     $student->studentServices()->update([
            //         'payment_cycle_id' => $request->input('payment_cycle_id', null),
            //     ]);
            // }
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 422);
        }
    }
    public function calculReceiptStudentRenew(Request $request, ReceiptService $receiptService)
    {
        try {
            $params = $request->all();
            $student = Student::findOrFail($params['student_id']);

            $data['student_services'] = $student->studentServices()
                ->where('status', 'active')
                ->get();

            $data['include_current_month'] = false;
            $data['enrolled_at'] = $request->input('enrolled_at', null);
            $calcuReceiptrenew = $receiptService->renewReceiptForStudent($student, $data);
            return redirect()->back()->with('successMessage', __('Cập nhật tái tục dịch vụ thành công!'));
        } catch (\Exception $e) {
            // Bắt lỗi chung khác
            return redirect()->back()->with('errorMessage', $e->getMessage());
        }
    }

}
