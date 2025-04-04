<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\App;

class ParameterController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'parameter';
    $this->viewPart = 'admin.pages.parameter';
    $this->responseData['module_name'] = __('Parameter setting');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $rows = Parameter::where('parent_id', NULL)->where('status', Consts::STATUS['active'])->orderBy('iorder')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] =  $rows;
    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $this->responseData['taxonomy'] =  Consts::TAXONOMY;
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
      'is_type' => 'required'
    ]);
    $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
    $params = $request->all();
    if (isset($params['lang'])) {
      $lang = $params['lang'];
      unset($params['lang']);
    }

    $params['json_params']['name'][$lang] = $request['name'];
    $propertie = Parameter::create($params);
    $id_redirect = $propertie->parent_id ?? 0;
    if ($id_redirect == 0) {
      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }
    return redirect()->route($this->routeDefault . '.edit', $id_redirect)->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Parameter  $parameter
   * @return \Illuminate\Http\Response
   */
  public function show(Parameter $parameter)
  {
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Parameter  $parameter
   * @return \Illuminate\Http\Response
   */
  public function edit(Parameter $parameter)
  {

    $this->responseData['detail'] = $parameter;
    $this->responseData['items'] = Parameter::where('parent_id', $parameter->id)->orderBy('iorder')->get();
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Parameter  $parameter
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Parameter  $parameter)
  {
    $request->validate([
      'name' => 'required|max:255'
    ]);
    $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
    $params = $request->all();
    if (isset($params['lang'])) {
      $lang = $params['lang'];
      unset($params['lang']);
    }
    $params['json_params']['name'][$lang] = $params['name'];
    $arr_insert = $params;
    if ($parameter->json_params != "") {
      foreach ($parameter->json_params as $key => $val) {
        if (isset($arr_insert['json_params'][$key])) {
          if ($arr_insert['json_params'][$key] != null) {
            if (isset($arr_insert['json_params'][$key])) {
              if (!is_array($params['json_params'][$key])) {
                $arr_insert['json_params'][$key] = $params['json_params'][$key];
              } else {

                $arr_insert['json_params'][$key] = array_merge((array)$val, $params['json_params'][$key]);
              }
            } else {
              $arr_insert['json_params'][$key] = $val;
            }
          }
        }
      }
    }

    $parameter->fill($arr_insert);
    $parameter->save();
    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Parameter  $parameter
   * @return \Illuminate\Http\Response
   */
  public function destroy(Parameter $parameter)
  {
    $parameter->status = Consts::STATUS_DELETE;
    $parameter->save();
    // Update delete status sub
    Parameter::where('parent_id', '=', $parameter->id)->update(['status' => Consts::STATUS_DELETE]);

    return redirect()->back()->with('successMessage', __('Delete record successfully!'));
  }
  public function delete()
  {
    if (!request()->ajax()) {
      return response()->json(['error' => 1, 'msg' => __('Method not allowed!')]);
    } else {
      $id = request('id');
      $check = Parameter::where('parent_id', $id)->count();
      if ($check) {
        return response()->json(['error' => 1, 'msg' => __('This item has menu children!')]);
      } else {
        Parameter::destroy($id);
      }
      return response()->json(['error' => 0, 'msg' => '']);
    }
  }

  public function updateSort()
  {
    $data = request('menu') ?? [];
    $root_id = request('root_id') ?? null;
    $reSort = json_decode($data, true);
    $newTree = [];
    foreach ($reSort as $key => $level_1) {
      $newTree[$level_1['id']] = [
        'parent_id' => $root_id,
        'iorder' => ++$key,
      ];
      if (!empty($level_1['children'])) {
        $list_level_2 = $level_1['children'];
        foreach ($list_level_2 as $key => $level_2) {
          $newTree[$level_2['id']] = [
            'parent_id' => $level_1['id'],
            'iorder' => ++$key,
          ];
          if (!empty($level_2['children'])) {
            $list_level_3 = $level_2['children'];
            foreach ($list_level_3 as $key => $level_3) {
              $newTree[$level_3['id']] = [
                'parent_id' => $level_2['id'],
                'iorder' => ++$key,
              ];
            }
          }
        }
      }
    }
    $response = (new Parameter)->reSort($newTree);
    return $response;
  }
}
