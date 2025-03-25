<?php

namespace App\Http\Controllers\Admin;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\tbClass;
use App\Models\Teacher;
use App\Models\Student;
use App\Http\Services\DataPermissionService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CertificateImportStudent;
use App\Exports\CertificateExport;

use Illuminate\Support\Facades\Auth;

use Exception;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->routeDefault  = 'certificate';
        $this->viewPart = 'admin.pages.certificate';
        $this->responseData['module_name'] = 'Quản lý chứng chỉ thi B1';
    }
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $params = $request->all();
        $params['year'] = $request->year ?? date('Y');
        // Get list post with filter params
        // Bổ sung phần check quyền xem dữ liệu theo giáo viên up hoặc theo id_hocvien được quản lý
        $student_ids = DataPermissionService::getPermissionStudents($admin->id); // Danh sách id_hocvien được xem
        $teacher_id = $admin->id;
        // Lấy ra danh sách theo teacher hoặc quyền dữ liệu được xem
        // Nếu là GV hoặc CBTS thì phải lọc theo đúng dữ liệu
        if ($admin->admin_type == 'teacher' || $admin->admin_type == 'admission') {
            $rows = Certificate::getSqlCertificate($params)
                ->where(function ($query) use ($student_ids, $teacher_id) {
                    $query->whereIn('tb_certificate.student_id', $student_ids)
                        ->orWhere('tb_certificate.teacher_id', $teacher_id);
                })
                ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        }
        // Còn lại sẽ lấy cả các bản ghi student_id là null
        else {
            $rows = Certificate::getSqlCertificate($params)
                ->where(function ($query) use ($student_ids, $teacher_id) {
                    $query->whereIn('tb_certificate.student_id', $student_ids)
                        ->orWhereNull('tb_certificate.student_id')
                        ->orWhere('tb_certificate.teacher_id', $teacher_id);
                })
                ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        }
        // Lấy tất cả
        $rows_all = Certificate::getSqlCertificate($params)
            ->where(function ($query) use ($student_ids, $teacher_id) {
                $query->whereIn('tb_certificate.student_id', $student_ids)
                    ->orWhere('tb_certificate.teacher_id', $teacher_id);
            })
            ->get();

        $this->responseData['rows'] =  $rows;
        $this->responseData['rows_all'] = $rows_all;

        $params_class['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        $class = tbClass::getsqlClass($params_class)->get();
        $this->responseData['class'] = $class;
        $this->responseData['params'] = $params;

        // Đếm số kỹ năng HV đã đỗ trong năm và gắn vào item
        $rows_all->each(function ($item) use ($params) {
            $skills = ['day_score_listen', 'day_score_speak', 'day_score_read', 'day_score_write'];
            $item->passedSkills = collect($skills)->filter(function ($skill) use ($item, $params) {
                return !empty($item[$skill]) && substr($item[$skill], 0, 4) == $params['year'];
            })->count();
        });
        // Thống kê đỗ bao nhiêu kỹ năng
        $statistics = ['goethe' => [], 'telc' => []];
        foreach (['goethe', 'telc'] as $type) {
            for ($i = 1; $i <= 4; $i++) {
                $statistics[$type][$i] = $rows_all->where('type', $type)->where('passedSkills', $i)->count();
            }
        }

        $teachers = Teacher::getsqlTeacher()->get();
        $this->responseData['teachers'] =  $teachers;
        $this->responseData['statistics'] = $statistics;
        // dd($statistics);
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin = Auth::guard('admin')->user();
        $params['type'] = 'lopchinh';
        // Get list post with filter params
        $params['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        $list_class = tbClass::getSqlClass($params)->get();
        $this->responseData['list_class'] =  $list_class;
        $params_student['permission'] = DataPermissionService::getPermissionStudents($admin->id);
        $params_student['state'] = Consts::STUDENT_STATUS['main learning'];
        $list_student = Student::getsqlStudentIndex($params_student)->get();
        $this->responseData['list_student'] = $list_student;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        // dd($this->responseData);
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
        // $request->validate([
        // 'student_id' => 'required',
        // 'class_id' => 'required',
        // 'type' => "required",
        // ]);
        $class_id = $request->class_id ?? null;
        $student =  $request->student ?? [];

        $class = tbClass::find($class_id);
        $result = 'successMessage';
        if (count($student) > 0) {
            $params = [];
            $count_error = 0;

            foreach ($student as $key => $item) {
                // check xem học viên đã tồn tại chưa
                $check = Certificate::where('student_id', $key)->count();
                if ($check > 0) {
                    $count_error++;
                    $result = 'errorMessage';
                    continue;
                }
                if ($item['score_listen'] != '' || $item['score_speak'] != '' || $item['score_read'] != '' || $item['score_write'] != '') {
                    $params = $item;
                    $params['student_id'] = $key;
                    $params['class_id'] = $class_id;
                    $certificate = Certificate::create($params);
                }
            }
            $mess = __('Add new successfully!');
            if ($count_error > 0) {
                $mess .= ' và có ' . $count_error . ' học viên đã nhập điểm trên hệ thống !';
            }
        }
        return redirect()->route($this->routeDefault . '.index')->with($result, $mess);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        $admin = Auth::guard('admin')->user();
        $params['type'] = 'lopchinh';
        // Get list post with filter params
        $params['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        $list_class = tbClass::getSqlClass($params)->get();
        $this->responseData['list_class'] =  $list_class;
        $this->responseData['detail'] = $certificate;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        $this->responseData['teacher'] = Teacher::where('admin_type', 'teacher')->get();
        // dd($this->responseData);
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'student_id' => 'required',
            // 'class_id' => 'required',
            'type' => "required",
        ]);
        $params = $request->all();
        $certificate->fill($params);
        $certificate->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        // $certificate->delete();
        return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Chức năng này cần xác nhận với kỹ thuật viên!'));
        // return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function studentByClass(Request $request)
    {
        try {
            $type = Consts::SCORE_TYPE;
            $params = $request->all();
            $params['order_by'] = 'id';
            $data_student = [];
            $list_student = Student::getSqlStudent($params)->get();
            $teacher = Teacher::where('admin_type', 'teacher')->get();
            $list_student = $list_student->reject(function ($val) use ($params) {
                $params_check['student_id'] = $val->id;
                $params_check['class_id'] = $params['class_id'];
                $check = Certificate::getSqlCertificate($params_check)->count();
                return $check > 0;
            });
            $result['html'] = __('No records available!');

            if ($list_student) {
                $result['html'] = view($this->viewPart . '.view_ajax_list_student', compact('list_student', 'type', 'params', 'teacher'))->render();
            }
            return $this->sendResponse($result, 'Lấy thông tin thành công');
        } catch (Exception $ex) {
            // throw $ex;
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
            $import = new CertificateImportStudent($params);
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
    public function exportCSertificate(Request $request)
    {
        $params = $request->all();
        return Excel::download(new CertificateExport($params), 'Chung_chi_b1.xlsx');
    }
}
