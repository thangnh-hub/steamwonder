<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Schedule;
use App\Models\tbClass;
use App\Models\Period;
use App\Models\Area;
use App\Models\Teacher;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;
use Exception;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'schedules';
        $this->viewPart = 'admin.pages.schedules';
        $this->responseData['module_name'] = 'Schedules Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $params = $request->all();
        $params['teacher_id'] = Auth::guard('admin')->user()->id;
        $rows = Schedule::getSqlSchedule($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;

        $paramClass['type'] = 'lopchinh';
        $paramClass['teacher_id'] = Auth::guard('admin')->user()->id;
        $this->responseData['class'] = tbClass::getSqlClass($paramClass)->get();

        $params['status'] = Consts::STATUS['active'];
        $this->responseData['room'] = Room::getSqlRoom($params)->get();
        $this->responseData['period'] = Period::getSqlPeriod($params)->get();
        $this->responseData['area'] = Area::getSqlArea($params)->get();

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
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        $paramStatus['status'] = Consts::STATUS['active'];

        $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
        $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
        $this->responseData['detail'] = $schedule;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'date' => 'required',
            'room_id' => 'required',
            'teacher_id' => 'required',
            'period_id' => 'required',
            'status' => 'required',
        ]);
        $params = $request->all();

        $schedule->fill($params);
        $schedule->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    // Danh sách buổi học giáo viên nước ngoài
    public function listScheduleGVNN(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $paramStatus['status'] = Consts::STATUS['active'];
        $params_class['permission'] = DataPermissionService::getPermissionClasses($admin->id);
        $class = tbClass::getSqlClass($params_class)->get();
        $this->responseData['class'] = $class;
        $this->responseData['module_name'] = 'Cập nhật lịch dạy cho GVNN';
        $this->responseData['period'] = Period::getSqlPeriod($paramStatus)->get();
        $this->responseData['room'] = Room::getSqlRoom($paramStatus)->get();
        $this->responseData['teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $this->responseData['class_id'] = $request->only('class_id')['class_id'] ?? '';
        return $this->responseView($this->viewPart . '.gvnn');
    }

    // Lấy buổi học đã có theo lớp
    public function getScheduleGVNN(Request $request)
    {
        try {
            $class_id = $request->class_id;
            $type = $request->type;
            $count = $request->count ?? 0;

            $class = tbClass::find($class_id);
            $admin = Auth::guard('admin')->user();
            $paramStatus['status'] = Consts::STATUS['active'];
            $params['permission'] = DataPermissionService::getPermissionClasses($admin->id);
            $params['class_id'] = $class_id;
            $params['type'] = 'gvnn';
            $schedule = Schedule::getSqlSchedule($params)->get();
            $period = Period::getSqlPeriod($paramStatus)->get();
            $paramStatus['area_id'] = $class->area_id;
            $room = Room::getSqlRoom($paramStatus)->get();
            $teacher = Teacher::getSqlTeacher($paramStatus)->get();
            $result['html'] = '';
            $result['count_lesson'] = 0;
            if ($schedule) {
                $result['html'] = view($this->viewPart . '.view_ajax_list_schedule', compact('class', 'schedule', 'params', 'period', 'room', 'teacher', 'type', 'count'))->render();
                $result['count_lesson'] = $schedule->count();
            }
            return $this->sendResponse($result, 'Lấy thông tin thành công');
        } catch (Exception $ex) {
            return $this->sendResponse('error', __($ex->getMessage()));
        }
    }

    public function createdScheduleGVNN(Request $request)
    {
        DB::beginTransaction();
        try {
            $class_id = $request->only('class_id')['class_id'];
            $class = tbClass::find($class_id);
            $lesson = $request->only('lesson')['lesson'];
            $assistant_teacher = (array)json_decode($class->assistant_teacher, true);
            foreach ($lesson as $key => $item) {
                if ($item['id'] == null) {
                    $params['type'] = 'gvnn';
                    $params['class_id'] = $class->id;
                    $params['area_id'] = $class->area_id;
                    $params['status'] = 'chuahoc';
                    $params['date'] = $item['date'];
                    $params['period_id'] = $item['period_id'];
                    $params['room_id'] = $item['room_id'];
                    $params['teacher_id'] = $item['assistant_teacher'][0] ?? '';
                    $params['assistant_teacher'] = json_encode($item['assistant_teacher']);
                    $params['json_params']['note'] = $item['note'];
                    Schedule::create($params);
                } else {
                    $schedule = Schedule::find($item['id']);
                    $schedule->date = $item['date'];
                    $schedule->period_id = $item['period_id'];
                    $schedule->room_id = $item['room_id'];
                    $schedule->teacher_id = $item['assistant_teacher'][0] ?? '';
                    $schedule->assistant_teacher = json_encode($item['assistant_teacher']);
                    $schedule->json_params->note = $item['note'];
                    $schedule->save();
                }
                // Thêm thằng đó vào mảng giáo viên phụ
                if (isset($item['assistant_teacher'][0]) && $item['assistant_teacher'][0] != '') {
                    array_push($assistant_teacher, $item['assistant_teacher'][0] ?? '');
                }
            }
            // Lưu lại mảng giáo viên phụ vào lớp
            array_unique($assistant_teacher);
            $class->assistant_teacher = json_encode($assistant_teacher);
            $class->save();
            DB::commit();
            return redirect()->back()->with('successMessage', __('Xác nhận thành công!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }
}
