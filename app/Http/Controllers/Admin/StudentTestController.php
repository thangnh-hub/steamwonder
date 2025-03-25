<?php

namespace App\Http\Controllers\Admin;

use App\Models\StudentTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Consts;
use Exception;

class StudentTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'student_test';
        $this->responseData['module_name'] = __('Quản lý đề thi test học viên');
    }
    public function index(Request $request)
    {
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
        $params = $request->all();
        $question = StudentTest::create($params);
        if ($question) return redirect()->back()->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentTest  $studentTest
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTest $studentTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentTest  $studentTest
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentTest $studentTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentTest  $studentTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTest $studentTest)
    {
        $params = $request->except(['ajax_update']);
        $studentTest->fill($params);
        $studentTest->save();

        $check_ajax = $request['ajax_update'] ?? '';

        if ($check_ajax != '') {
            return $this->sendResponse('success', __('Cập nhật câu hỏi thành công!'));
        }
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentTest  $studentTest
     * @return \Illuminate\Http\Response
     */
    public function destroyAjax(Request $request)
    {
        try {
            $studentTest = StudentTest::find($request->id)->delete();
            if ($studentTest) return $this->sendResponse('', 'success');
            else return $this->sendResponse('', __('No records available!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
}
