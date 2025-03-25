<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Room;
use App\Models\Level;
use App\Models\Course;
use App\Models\UserClass;
use App\Models\tbClass;
use App\Models\Syllabus;
use Exception;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'courses';
        $this->viewPart = 'admin.pages.courses';
        $this->responseData['module_name'] = 'Courses Management';
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
        $params['order_by'] = ['day_opening' => 'desc'];
        $rows = Course::getSqlCourse($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramSallybus['is_approve'] = true;
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSallybus)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['course_type'] = Consts::SYLLABUS_TYPE;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paramSyllabus['is_approve'] = true;

        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['levels'] = Level::getSqlLevel()->get();
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['course_type'] = Consts::SYLLABUS_TYPE;
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
        $request->validate([
            'name' => 'required',
            // 'level_id' => "required",
            // 'syllabus_id' => "required",
        ]);
        $params = $request->all();
        $course = Course::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {

        $paramSyllabus['is_approve'] = true;
        $paramSyllabus['type'] = $course->type;
        $this->responseData['syllabus'] = Syllabus::getSqlSyllabus($paramSyllabus)->get();
        $this->responseData['levels'] = Level::getSqlLevel()->get();

        $this->responseData['detail'] = $course;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['course_type'] = Consts::SYLLABUS_TYPE;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|max:255',
            // 'syllabus_id' => 'required',
            // 'level_id' => 'required',
        ]);
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
        $params['json_params']['name'][$lang] = $params['name'];
        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($course->json_params != "") {
            foreach ($course->json_params as $key => $val) {
                // if(in_array($key,['widget','paramater',])){continue;}
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
        $course->fill($arr_insert);
        $course->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function syllabusBylevel(Request $request)
    {
        try {
            $params['level_id'] = $request->id;
            $params['is_flag'] = $request->is_flag ?? '';
            $params['type'] = $request->type ?? '';
            $rows = Syllabus::getSqlSyllabus($params)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }


    public function courseBysyllabus(Request $request)
    {
        try {
            $level_id = $request->level_id;
            $syllabus_id = $request->syllabus_id;
            $rows = Course::where('level_id', $level_id)->where('syllabus_id', $syllabus_id)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function roomByarea(Request $request)
    {
        try {
            $area_id = $request->area_id;
            $rows = Room::where('area_id', $area_id)->get();
            if (count($rows) > 0) {
                return $this->sendResponse($rows, 'success');
            }
            return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }

    public function search(Request $request)
    {
        try {
            $params = $request->all();
            $params['order_by'] = 'id';
            // Get list post with filter params
            $rows = tbClass::getSqlClass($params)->get();
            foreach ($rows as $key => $value) {
                $rows[$key]['student_quanty'] = UserClass::where('class_id', $value->id)->get()->count();
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
}
