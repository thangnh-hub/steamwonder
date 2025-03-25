<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\tbClass;
use App\Models\Holiday;
use App\Models\Syllabus;
use App\Models\ClassProcess;
use App\Models\LessonSylabu;
use App\Http\Services\ClassProcessService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ClassProcessController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'class_process';
        $this->viewPart = 'admin.pages.class_process';
        $this->responseData['module_name'] = 'Quản lý lộ trình học tập';
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
        $rows = ClassProcess::getSqlProcessClass($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        // Xử lý lấy dữ liệu chi tiết lộ trình theo trình độ nếu có
        foreach ($rows as $key => $row) {
            $total_lesson = 0;
            $total_schedule = 0;
            if ($row->a11 != null) {
                if (isset($row->a11->syllabus_id) && $row->a11->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->a11->syllabus_id);
                    $row->a11_syllabus = $syllabus;
                }
                if (isset($row->a11->class_id) && $row->a11->class_id > 0) {
                    $class = tbClass::find($row->a11->class_id);
                    $row->a11_class = $class;
                    // Chỉ cộng khi có lớp
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->a12 != null) {
                if (isset($row->a12->syllabus_id) && $row->a12->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->a12->syllabus_id);
                    $row->a12_syllabus = $syllabus;
                }
                if (isset($row->a12->class_id) && $row->a12->class_id > 0) {
                    $class = tbClass::find($row->a12->class_id);
                    $row->a12_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->a21 != null) {
                if (isset($row->a21->syllabus_id) && $row->a21->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->a21->syllabus_id);
                    $row->a21_syllabus = $syllabus;
                }
                if (isset($row->a21->class_id) && $row->a21->class_id > 0) {
                    $class = tbClass::find($row->a21->class_id);
                    $row->a21_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->a22 != null) {
                if (isset($row->a22->syllabus_id) && $row->a22->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->a22->syllabus_id);
                    $row->a22_syllabus = $syllabus;
                }
                if (isset($row->a22->class_id) && $row->a22->class_id > 0) {
                    $class = tbClass::find($row->a22->class_id);
                    $row->a22_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->b11 != null) {
                if (isset($row->b11->syllabus_id) && $row->b11->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->b11->syllabus_id);
                    $row->b11_syllabus = $syllabus;
                }
                if (isset($row->b11->class_id) && $row->b11->class_id > 0) {
                    $class = tbClass::find($row->b11->class_id);
                    $row->b11_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->b12 != null) {
                if (isset($row->b12->syllabus_id) && $row->b12->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->b12->syllabus_id);
                    $row->b12_syllabus = $syllabus;
                }
                if (isset($row->b12->class_id) && $row->b12->class_id > 0) {
                    $class = tbClass::find($row->b12->class_id);
                    $row->b12_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }
            if ($row->otcs != null) {
                if (isset($row->otcs->syllabus_id) && $row->otcs->syllabus_id > 0) {
                    $syllabus = Syllabus::find($row->otcs->syllabus_id);
                    $row->otcs_syllabus = $syllabus;
                }
                if (isset($row->otcs->class_id) && $row->otcs->class_id > 0) {
                    $class = tbClass::find($row->otcs->class_id);
                    $row->otcs_class = $class;
                    $total_schedule += $class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count() ?? 0;
                    $total_lesson += $syllabus->lesson ?? 0;
                }
            }

            $text_total = 'N/A';
            $day_total = $total_schedule - $total_lesson;
            if ($day_total < 0) {
                $text_total = 'Nhanh: ' . abs($day_total) . ' ngày ';
            } elseif ($day_total == 0) {
                $text_total = 'Đúng tiến độ';
            } else {
                $text_total = 'Chậm: ' . $day_total . ' ngày ';
            }

            $row->total_lesson = $total_lesson;
            $row->total_schedule = $total_schedule;
            $row->text_total = $text_total;
        }

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
            'name' => 'required',
            'day_repeat' => "required",
        ]);

        $params = $request->all();
        $params['day_repeat'] = json_encode($request->input('day_repeat'));
        $classProcess = ClassProcess::create($params);
        return redirect()->route($this->routeDefault . '.edit', $classProcess->id)->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassProcess  $classProcess
     * @return \Illuminate\Http\Response
     */
    public function show(ClassProcess $classProcess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassProcess  $classProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassProcess $classProcess)
    {
        $this->responseData['syllabuss_a11'] = Syllabus::where('level_id', 1)->where('type', 'offline')->get();
        $this->responseData['syllabuss_a12'] = Syllabus::where('level_id', 2)->where('type', 'offline')->get();
        $this->responseData['syllabuss_a21'] = Syllabus::where('level_id', 3)->where('type', 'offline')->get();
        $this->responseData['syllabuss_a22'] = Syllabus::where('level_id', 4)->where('type', 'offline')->get();
        $this->responseData['syllabuss_b11'] = Syllabus::where('level_id', 5)->where('type', 'offline')->get();
        $this->responseData['syllabuss_b12'] = Syllabus::where('level_id', 6)->where('type', 'offline')->get();
        $this->responseData['syllabuss_otcs'] = Syllabus::where('level_id', 20)->where('type', 'offline')->get();

        $this->responseData['detail'] = $classProcess;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassProcess  $classProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassProcess $classProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassProcess  $classProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassProcess $classProcess)
    {
        $classProcess->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    public function classBySyllabus(Request $request)
    {
        try {
            $process = ClassProcess::find($request->process_id);
            $level = $request->level;

            $class_id = (isset($process->$level->class_id) && $process->$level->class_id != "") ? $process->$level->class_id : "";

            $params['syllabus_id'] = $request->id;
            $rows = tbClass::getSqlClass($params)->get();

            foreach ($rows as $row) {
                $row->selected = ($row->id == $class_id) ? "selected" : "";
            }
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function updateAjaxProcess(Request $request)
    {
        $request->validate([
            'syllabus_id' => 'required',
            'start_date' => "required",
        ]);
        try {
            $params['class_id'] = $request->class_id;
            if ($params['class_id'] != "") $class = tbClass::getSqlClass($params)->first();
            $process = ClassProcess::find($request->process_id);
            //Ngày kết thúc thực tế theo lớp
            $day_end_real = isset($class) && $class->day_end != "" ? $class->day_end : "";

            $check = ClassProcessService::checkLevelConditional($request->process_id, $request->level);
            if ($check) {
                $updateResult =  $process->update([
                    '' . $request->level . '->syllabus_id' => $request->syllabus_id,
                    '' . $request->level . '->class_id' => $request->class_id,
                    '' . $request->level . '->start_date' => $request->start_date,
                    '' . $request->level . '->end_date' => $request->end_date,
                    '' . $request->level . '->end_date_real' => $day_end_real,
                ]);
            } else {
                return $this->sendResponse("", 'error');
            }

            if (isset($day_end_real)) {
                return $this->sendResponse($day_end_real, 'success');
            }
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function calculatorTimeEnd(Request $request)
    {
        try {
            $params_holiday['status'] = Consts::STATUS['active'];
            $holidays = Holiday::getsqlHoliday($params_holiday)->get();
            $arr_holiday = [];
            foreach ($holidays as $key => $holiday) {
                $params_holi = strtotime($holiday->date);
                array_push($arr_holiday, $params_holi);
            }
            $schedule = LessonSylabu::where('syllabus_id', $request->syllabus_id)->get();
            $scheduleCount = $schedule->count();

            if (isset($schedule) && $scheduleCount > 0) {
                //Ngày học của buổi
                $time_bd = strtotime($request->start_date);
                $list = array();
                $day = date('d', $time_bd);
                $month = date('m', $time_bd);
                $year = date('Y', $time_bd);

                $buoi = 0;
                $arr_day_repeat = $request->day_repeat;
                for ($thang = $month; $thang <= 12; $thang++) {
                    for ($d = 1; $d <= 31; $d++) {
                        $time = strtotime($year . '-' . $thang . '-' . $d);
                        if (date('m', $time) == $thang && $time >= $time_bd && in_array(date('w', $time), $arr_day_repeat) && !in_array($time, $arr_holiday)) {
                            $list[] = date('Y-m-d', $time);
                            $buoi++;
                            if ($buoi == $scheduleCount) break;
                        }
                    }
                    if ($buoi == $scheduleCount) break;
                }

                if ($buoi < $scheduleCount) {
                    $month = 1;
                    $year += 1;
                    for ($thang = $month; $thang <= 12; $thang++) {
                        for ($d = 1; $d <= 31; $d++) {
                            $time = strtotime($year . '-' . $thang . '-' . $d);
                            if (date('m', $time) == $thang && $time >= $time_bd && in_array(date('w', $time), $arr_day_repeat) && !in_array($time, $arr_holiday)) {
                                $list[] = date('Y-m-d', $time);
                                $buoi++;
                                if ($buoi == $scheduleCount) break;
                            }
                        }
                        if ($buoi == $scheduleCount) break;
                    }
                }
                foreach ($schedule as $key => $item) {
                    $params2['date'] = $list[$key];
                }
                if ($params2['date']) {
                    return $this->sendResponse($params2['date'], 'success');
                }
                return $this->sendResponse('', __('No records available!'));
            }
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
