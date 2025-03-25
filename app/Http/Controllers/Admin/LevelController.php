<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Level;
use App\Models\Subject;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'levels';
        $this->viewPart = 'admin.pages.levels';
        $this->responseData['module_name'] = 'Levels Management';
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

        $rows = Level::getSqlLevel($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramSubjects['status'] = Consts::SUBJECT_STATUS['active'];
        $this->responseData['parents'] = Subject::getSqlSubject($paramSubjects)->get();
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
        $paramSubjects['status'] = Consts::STATUS['active'];

        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $this->responseData['parents'] = Subject::getSqlSubject($paramSubjects)->get();
        $this->responseData['status'] = Consts::STATUS;
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
        $level = Level::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $level)
    {
        $paramSubjects['status'] = Consts::TAXONOMY_STATUS['active'];

        $this->responseData['parents'] = Subject::getSqlSubject($paramSubjects)->get();
        $this->responseData['detail'] = $level;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required|max:255',
            'subject_id' => 'required',
            'code' => 'required',
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
        if ($level->json_params != "") {
            foreach ($level->json_params as $key => $val) {
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
        $level->fill($arr_insert);
        $level->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {

        $level->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
