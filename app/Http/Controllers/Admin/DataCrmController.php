<?php

namespace App\Http\Controllers\Admin;

use App\Models\DataCrm;
use Illuminate\Http\Request;

class DataCrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function show(DataCrm $dataCrm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function edit(DataCrm $dataCrm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataCrm $dataCrm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataCrm  $dataCrm
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataCrm $dataCrm)
    {
        //
    }

    public function importDataCrm(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Handle the file upload and import logic here
    }

    public function exportDataCrm(Request $request)
    {
        // Handle export logic here
    }
}
