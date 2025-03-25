<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Exception;
use App\Models\Language;
use App\Models\Syllabus;
use App\Models\LessonSylabu;
use App\Models\Level;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LessonSyllabussImport;


use stdClass;

class SyllabusController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'syllabuss';
        $this->viewPart = 'admin.pages.syllabuss';
        $this->responseData['module_name'] = __('Syllabus Management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['type_offline'] = true;
        // Get list post with filter params
        $rows = Syllabus::getSqlSyllabus($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['approve'] = Consts::APPROVE;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['score_type'] = Consts::SCORE_TYPE;
        $this->responseData['forms_training'] = Consts::FORMS_TRAINING;
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
            'name' => 'required|unique:tb_syllabuss',
            'lesson' => "required|numeric",
            'lesson_min' => "required|numeric",
            'level_id' => "required",
        ]);
        DB::beginTransaction();
        try {
            $params = $request->except(['lesson_syllabus']);
            $params['is_flag'] = $request->is_flag ?? '0';
            $syllabus = Syllabus::create($params);

            if ($syllabus && $request['lesson_syllabus']) {
                $params_lessonSylabus = $request['lesson_syllabus'];
                foreach ($params_lessonSylabus as $item) {
                    $params2['syllabus_id'] = $syllabus->id;
                    $params2['ordinal'] = $item['ordinal'];
                    $params2['title'] = $item['title'];
                    $params2['content'] = $item['content'];
                    $params2['target'] = $item['target'];
                    $params2['teacher_mission'] = $item['teacher_mission'];
                    $params2['student_mission'] = $item['student_mission'];
                    $params2['json_params']['file'] = $item['file'];
                    $syllabus_lesson = LessonSylabu::create($params2);
                }
            }
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            // throw $ex;
            return redirect()->back()->with('errorMessage', $ex->getMessage());
            abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\Response
     */
    public function show(Syllabus $syllabuss)
    {
        $this->responseData['module_name'] = __('Chi tiết chương trình');
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['detail'] = $syllabuss;
        $lessonSylabus = LessonSylabu::where('syllabus_id', $syllabuss->id)->get();
        $this->responseData['lessonSylabus'] = $lessonSylabus;
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\Response
     */
    public function edit(Syllabus $syllabuss)
    {
        // dd($syllabuss);
        $this->responseData['parents'] = Level::getSqlLevel()->get();
        $this->responseData['detail'] = $syllabuss;
        $lessonSylabus = LessonSylabu::where('syllabus_id', $syllabuss->id)->get();
        $this->responseData['lessonSylabus'] = $lessonSylabus;
        $this->responseData['approve'] = Consts::APPROVE;
        $this->responseData['score_type'] = Consts::SCORE_TYPE;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['forms_training'] = Consts::FORMS_TRAINING;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Syllabus $syllabuss)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|unique:tb_syllabuss,name,' . $syllabuss->id,
                'level_id' => "required",
            ]);

            $params = $request->except(['lesson_syllabus', 'lesson_syllabus_add', 'files']);
            $params['is_flag'] = $request->is_flag ?? '0';
            $arr_insert = $params;

            $syllabuss->fill($arr_insert);
            $syllabuss->save();

            if ($syllabuss->save()) {
                $params_lessonSylabus = $request['lesson_syllabus'];
                foreach ($params_lessonSylabus as $item) {
                    $lesson = LessonSylabu::find($item['id']);
                    $params2['title'] = $item['title'];
                    $params2['content'] = $item['content'];
                    $params2['target'] = $item['target'];
                    $params2['teacher_mission'] = $item['teacher_mission'];
                    $params2['student_mission'] = $item['student_mission'];
                    $params2['json_params']['file'] = $item['file'] ?? '';
                    $lesson->fill($params2);
                    $lesson->save();
                }
                if (isset($request['lesson_syllabus_add']) && $request['lesson_syllabus_add'] != null) {
                    $params_lessonSylabus_add = $request['lesson_syllabus_add'];
                    foreach ($params_lessonSylabus_add as $item) {
                        $params_add['syllabus_id'] = $syllabuss->id;
                        $params_add['title'] = $item['title'];
                        $params_add['content'] = $item['content'];
                        $params_add['target'] = $item['target'];
                        $params_add['teacher_mission'] = $item['teacher_mission'];
                        $params_add['student_mission'] = $item['student_mission'];
                        $params_add['json_params']['file'] = $item['file'] ?? '';
                        LessonSylabu::create($params_add);
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            // throw $ex;
            return redirect()->back()->with('errorMessage', $ex->getMessage());
            abort(422, __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Syllabus  $syllabus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Syllabus $syllabuss)
    {
        $syllabuss->delete();
        $res = LessonSylabu::where('syllabus_id', $syllabuss->id)->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function importLessonSyllabuss(Request $request)
    {
        $params = $request->all();
        if (isset($params['file']) && isset($params['syllabuss_id']) && $params['syllabuss_id'] != '') {
            $import = new LessonSyllabussImport($params);
            Excel::import($import, request()->file('file'));
            if ($import->hasError) {
                session()->flash('errorMessage', $import->errorMessage);
                return $this->sendResponse('warning', $import->errorMessage);
            }
            session()->flash('successMessage', 'Import buổi học thành công');
            return $this->sendResponse('success', __('Import buổi học thành côngg!'));
        }
        session()->flash('errorMessage', __('Cần chọn file để Import!'));
        return $this->sendResponse('warning', __('Cần chọn file để Import!'));
    }
}
