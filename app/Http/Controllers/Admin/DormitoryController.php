<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Exception;
use App\Models\Dormitory;
use App\Models\Dormitory_user;
use App\Models\DormitoryHistory;
use App\Models\Dormitory_muster;
use App\Models\Area;
use App\Models\Language;
use App\Models\Student;
use App\Models\StatusStudent;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DataPermissionService;
use App\Http\Services\DormitoryService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DormitoryImportStudent;
use App\Imports\DormitoryImport;
use App\Exports\DormitoryExport;
use App\Exports\DormitoryReportExportStudent;
use Aws\Api\Service;
use Carbon\Carbon;


class DormitoryController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'dormitory';
        $this->viewPart = 'admin.pages.dormitory';
        $this->responseData['module_name'] = __('Quản lý ký túc xá');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id_user = Auth::guard('admin')->user()->id;
        $list_areaId = DataPermissionService::getPermisisonAreas($id_user);
        $params = $request->all();
        $params['list_area'] = $list_areaId;
        $rows = Dormitory::getSqlDormitory($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        $params_area['id'] = $list_areaId;
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['status'] = Consts::STATUS_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id_user = Auth::guard('admin')->user()->id;
        $params_area['id'] = DataPermissionService::getPermisisonAreas($id_user);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['status'] = Consts::STATUS_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
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
            'name' => 'required|max:255',
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        // check phòng đã tồn tại chưa
        $params_check['name'] = $params['name'];
        $params_check['don_nguyen'] = $params['don_nguyen'];
        $params_check['area_id'] = $params['area_id'];
        $params_check['status_other_deactive'] = true;
        $check = Dormitory::getSqlDormitory($params_check)->first();
        if ($check) {
            return redirect()->back()->with('errorMessage', __('Phòng đã tồn tại!'));
        }
        $params['json_params']['name'][$lang] = $request['name'];
        $dormitory = Dormitory::create($params);
        $params_create = [
            'id_dormitory' => $dormitory->id,
            'time_in' => $params['time_start']
        ];
        DormitoryService::createDormitoryHistory($params_create);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dormitory  $dormitory
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dormitory $dormitory)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dormitory  $dormitory
     * @return \Illuminate\Http\Response
     */
    public function edit(Dormitory $dormitory)
    {
        $id_user = Auth::guard('admin')->user()->id;
        $params_area['id'] = DataPermissionService::getPermisisonAreas($id_user);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['status'] = Consts::STATUS_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['detail'] = $dormitory;
        return $this->responseView($this->viewPart . '.edit');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dormitory  $dormitory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dormitory $dormitory)
    {
        $params = $request->all();
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['json_params']['name'][$lang] = $params['name'];
        // check sô lượng học viên thuê đang thuê
        $param_user['dormitory'] = $dormitory->id;
        $param_user['status'] = Consts::STATUS_DORMITORY_USER['already'];
        $count_user = Dormitory_user::getSqlDormitoryUser($param_user)->count();
        if ($count_user > $params['slot']) {
            return redirect()->back()->with('errorMessage', __('Số lượng học viên đang thuê lớn hơn số chỗ !'));
        }
        if ($count_user == $params['slot']) {
            $params['status'] = Consts::STATUS_DORMITORY['full'];
        }
        $dormitory->fill($params);
        $dormitory->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }
    public function setCheckOut(Request $request)
    {
        $id = (int)$request->only('id')['id'];
        $time = $request->only('time')['time'];
        $dormitory = Dormitory::find($id);
        if ($dormitory) {
            // nếu có học viên đang ở thì k thể trả phòng
            $params['dormitory'] = $id;
            $params['status'] = Consts::STATUS_DORMITORY_USER['already'];
            $count_user = Dormitory_user::getSqlDormitoryUser($params)->count();
            if ($count_user > 0) {
                return redirect()->back()->with('errorMessage', __('Vẫn còn học viên đang thuê phòng!'));
            }
            $dormitory->status = Consts::STATUS_DORMITORY['deactive'];
            $dormitory->gender = Consts::GENDER['other'];
            $dormitory->save();
            $where['id_dormitory'] = $id;
            $value['time_out'] = $time;
            DormitoryService::updateData($where, $value);
            session()->flash('successMessage',  __('Trả phòng thành công!'));
        } else {
            session()->flash('errorMessage',  __('Phòng không tồn tại!'));
        }
        return $this->sendResponse('successMessage', 'Cập nhật thành công!');
    }
    public function setCheckIn(Request $request)
    {
        $id = (int)$request->only('id')['id'];
        $time = $request->only('time')['time'];
        $dormitory = Dormitory::find($id);
        if ($dormitory) {
            $dormitory->status = Consts::STATUS_DORMITORY['empty'];
            $dormitory->time_start = $time;
            $dormitory->gender = Consts::GENDER['other'];
            $dormitory->save();
            $params_create = [
                'id_dormitory' => $id,
                'time_in' => $time
            ];

            DormitoryService::createDormitoryHistory($params_create);
            session()->flash('successMessage',  __('Cập nhật thành công!'));
        } else {
            session()->flash('errorMessage',  __('Phòng không tồn tại!'));
        }
        return $this->sendResponse('successMessage', 'Cập nhật thành công!');
    }

    public function history(Request $request)
    {
        $id_user = Auth::guard('admin')->user()->id;
        $list_areaId = DataPermissionService::getPermisisonAreas($id_user);
        $params = $request->all();
        $params['list_area'] = $list_areaId;
        $rows = DormitoryHistory::getSqlDormitoryHistory($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $id_user = Auth::guard('admin')->user()->id;
        $params_area['id'] = $list_areaId;
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['status'] = Consts::STATUS_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Quản lý lịch sử thuê ký túc xá');
        return $this->responseView($this->viewPart . '.history');
    }
    public function editHistory(Request $request)
    {
        $params = $request->all();
        $where['id'] = $params['id'];
        $value['time_in'] = $params['time_in'];
        $value['time_out'] = $params['time_out'];
        DormitoryService::updateData($where, $value);
        session()->flash('successMessage',  __('Cập nhật thành công!'));
        return $this->sendResponse('successMessage', 'Cập nhật thành công!');
    }

    public function addStudent(Request $request)
    {
        $params = $request->except('admin_code', 'gender', 'status_dormitory');
        $request->validate([
            'admin_code' => 'required|max:255',
        ]);
        DB::beginTransaction();
        try {
            // lấy thông tin học viên
            $student = Student::where('admin_code', trim($request->admin_code))->first();
            $gender = $request->gender ?? '';
            $status_dormitory = $request->status_dormitory ?? '';
            if ($student) {
                // cập nhật lại giới tính của học viên
                $student->gender = $gender;
                $student->status_dormitory = $status_dormitory;
                $student->save();

                // check xem hv đã tồn tại trong ktx chưa
                $dormitory_user = Dormitory_user::where('id_user', $student->id)->where('status', Consts::STATUS_DORMITORY_USER['already'])->first();
                if (empty($dormitory_user)) {
                    $params['id_user'] =  $student->id;
                    Dormitory_user::create($params);
                }
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Xác nhận thành công!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    public function getStudent(Request $request)
    {

        $params = $request->all();
        if (isset($params['type']) && $params['type'] == 'admin_code') {
            // lấy thông tin học viên theo mã
            $student = Student::where('admin_code', trim($params['admin_code']))->first();
            if ($student) {
                return $this->sendResponse('success', 'Lấy thông tin thành công!');
            } else {
                return $this->sendResponse('warning', 'Mã học viên ' . $params['admin_code'] . ' không tồn tại !');
            }
        }
        // lấy thông tin user của phòng
        $dormitory_user = Dormitory_user::getSqlDormitoryUser($params)->first();
        if ($dormitory_user->time_out != '') {
            return $this->sendResponse('error', 'Học viên đã trả phòng không thể sửa!');
        }
        $dormitory['user_name'] = $dormitory_user->user_name;
        // $dormitory['id_dormitory'] = $dormitory_user->id_dormitory??null;
        $dormitory['id_user'] = $dormitory_user->id_user;
        $dormitory['time_in'] = $dormitory_user->time_in;
        $dormitory['time_out'] = $dormitory_user->time_out;
        $dormitory['time_expires'] = $dormitory_user->time_expires;
        $dormitory['gender'] = $dormitory_user->student->gender;
        $dormitory['don_vao'] = $dormitory_user->json_params->don_vao ?? '';
        $dormitory['ghi_chu'] = $dormitory_user->json_params->ghi_chu ?? '';
        return $this->sendResponse($dormitory, 'Lấy thông tin thành công');
    }
    public function editStudent(Request $request)
    {
        $params = $request->all();
        $request->validate([
            'id' => 'required|max:255',
        ]);
        $id = $params['id'];
        unset($params['id']);
        $gender = $params['gender'];
        unset($params['gender']);
        DB::beginTransaction();
        try {
            // lấy thông tin user của phòng
            $dormitory_user = Dormitory_user::find($id);
            // Cập nhật giới tính học viên
            if ($gender != '') {
                $student = Student::find($dormitory_user->id_user);
                $student->gender = $gender;
                $student->save();
            }
            // cập nhật
            $dormitory_user->fill($params);
            $dormitory_user->save();
            DB::commit();
            return redirect()->back()->with('successMessage', __('Update successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    public function importStudent(Request $request)
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
            $import = new DormitoryImportStudent($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            $data_count = $import->getRowCount();
            $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
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

    public function importDormitory(Request $request)
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
            $import = new DormitoryImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            $data_count = $import->getRowCount();
            $mess = __('Thêm mới') . ": " . $data_count['insert_row'] . " - " . __('Cập nhật') . ": " . $data_count['update_row'] . " - " . __('Lỗi') . ": " . $data_count['error_row'];
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

    public function exportStudent(Request $request)
    {
        $params = $request->all();
        return Excel::download(new DormitoryExport($params), 'Danh_sach_hoc_vien_ktx.xlsx');
    }

    public function destroy(Dormitory $dormitory)
    {
        $dormitory->delete();
        Dormitory_user::where('id_dormitory', $dormitory->id)->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete successfully!'));
    }

    public function listStudent(Request $request)
    {
        $params = $request->all();
        $this->responseData['status'] = Consts::STATUS_DORMITORY_USER;
        $this->responseData['gender'] = Consts::GENDER;
        $admin = Auth::guard('admin')->user();
        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();

        $this->responseData['dormitory'] =  Dormitory::all();
        $params['list_user'] = DataPermissionService::getPermissionStudents($admin->id);
        $this->responseData['rows'] =  Dormitory_user::getSqlDormitoryUser($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        // $params_student['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        // $params_student['state'] = Consts::STUDENT_STATUS['main learning'];
        // $this->responseData['student'] = Student::getsqlStudentIndex($params_student)->get();
        $this->responseData['status_study'] =  StatusStudent::getSqlStatusStudent()->get();
        $this->responseData['params'] = $params;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['module_name'] = 'Tổng hợp danh sách học viên KTX';
        return $this->responseView($this->viewPart . '.list_user');
    }

    public function deleteStudent(Request $request)
    {
        $params = $request->all();
        DB::beginTransaction();
        try {
            foreach ($params['id'] as $id) {
                // check học viên đang ở hay rời đi
                $dormitory_user = Dormitory_user::find($id);
                if ($dormitory_user->status == 'already') {
                    throw new Exception("Học viên đang ở không thể xóa!");
                }
            }

            Dormitory_user::destroy($params['id']);
            session()->flash('successMessage', 'Xóa học viên thành công!');
            DB::commit();
            return $this->sendResponse('successMessage', 'Xóa học viên thành công!');
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function ReportDormitory(Request $request)
    {
        $params = $request->all();
        $this->responseData['module_name'] = 'Báo cáo tổng hợp KTX hiện tại';

        $DormitoryService = new DormitoryService();
        $list_area = $DormitoryService->reportDormitory();
        $this->responseData['list_area'] = $list_area;

        return $this->responseView($this->viewPart . '.report_dormitory');
    }

    public function ReportDormitoryMonth(Request $request)
    {
        $params = $request->all();
        $DormitoryService = new DormitoryService();
        if (isset($params['from_month']) && isset($params['to_month']) && $params['from_month'] != '' && $params['to_month'] != '') {
            $list_area = $DormitoryService->reportDormitoryMonth($params['from_month'] ?? '', $params['to_month'] ?? '');
            $this->responseData['list_area'] = $list_area;
        }
        $this->responseData['params'] =  $params;
        $this->responseData['module_name'] = __('Thống kê chi tiết theo từng tháng');
        return $this->responseView($this->viewPart . '.report_dormitory_month');
    }

    public function exportReportStudent(Request $request){
        $params = $request->all();
        return Excel::download(new DormitoryReportExportStudent($params), 'Danh_sach_hoc_vien_ktx.xlsx');
    }
    public function getStudentArea(Request $request)
    {
        $params = $request->all();
        // Lấy tất cả các học viên ở ktx theo khu vực và theo tháng
        $dormitory_user = Dormitory_user::getSqlDormitoryUser($params)->whereNotNull('id_dormitory')->get();
        $data = [];
        $responsive['area_name'] = Area::find($params['area_id'])->name;
        if (count($dormitory_user) > 0) {
            foreach ($dormitory_user as $key => $val) {
                $data[$key]['admin_code'] = $val->admin_code ?? '';
                $data[$key]['user_name'] = $val->user_name ?? '';
                $data[$key]['staff_name'] = $val->student->admission->name ?? '';
                $data[$key]['user_gender'] = __($val->user_gender ?? '');
                $data[$key]['dormitory_name'] = $val->dormitory->name ?? '';
                $data[$key]['status'] = ($val->time_out =='' || date('Y-m', strtotime($val->time_out)) > $params['months_come_leave'])?__(Consts::STATUS_DORMITORY_USER['already']):__(Consts::STATUS_DORMITORY_USER['leave']);
                $data[$key]['time_in'] = $val->time_in != '' ? date('d/m/Y', strtotime($val->time_in)) : '--/--/----';
                $data[$key]['time_out'] = $val->time_out != '' ? date('d/m/Y', strtotime($val->time_out)) : '--/--/----';
                $responsive['area_name'] = $val->dormitory->area->name;
            }
        }
        $responsive['list'] = $data;
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, nam
        $student_come_male = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    Carbon::parse($item->time_in)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_in)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'male';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, nu
        $student_come_female = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    Carbon::parse($item->time_in)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_in)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'female';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày vào trong tháng, khac
        $student_come_other = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    Carbon::parse($item->time_in)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_in)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'other';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, nam
        $student_out_male = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    !empty($item->time_out) &&
                    Carbon::parse($item->time_out)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_out)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'male';
            })
            ->count();

        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, nu
        $student_out_female = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    !empty($item->time_out) &&
                    Carbon::parse($item->time_out)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_out)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'female';
            })
            ->count();
        // lấy tất cả studen thuộc khu vực, ngày ra trong tháng, khac
        $student_out_other = $dormitory_user
            ->filter(function ($item, $key) use ($params) {
                return $item->dormitory->area->id == $params['area_id'] &&
                    !empty($item->time_out) &&
                    Carbon::parse($item->time_out)->month == Carbon::parse($params['months_come_leave'])->month &&
                    Carbon::parse($item->time_out)->year == Carbon::parse($params['months_come_leave'])->year &&
                    $item->user_gender == 'other';
            })
            ->count();

        $responsive['student_come_male'] = $student_come_male;
        $responsive['student_come_female'] = $student_come_female;
        $responsive['student_come_other'] = $student_come_other;
        $responsive['student_out_male'] = $student_out_male;
        $responsive['student_out_female'] = $student_out_female;
        $responsive['student_out_other'] = $student_out_other;
        return $this->sendResponse($responsive, 'Lấy thông tin thành công');
    }

    public function getInforStudent(Request $request)
    {
        $admin_code = $request->admin_code ?? '';
        $student = '';
        if ($admin_code != '') {
            $student = Student::where('admin_code', trim($admin_code))->first();
        }
        return $this->sendResponse($student, 'Lấy thông tin thành công!');
    }

    public function expiredStudent(Request $request)
    {
        $this->responseData['module_name'] = __('Danh sách học viên sắp hết hạn');
        $admin = Auth::guard('admin')->user();
        $params = $request->all();
        $time_now = time();
        $time_expired = 15 * 86400; // thời gian lấy là 15 ngày
        $day_now = date('Y-m-d', $time_now);
        $day_expired = date('Y-m-d', ($time_now + $time_expired));
        $params['day_expired'] = $day_expired;
        $params['status'] = Consts::STATUS_DORMITORY_USER['already'];
        $params['list_user'] = DataPermissionService::getPermissionStudents($admin->id);
        $domitory_user = Dormitory_user::getSqlDormitoryUser($params)->whereNotNull('id_dormitory')->where('tb_dormitory_user.time_expires', '>', $day_now)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);;
        $this->responseData['rows'] = $domitory_user;

        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['params'] = $params;
        $this->responseData['status'] = Consts::STATUS_DORMITORY_USER;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['dormitory'] =  Dormitory::all();
        return $this->responseView($this->viewPart . '.expires_student');
    }

    public function listMuster(Request $request)
    {
        $params = $request->all();
        $time_now = date('Y-m-d', time());
        $params['time_muster'] = isset($params['time_muster']) && $params['time_muster'] != '' ? $params['time_muster'] : $time_now;
        $this->responseData['params'] = $params;
        $this->responseData['module_name'] = __('Quản lý điểm danh KTX');

        $this->responseData['status'] = Consts::STATUS_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
        $admin = Auth::guard('admin')->user();
        $list_id_area = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['id'] = $list_id_area;
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $params['status_other'] = Consts::STATUS_DORMITORY['empty'];
        $params['status_other_deactive'] = true;

        $params['list_area'] = $list_id_area;
        $rows = Dormitory::getSqlDormitoryReportMuster($params)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['dormitory'] =  Dormitory::all();

        return $this->responseView($this->viewPart . '.list_muster');
    }

    public function getMuster(Request $request)
    {

        DB::beginTransaction();
        try {
            $admin = Auth::guard('admin')->user();
            $params = $request->all();
            $params_muster['id_dormitory'] = $request->id ?? '';
            $params_muster['time_muster'] = $request->time ?? '';
            if ($params_muster['time_muster'] == '') {
                return redirect()->back()->with('errorMessage', __('Cần chọn ngày điểm danh!'));
            }
            // check phòng đã điểm danh ngày hôm đó chưa
            $check = Dormitory_muster::getSqlDormitoryMuster($params_muster)->count();
            if ($check <= 0) {
                //nếu chưa -> lấy thông tin user trong phòng add vào bảng lịch sử điểm danh theo ngày
                $params_user['status'] = Consts::STATUS_DORMITORY_USER['already'];
                $params_user['dormitory'] = $params_muster['id_dormitory'];
                $dormitory_user = Dormitory_user::getSqlDormitoryUser($params_user)->get();
                if ($dormitory_user) {
                    foreach ($dormitory_user as $item) {
                        $params_add['id_user'] = $item->id_user;
                        $params_add['id_dormitory'] = $item->id_dormitory;
                        $params_add['time_muster'] = $params_muster['time_muster'];
                        $params_add['admin_created_id'] = Auth::guard('admin')->user()->id;
                        $params_add['admin_updated_id'] = Auth::guard('admin')->user()->id;
                        Dormitory_muster::create($params_add);
                    }
                }
            }
            $dormitory_muster = Dormitory_muster::getSqlDormitoryMuster($params_muster)->get();
            $this->responseData['rows'] =  $dormitory_muster;
            $this->responseData['dormitory'] =  Dormitory::find($params_muster['id_dormitory']);
            $this->responseData['params'] =  $params;
            $this->responseData['status'] =  Consts::STATUS_DORMITORY_MUSTER;
            $this->responseData['reason'] =  Consts::REASON_DORMITORY;
            $this->responseData['module_name'] = __('Điểm danh KTX');
            DB::commit();
            return $this->responseView($this->viewPart . '.get_muster');
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function updateMuster(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            foreach ($params['data'] as $key => $val) {
                $dormitory_muster = Dormitory_muster::find($key);
                $dormitory_muster->status = $val['status'];
                $dormitory_muster->json_params = $val['json_params'];
                $dormitory_muster->save();
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function reportMuster(Request $request)
    {
        $params = $request->all();
        $time_now = date('Y-m-d', time());
        $params['time_muster'] = isset($params['time_muster']) && $params['time_muster'] != '' ? $params['time_muster'] : $time_now;
        $admin = Auth::guard('admin')->user();
        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['module_name'] = __('Báo cáo điểm danh KTX');
        $this->responseData['rows'] = Dormitory::getSqlDormitoryReportMuster($params)->get();
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.report_muster');
    }

    // Danh sách HV đăng ký KTX
    public function listStudentRegister(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        // $params['dormitory'] = $params['dormitory'] ?? 'ktx';
        $params['status_dormitory'] = Consts::STATUS_PAYMENT_DORMITORY['not_paid'];
        $params['list_id'] = DataPermissionService::getPermissionStudents($admin->id);
        $rows = Student::getsqlStudentIndex($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        // Lấy khu vực
        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();

        $this->responseData['params'] = $params;
        $this->responseData['dormitory'] = Consts::DORMITORY;
        $this->responseData['status_payment_dormitory'] = Consts::STATUS_PAYMENT_DORMITORY;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['module_name'] = 'Danh sách học viên đăng ký vào KTX';
        return $this->responseView($this->viewPart . '.list_user_register');
    }
    // Danh sách học viên đã đóng tiền KTX và chờ xếp phòng
    public function listStudentPaid(Request $request)
    {
        $params = $request->all();
        $admin = Auth::guard('admin')->user();
        $params['list_user'] = DataPermissionService::getPermissionStudents($admin->id);
        $rows = Dormitory_user::getSqlDormitoryUser($params)->whereNull('id_dormitory')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        // Lấy khu vực
        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $params_dormitory['status_other'] = 'full';
        $params_dormitory['status_other_deactive'] = true;
        $this->responseData['dormitory'] =  Dormitory::getSqlDormitory($params_dormitory)->get();
        $this->responseData['params'] = $params;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['module_name'] = 'Danh sách học viên chờ xếp phòng';
        return $this->responseView($this->viewPart . '.list_user_paid');
    }
    // cập nhật phòng cho hv đã đăng ký KTX
    public function updateStudentPaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            $request->validate([
                'id' => 'required|max:255',
                'id_dormitory' => 'required',
            ]);
            // lấy thông tin phòng
            $dormitory = Dormitory::find($params['id_dormitory']);
            // lấy thông tin user của phòng
            $dormitory_user = Dormitory_user::find($params['id']);
            // kiểm tra học viên đang ở trong phòng nào chưa
            $params_check['status'] =  Consts::STATUS_DORMITORY_USER['already'];
            $params_check['id_user'] =  $dormitory_user->id_user;
            $check_user =  Dormitory_user::getSqlDormitoryUser($params_check)->whereNotNull('id_dormitory')->first();
            if ($check_user) {
                return redirect()->back()->with('errorMessage', 'Học viên ' . $check_user->user_name . ' đã được ghép vào phòng ' . $check_user->dormitory->name . '!');
            }
            // kiểm tra slot của phòng còn k
            if ($dormitory->slot <= $dormitory->dormitoryUsers()->where('status', 'already')->count()) {
                return redirect()->back()->with('errorMessage', __('Phòng đã đầy!'));
            }
            // cập nhật học viên vào phòng
            $dormitory_user->id_dormitory = $dormitory->id;
            $dormitory_user->save();
            // cập nhật lại số lượng phòng và check trạng thái
            DormitoryService::updateStatusDormitory($dormitory, 'checkin', $dormitory_user);
            DB::commit();
            return redirect()->back()->with('successMessage', __('Update successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    // Danh sách hv đang ở cho quản sinh
    public function listStudentOverseer(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $params = $request->all();
        $params['list_user'] = DataPermissionService::getPermissionStudents($admin->id);
        $params['status'] = Consts::STATUS_DORMITORY_USER['already'];
        $this->responseData['rows'] =  Dormitory_user::getSqlDormitoryUser($params)->whereNotNull('id_dormitory')->orderBy('id_dormitory', 'ASC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $params_area['id'] = DataPermissionService::getPermisisonAreas($admin->id);
        $params_area['status'] = Consts::USER_STATUS['active'];
        $this->responseData['area'] =  Area::getSqlArea($params_area)->get();
        $this->responseData['dormitory'] =  Dormitory::all();
        $this->responseData['params'] = $params;
        $this->responseData['status'] = Consts::STATUS_DORMITORY_USER;
        $this->responseData['gender'] = Consts::GENDER;
        $this->responseData['module_name'] = 'Danh sách học viên đang thuê phòng KTX';
        return $this->responseView($this->viewPart . '.list_user_overseer');
    }

    // cập nhật trạng thái đổi phòng hoặc trả phòng cho VH
    public function leaveOrChangeRoom(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();
            $request->validate([
                'id' => 'required|max:255',
            ]);
            $id = $params['id'];
            unset($params['id']);
            $dormitory_user = Dormitory_user::find($id);
            // lấy thông tin của phòng hiện tại
            $dormitory_index = Dormitory::find($dormitory_user->id_dormitory);

            // trường hợp đổi phòng (là cho ra khỏi phòng hiện tại và thêm vào phòng mới với ngày ra và ngày vào là hôm nay)
            if (isset($params['id_dormitory']) && $params['id_dormitory'] != '') {
                // Thông tin phòng muốn đổi và check slot của phòng
                $dormitory = Dormitory::find($params['id_dormitory']);
                if ($dormitory->slot <= $dormitory->dormitoryUsers()->where('status', 'already')->count()) {
                    return redirect()->back()->with('errorMessage', __('Phòng đã đầy!'));
                }
                // Cho ra khỏi phòng hiện tại, đổi trạng thái phòng hiện tại và đổi trạng thái học viên
                DormitoryService::updateStatusDormitory($dormitory_index, 'checkout', $dormitory_user);
                $params_user['status'] = Consts::STATUS_DORMITORY_USER['leave'];
                $params_user['time_out'] = date('Y-m-d', time());
                $dormitory_user->fill($params_user);
                $dormitory_user->save();
                // Thêm bản ghi của user vào phòng mới, cập nhật lại trạng thái và số lượng của phòng và user, ngày vào + 1
                $params_create['id_dormitory'] =  $dormitory->id;
                $params_create['id_user'] =  $dormitory_user->id_user;
                $params_create['time_in'] =  Carbon::now()->addDay()->format('Y-m-d');
                $params_create['time_expires'] =  $dormitory_user->time_expires;
                $params_create['json_params'] =  $dormitory_user->json_params;
                $dormitory_user_new =  Dormitory_user::create($params_create);
                DormitoryService::updateStatusDormitory($dormitory, 'checkin', $dormitory_user_new);

            }

            // trường hợp trả phòng
            if (isset($params['time_out']) && $params['time_out'] != '') {
                $params['status'] = Consts::STATUS_DORMITORY_USER['leave'];
                // cập nhật số lượng và trạng thái phòng
                DormitoryService::updateStatusDormitory($dormitory_index, 'checkout', $dormitory_user);
                $dormitory_user->fill($params);
                $dormitory_user->save();
            }

            DB::commit();
            return redirect()->back()->with('successMessage', __('Update successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    public function updateQuantity()
    {
        $dormitory = Dormitory::all();
        foreach ($dormitory as $key => $val) {
            $val->quantity = $val->dormitoryUsers()->where('status', 'already')->count();
            $val->status = Consts::STATUS_DORMITORY['already'];
            if ($val->quantity <= 0) {
                $val->status = Consts::STATUS_DORMITORY['empty'];
            } elseif ($val->quantity >= $val->slot) {
                $val->status = Consts::STATUS_DORMITORY['full'];
            };
            $val->save();
        }
        return redirect()->back()->with('successMessage', __('Update successfully!'));
    }
}
