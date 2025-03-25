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
        $this->routeDefault  = 'rooms';
        $this->viewPart = 'admin.pages.rooms';
        $this->responseData['module_name'] = 'Rooms Management';
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
        
        $rows = Room::getSqlRoom($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramAreas['status'] = Consts::STATUS['active'];
        $this->responseData['parents'] = Area::getSqlArea($paramAreas)->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paramAreas['status'] = Consts::STATUS['active'];

        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = Area::getSqlArea($paramAreas)->get();
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type'] = Consts::ROOM_TYPE;
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
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();

        if (isset($params['import']) && isset($params['file'])) {

            Excel::import(new Eimport($params), request()->file('file'));
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        }
        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $params['json_params']['name'][$lang] = $request['name'];
        $room = Room::create($params);
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
        $paramAreas['status'] = Consts::STATUS['active'];

        $this->responseData['parents'] = Area::getSqlArea($paramAreas)->get();
        $this->responseData['detail'] = $room;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['type'] = Consts::ROOM_TYPE;

        $paramSchedule['room_id'] = $room->id;
        $list_schedule=Schedule::getSqlSchedule($paramSchedule)->get();
        $this->responseData['list_schedule'] = $list_schedule;

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
        if ($room->json_params != "") {
            foreach ($room->json_params as $key => $val) {
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
        $room->fill($arr_insert);
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
        $room->status = Consts::STATUS_DELETE;
        $room->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}