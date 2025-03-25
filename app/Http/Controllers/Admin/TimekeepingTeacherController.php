<?php

namespace App\Http\Controllers\Admin;

use App\Models\TimekeepingTeacher;
use App\Models\Teacher;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class TimekeepingTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'timekeeping_teacher';
        $this->viewPart = 'admin.pages.timekeeping_teacher';
        $this->responseData['module_name'] = 'Quản lý chấm công bổ sung giáo viên';
    }
     public function index(Request $request)
    {
        $params = $request->all();
        $params_teacher = [];
        $params['months'] = isset($params['months']) ? $params['months'] : date('Y-m', time());
        $monthYear = explode('-', $params['months']);
        $params['year'] = $monthYear[0];
        $params['month'] = $monthYear[1];
        if(Auth::guard('admin')->user()->admin_type=="teacher") 
        {
            $params["teacher_id"] = Auth::guard('admin')->user()->id;
            $params_teacher["teacher_id"] = Auth::guard('admin')->user()->id;
        }
        // Get list post with filter params
        $rows = TimekeepingTeacher::getSqlTimekeeping($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['teacher'] = Teacher::getsqlTeacher($params_teacher)->get();
        $this->responseData['type'] = Consts::TYPE_TIMEKEEPING;
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['rows'] =  $rows;
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
        $period = Period::getsqlPeriod()->get();
        $this->responseData['type'] = Consts::TYPE_TIMEKEEPING;
        $params['date'] = date('Y-m-d', time());
        $this->responseData['params'] = $params;
        $this->responseData['period'] = $period;
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
            'date' => 'required',
            'period' => "required",
        ]);
        $params = $request->all();
        $params["teacher_id"] = Auth::guard('admin')->user()->id;
        $params["is_approve"] = Consts::APPROVE['0'];
        $timekeeping = TimekeepingTeacher::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimekeepingTeacher  $timekeepingTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(TimekeepingTeacher $timekeepingTeacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimekeepingTeacher  $timekeepingTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(TimekeepingTeacher $timekeepingTeacher)
    {
        $period = Period::getsqlPeriod()->get();
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['period'] = $period;
        $this->responseData['type'] = Consts::TYPE_TIMEKEEPING;
        $this->responseData['detail'] = $timekeepingTeacher;
        return $this->responseView($this->viewPart . '.edit');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimekeepingTeacher  $timekeepingTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimekeepingTeacher $timekeepingTeacher)
    {
        $request->validate([
            'date' => 'required',
            'period' => "required",
        ]);
        if ($timekeepingTeacher->is_approve == Consts::APPROVE['1']) {
            return redirect()->back()->with('errorMessage', __('Trạng thái đã duyệt không được phép cập nhật !'));
        }
        $params=$request->all();
        $timekeepingTeacher->fill($params);
        $timekeepingTeacher->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimekeepingTeacher  $timekeepingTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimekeepingTeacher $timekeepingTeacher)
    {
        if ($timekeepingTeacher->is_approve == Consts::APPROVE['1']) {
            return redirect()->back()->with('errorMessage', __('Trạng thái đã duyệt không được phép xóa !'));
        }
        $timekeepingTeacher->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function approve(Request $request)
    {
        try {
            $timekeepingTeacher=TimekeepingTeacher::find($request->id);
            if (isset($timekeepingTeacher)) {
                if ($timekeepingTeacher->is_approve == Consts::APPROVE['1']) {
                    $updateResult =  $timekeepingTeacher->update([
                        'is_approve' => Consts::APPROVE['0'],
                    ]);
                }else{
                    $updateResult =  $timekeepingTeacher->update([
                        'is_approve' => Consts::APPROVE['1'],
                    ]);
                }
                if ($updateResult) {
                    return $this->sendResponse("", 'success');
                }
                return $this->sendResponse('', __('No records available!'));
            }
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
