<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Student;
use App\Models\Area;
use App\Http\Services\DataPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        DB::beginTransaction();
        try {
            $params = $request->all();

            $request->validate(
                [
                    'first_name' => "required|max:255",
                    'last_name' => "required|max:255",
                    'student_code' => 'nullable|max:255|unique:tb_students,student_code',
                    'gender' => "required",
                    'area_id' => "required",
                ]
            );

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->bach()->with('errorMessage', $ex->getMessage());
            abort(422, __($ex->getMessage()));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {


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
        $admin_id = Auth::guard('admin')->user()->id;
        $list_students = DataPermissionService::getPermissionStudents($admin_id);
        if (!in_array($student->id, $list_students)) {
            return redirect()->back()->with('errorMessage', __('Bạn không có quyền thao tác với dữ liệu của học viên này!'));
        }

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
        $request->validate(
            [
                'first_name' => "required|max:255",
                'last_name' => "required|max:255",
                'student_code' => "required|max:255|unique:tb_students,student_code," . $student->id,
                'gender' => "required",
                'area_id' => "required",
            ]
        );
        DB::beginTransaction();
        try {

            $student->save();
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', $ex->getMessage());
            // abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $admin_id = Auth::guard('admin')->user()->id;
        $list_students = DataPermissionService::getPermissionStudents($admin_id);
        if (!in_array($student->id, $list_students)) {
            return redirect()->back()->with('errorMessage', __('Bạn không có quyền thao tác với dữ liệu của học viên này!'));
        }
        $student->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
