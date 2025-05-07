<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Attendances;
use App\Models\tbClass;
use App\Models\Area;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault  = 'attendance';
        $this->viewPart = 'admin.pages.attendance';
        $this->responseData['module_name'] = 'Quản lý điểm danh';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->only(['class_id', 'area_id', 'tracked_at']);
        $this->responseData['classs'] = tbClass::all();
        $this->responseData['areas'] = Area::all();
        $this->responseData['status'] = Consts::ATTENDANCE_STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['rows'] = StudentClass::getSqlStudentClass($params)->get();
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
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function show(Attendances $attendances)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendances $attendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendances $attendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendances $attendances)
    {
        //
    }
}
