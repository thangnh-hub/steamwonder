<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Student;
use App\Models\tbParent;
use App\Models\Relationship;
use App\Models\StudentParent;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'students';
        $this->viewPart = 'admin.pages.students';
        $this->responseData['module_name'] = __('Students Management');
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
        $allParents= tbParent::getSqlParent($params_active)->get();
        $this->responseData['allParents'] = $allParents;

        //danh sách mqh
        $this->responseData['list_relationship'] = Relationship::getSqlRelationship($params_active)->get();
        //lấy ra danh sách mqh của học sinh
        $this->responseData['studentParentIds'] = $student->studentParents->pluck('parent_id')->toArray();

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
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $student->update($params);

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
        foreach ($parentsInput as $parentId => $data) {
            if (!empty($data['id'])) {
                StudentParent::create([
                    'student_id'      => $student->id,
                    'parent_id'       => $data['id'],
                    'relationship_id' => $data['relationship_id'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('successMessage', __('Cập nhật người thân thành công!'));
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
}
