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
    parent::__construct();
    $this->routeDefault  = 'holiday';
    $this->viewPart = 'admin.pages.holidays';
    $this->responseData['module_name'] = __('Holidays Management');
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
    // Get list post with filter params
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

    $data = [];
    foreach ($request->date as $key => $item) {
      $params['date']      = $item;
      $params['status'] = Consts::STATUS['active'];
      $params['name'] = $request->name;

      array_push($data, $params);
    }

    Holiday::insert($data);
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
