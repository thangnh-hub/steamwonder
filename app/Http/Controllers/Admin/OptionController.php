<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'options';
        $this->viewPart = 'admin.pages.options';
        $this->responseData['module_name'] = 'Options Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Option::paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] = $rows;
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
            'option_name' => 'required|unique:tb_options|max:255',
            'option_value' => 'required',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Option::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function show(Option $option)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function edit(Option $option)
    {
        $this->responseData['detail'] = $option;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $option)
    {
        $request->validate([
            'option_name' => 'required|max:255|unique:tb_options,option_name,' . $option->id,
            'option_value' => 'required',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $option->fill($params);
        $option->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        return redirect()->back()->with('errorMessage', __('Record cannot be deleted!'));
    }
}
