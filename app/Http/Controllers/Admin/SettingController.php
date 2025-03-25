<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Widget;
use App\Models\WidgetConfig;
use Illuminate\Http\Request;
use stdClass;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'settings';
        $this->viewPart = 'admin.pages.settings';
        $this->responseData['module_name'] = 'Settings Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang = !empty($request->get('lang')) ? $request->get('lang') . '-' : null;
        $this->responseData['lang'] = $lang;

        $rows = Setting::get();
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $widgets = Widget::getSqlWidget($params_widget)->get();
        $widgetConfig = WidgetConfig::all();
        $this->responseData['widgets'] = $widgets;
        $this->responseData['widgetConfig'] = $widgetConfig;
        $this->responseData['all_setting'] = $rows;
        if ($rows) {
            $setting = new stdClass();
            foreach ($rows as $item) {
                $setting->{$item->option_name} = $item->option_value;
            }
            $this->responseData['setting'] = $setting;
        }

        // dd($this->responseData);
        return $this->responseView($this->viewPart . '.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lang = !empty($request->get('lang')) ? $request->get('lang') . '-' : null;
        $params = $request->except(
            [
                '_token',
                'lang'
            ]
        );
        // dd($lang);
        foreach ($params as $key => $value) {
            Setting::updateOrInsert(
                ['option_name' => $lang . $key],
                ['option_value' => $value]
            );
        }

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        // Do not use this function
        return redirect()->back();
    }

    public function settingTheme(Request $request)
    {
        $this->responseData['module_name'] = 'Theme Settings';

        $rows = Setting::get();
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $widgets = Widget::getSqlWidget($params_widget)->get();
        $widgetConfig = WidgetConfig::all();
        $this->responseData['widgets'] = $widgets;
        $this->responseData['widgetConfig'] = $widgetConfig;
        if ($rows) {
            $setting = new stdClass();
            foreach ($rows as $item) {
                $setting->{$item->option_name} = $item->option_value;
            }
            $this->responseData['setting'] = $setting;
        }

        return $this->responseView($this->viewPart . '.setting_theme');
    }
}
