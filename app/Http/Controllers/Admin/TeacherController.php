<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Role;
use App\Models\tbClass;
use App\Models\Staff;
use App\Models\Teacher;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'teachers';
        $this->viewPart = 'admin.pages.teachers';
        $this->responseData['module_name'] = 'Teachers Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = Teacher::getsqlTeacher($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }
    public function importTeacher()
    {   
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['module_name'] = 'Teachers Import';
        return $this->responseView($this->viewPart . '.import');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class = tbClass::getsqlClass()->get();
        $staff = Staff::getsqlStaff()->get();
        $teacher = Teacher::getsqlTeacher()->get();
        $direct_manager = $staff;

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $this->responseData['direct_manager'] =  $direct_manager;
        $this->responseData['class'] =  $class;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;
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
        $params = $request->all();
        if (isset($params['import']) && isset($params['file'])) {
            $import = new TeacherImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                return redirect()->back()->with('errorMessage', $import->errorMessage);
            }
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        }
        $request->validate([
            // 'name' => 'required',
            // 'admin_code' => 'required|unique:admins',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
        ]);

        // Find the last admin code
        $lastAdmin = Teacher::orderBy('id', 'desc')->first();
        $lastAdminCode = $lastAdmin->id ?? 0;
        // Extract the numeric part and increment it
        $numericPart = (int)$lastAdminCode;
        // Calculate the number of digits required for the numeric part
        $numDigits = max(4, strlen((string)$numericPart));
        // Add one to the numeric part
        $newNumericPart = str_pad($numericPart + 1, $numDigits, '0', STR_PAD_LEFT);

        $params['admin_code'] = 'GV' . $newNumericPart;
        $params['admin_type'] = Consts::ADMIN_TYPE['teacher'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['name'] = $params['json_params']['last_name'] . ' ' . $params['json_params']['middle_name'] . ' ' . $params['json_params']['first_name'];

        $teacher = Teacher::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        $class = tbClass::getsqlClass()->get();
        $staff = Staff::getsqlStaff()->get();
        $teachers = Teacher::getsqlTeacher()->get();
        $direct_manager = $staff;

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $this->responseData['direct_manager'] =  $direct_manager;
        $this->responseData['class'] =  $class;
        $this->responseData['detail'] = $teacher;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['gender'] = Consts::GENDER;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $arr_lang_code = [];
        $all_lang = Language::where('status', Consts::STATUS['active'])->get();
        foreach ($all_lang as $val) {
            $arr_lang_code[] = $val->lang_code;
        }

        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $request->validate([
            // 'name' => 'required',
            'email' => "required|email|max:255|unique:admins,email," . $teacher->id,
        ]);
        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->back()->with('errorMessage', __('Password is very short!'));
            }
            $params['password'] = $password_new;
        }
        // Find the last admin code
        $lastAdmin = Teacher::where('admin_type', Consts::ADMIN_TYPE['teacher'])->find($teacher->id);
        $lastAdminCode = $lastAdmin->id ?? 0;
        // Extract the numeric part and increment it
        $numericPart = (int)$lastAdminCode;
        // Calculate the number of digits required for the numeric part
        $numDigits = max(4, strlen((string)$numericPart));
        // Add one to the numeric part
        $newNumericPart = str_pad($numericPart, $numDigits, '0', STR_PAD_LEFT);

        // thangnh update k thay doi ma giang vien khi edit
        // $params['admin_code'] = 'GV' . $newNumericPart;
        $params['admin_type'] = Consts::ADMIN_TYPE['teacher'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['name'] = $params['json_params']['last_name'] . ' ' . $params['json_params']['middle_name'] . ' ' . $params['json_params']['first_name'];

        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($teacher->json_params != "") {
            foreach ($teacher->json_params as $key => $val) {
                if (in_array($key, ['field_id'])) {
                    continue;
                }
                if (isset($arr_insert['json_params'][$key])) {
                    if ($arr_insert['json_params'][$key] != null) {
                        if (isset($arr_insert['json_params'][$key])) {
                            if (is_array($params['json_params'][$key])) {
                                $key_lang = collect($params['json_params'][$key])->filter(function ($item, $key) use ($arr_lang_code) {
                                    return in_array($key, $arr_lang_code);
                                });
                                if (count($key_lang) > 0) {
                                    $arr_insert['json_params'][$key] = array_merge((array)$val, $params['json_params'][$key]);
                                } else {
                                    $arr_insert['json_params'][$key] = $params['json_params'][$key] ?? $val;
                                }
                            }
                        } else {
                            $arr_insert['json_params'][$key] = $val;
                        }
                    }
                }
            }
        }
        // dd($arr_insert);
        $teacher->fill($arr_insert);
        $teacher->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}