<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Area;
use App\Models\StatusStudent;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use stdClass;
use Vietnamese;


class AreaController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'areas';
        $this->viewPart = 'admin.pages.areas';
        $this->responseData['module_name'] = 'Areas Management';
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
        $rows = Area::getsqlArea($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
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
        $this->responseData['parent'] = Area::getsqlArea($params)->get();
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
        $lang = Language::where('is_default', 1)->first()->lang_code ?? App::getLocale();
        $params = $request->all();

        if (isset($params['lang'])) {
            $lang = $params['lang'];
            unset($params['lang']);
        }
        $code = $this->generateAreaCode($params['name']);

        $params['code'] = $code;
        $params['json_params']['name'][$lang] = $request['name'];
        $area = Area::create($params);
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
    public function edit(Area $area)
    {
        $this->responseData['detail'] = $area;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['route_name'] = Consts::ROUTE_NAME;
        $params['status'] = Consts::STATUS['active'];
        $params['different_id'] = $area->id;
        // Get list post with filter params
        $this->responseData['parent'] = Area::getsqlArea($params)->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|max:255',
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

        $code = $this->generateAreaCode($params['name']);

        $params['code'] = $code;
        $params['json_params']['name'][$lang] = $params['name'];
        $arr_insert = $params;
        // cập nhật lại arr_insert['json_params'] từ dữ liệu mới và cũ
        if ($area->json_params != "") {
            foreach ($area->json_params as $key => $val) {
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
        $area->fill($arr_insert);
        $area->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        $area->status = Consts::STATUS_DELETE;
        $area->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
    private function removeVietnameseChars($str)
    {
        $str = mb_strtolower($str, 'UTF-8');

        $str = str_replace(
            ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'đ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'],
            ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y'],
            $str
        );
        $str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
        return $str;
    }
    private function generateAreaCode($name)
    {
        $name = $this->removeVietnameseChars($name);
        $nameWords = explode(' ', $name);
        $code = '';
        foreach ($nameWords as $word) {
            $code .= strtoupper(substr($word, 0, 1));
        }
        return $code;
    }
}
