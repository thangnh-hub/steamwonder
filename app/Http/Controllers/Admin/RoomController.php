<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Area;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;

class RoomController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'rooms';
    $this->viewPart = 'admin.pages.rooms';
    $this->responseData['module_name'] = __('Rooms Management');
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
    $rows = Room::getSqlRoom($params)->orderBy('tb_rooms.area_id')->orderBy('tb_rooms.id')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['areas'] = Area::getSqlArea(['status' => 'active'])->get();
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
    $this->responseData['areas'] = Area::getSqlArea(['status' => 'active'])->get();
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['type'] = Consts::ROOM_TYPE;
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
      'name' => 'required|max:255',
      'area_id' => 'required',
    ]);

    $params = $request->all();
    Room::create($params);
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Room $room)
  {
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Room $room)
  {
    $this->responseData['areas'] = Area::getSqlArea(['status' => 'active'])->get();
    $this->responseData['detail'] = $room;
    $this->responseData['status'] = Consts::STATUS;
    $this->responseData['type'] = Consts::ROOM_TYPE;

    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Room $room)
  {
    $request->validate([
      'name' => 'required|max:255',
      'area_id' => 'required',

    ]);
    $params = $request->all();

    $room->fill($params);
    $room->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Room $room)
  {
    $room->delete();

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
