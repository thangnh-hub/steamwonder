<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\AccountingDebt;
use App\Models\Student;
use App\Models\tbClass;
use App\Models\Admin;
use App\Models\Course;
use App\Models\StatusStudent;
use App\Models\Field;
use App\Models\Area;
use App\Models\Level;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountingDebtExport;
use App\Imports\AccountingDebtImport;



class AccountingDebtController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'accounting_debt';
        $this->viewPart = 'admin.pages.accounting_debt';
        $this->responseData['module_name'] = 'Công nợ kế toán';
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
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // $params['state'] = Consts::STUDENT_STATUS['main learning'];
        // Get list post with filter params
        if(isset($params['from_date']) && $params['from_date'] != ''){
            $params['from_date_official'] = Carbon::parse($params['from_date'])->subDays(150)->format('Y-m-d');
        }
        if(isset($params['to_date']) && $params['to_date'] != ''){
            $params['to_date_official'] = Carbon::parse($params['to_date'])->subDays(150)->format('Y-m-d');
        }
        // dd($params);
        $rows = Student::getsqlStudentAccounting($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $staffs = Admin::where('admin_type', Consts::ADMIN_TYPE['admission'])
            ->where('status', Consts::STATUS['active'])
            ->get();
        $class = tbClass::getsqlClass()->get();
        $course = Course::where('status', 'active')->orderBy('tb_courses.day_opening', 'desc')->get();
        $status_student = StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['staffs'] =  $staffs;
        $this->responseData['course'] =  $course;
        $this->responseData['class'] =  $class;
        $this->responseData['status_study'] =  $status_student;
        $this->responseData['status'] = Consts::STUDENT_STATUS;
        $this->responseData['contract_type'] = Consts::CONTRACT_TYPE;
        $this->responseData['contract_status'] = Consts::CONTRACT_STATUS;
        $this->responseData['type_revenue'] = Consts::TYPE_REVENUE;
        $this->responseData['field'] = Field::getsqlField()->get();
        $this->responseData['version'] = Consts::VERSION_DEPT;
        $this->responseData['params'] = $params;
        $this->responseData['area'] =  Area::where('status', '=', Consts::USER_STATUS['active'])->get();

        /** Cập nhật lại trạng trái thanh toán khi tải lại trang */
        if(request()->query() == null){
            Student::where('admin_type', Consts::ADMIN_TYPE['student'])->update([
                'json_params->status_accounting_debt' => ''
            ]);
        }
        /** Lấy danh sách level và level tiếp theo*/
        $levels = Level::getSqlLevel()->get();
        $this->responseData['levels'] = $levels;

        return $this->responseView($this->viewPart . '.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountingDebt  $accountingDebt
     * @return \Illuminate\Http\Response
     */
    public function show(AccountingDebt $accountingDebt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountingDebt  $accountingDebt
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountingDebt $accountingDebt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountingDebt  $accountingDebt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountingDebt $accountingDebt)
    {
        $params = $request->all();
        $accountingDebt->fill($params);
        $accountingDebt->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountingDebt  $accountingDebt
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountingDebt $accountingDebt) {}

    /** Lấy view công nợ truyển ajax */
    public function getListAccountingDebt(Request $request)
    {
        $student_id = $request->only('student_id')['student_id'];
        $student = Student::find($student_id);
        $list_accounting = AccountingDebt::where('student_id', $student_id)->get();
        $type_revenue = Consts::TYPE_REVENUE;
        $result['html'] = view($this->viewPart . '.view_accounting_ajax', compact('list_accounting', 'type_revenue'))->render();
        $result['student'] = $student;
        return $this->sendResponse($result, 'Thấy thông tin thành công');
    }

    /** Thêm mới lịch sử */
    public function createAccountingDebt(Request $request)
    {
        try {
            $params['student_id'] = $request->student_id;
            $params['type_revenue'] = $request->type;
            $params['amount_paid'] = $request->money;
            $params['time_payment'] = $request->time;
            $params['json_params']['note'] = $request->note;
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            // Check chưa có thì tạo
            $check = AccountingDebt::where('student_id', $params['student_id'])->where('type_revenue', $params['type_revenue'])->count();
            if ($check > 0) {
                return $this->sendResponse('warning', 'Khoản thu đã tồn tại!');
            }
            AccountingDebt::create($params);
            return $this->sendResponse('success', 'Lưu thông tin thành công');
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
    public function updateHistory(Request $request)
    {
        try {
            $id = $request->only('id')['id'];
            $student_id = $request->only('student_id')['student_id'];
            $type_revenue = $request->only('type_revenue')['type_revenue'];
            $check = AccountingDebt::where('student_id', $student_id)->where('type_revenue', $type_revenue)->where('id', '!=', $id)->count();
            if ($check > 0) {
                return $this->sendResponse('warning', 'Loại khoản thu đã tồn tại !');
            }
            $accountingDebt = AccountingDebt::find($id);
            if ($accountingDebt) {
                $params['type_revenue'] = $request->type_revenue;
                $params['amount_paid'] = $request->amount_paid;
                $params['time_payment'] = $request->time_payment;
                $params['json_params']['note'] = $request->note;
                $accountingDebt->fill($params);
                $accountingDebt->save();
                return $this->sendResponse('success', 'Cập nhật thành công');
            } else {
                return $this->sendResponse('warning', 'Không tìm thấy dữ liệu!');
            }
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
    public function deleteHistory(Request $request)
    {
        $id = $request->only('id')['id'];
        $accountingDebt = AccountingDebt::find($id);
        if ($accountingDebt) {
            $accountingDebt->delete(); // Xóa bản ghi
            return $this->sendResponse('success', 'Xóa thông tin thành công');
        } else {
            return $this->sendResponse('warning', 'Lỗi kết nối!');
        }
    }
    public function exportStudent(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // $params['state'] = Consts::STUDENT_STATUS['main learning'];
        return Excel::download(new AccountingDebtExport($params), 'Student.xlsx');
    }
    public function importHistory(Request $request)
    {
        $params = $request->all();
        if (isset($params['file'])) {
            if ($this->checkFileImport($params['file']) == false) {
                $_datawith = 'errorMessage';
                $mess = 'File Import không hợp lệ, có chứ Sheet ẩn !';
                session()->flash($_datawith, $mess);
                return $this->sendResponse($_datawith, $mess);
            }
            $_datawith = 'successMessage';
            $import = new AccountingDebtImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            $data_count = $import->getRowCount();
            $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
            foreach ($data_count['error_mess'] as $val) {
                $mess .= '</br>' . $val;
            };
            if (count($data_count['error_mess']) > 0) {
                $_datawith = 'errorMessage';
            };
            session()->flash($_datawith, $mess);
            return $this->sendResponse($_datawith, $mess);
        }
        session()->flash('errorMessage', __('Cần chọn file để Import!'));
        return $this->sendResponse('warning', __('Cần chọn file để Import!'));
    }
    /** Cập nhật trạng thái tài chính của học viên */
    public function updateStatusAccountingDebtStudent(Request $request)
    {
        try {
            $student_id = $request->only('student_id')['student_id'];
            $status = $request->only('status')['status'] ?? '';
            $student = Student::find($student_id);
            $jsonParams = (array) $student->json_params ?? [];
            $jsonParams['status_accounting_debt'] = $status;
            $student->json_params = $jsonParams;
            $student->save();
            return $this->sendResponse('success', 'Cập nhật thành công');
        } catch (Exception $ex) {
            abort(422, __($ex->getMessage()));
        }
    }
}
