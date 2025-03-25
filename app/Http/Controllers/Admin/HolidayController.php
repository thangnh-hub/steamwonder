<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Area;
use App\Models\Holiday;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use stdClass;
use Vietnamese;


class HolidayController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'holiday';
        $this->viewPart = 'admin.pages.holidays';
        $this->responseData['module_name'] = 'Holidays Management';
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
        $rows = Holiday::getsqlHoliday($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] =  $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['postStatus'] = Consts::STATUS;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['status'] = Consts::STATUS['active'];
        // Get list post with filter params
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['status'] = Consts::STATUS;

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
        ]);
        $params = $request->all();
        $data = [];
        foreach ($request->date as $key => $item) {
            $params2['date']      = $item;
            $params2['status'] = Consts::STATUS['active'];
            $params2['name'] = $request->name;

            array_push($data, $params2);
        }
        $holiday = Holiday::insert($data);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        $this->responseData['detail'] = $holiday;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
      
        // Get list post with filter params
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'name' => 'required|max:255',
            'date' => 'required',
        ]);
        $params = $request->all();
        // dd($arr_insert);
        $holiday->fill($params);
        $holiday->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
  
}
