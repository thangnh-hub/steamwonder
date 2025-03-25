<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'languages';
        $this->viewPart = 'admin.pages.languages';
        $this->responseData['module_name'] = __('Languages setting');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Language::orderBy('iorder')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

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
            'lang_name' => 'required|max:255',
            'lang_locale' => 'required|max:255',
            'lang_code' => 'required|max:255'
        ]);
        $params = $request->except(['is_default', 'status']);

        Language::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        $this->responseData['detail'] = $language;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'lang_name' => 'required|max:255',
            'lang_locale' => 'required|max:255',
            'lang_code' => 'required|max:255'
        ]);

        $params = $request->except(['is_default', 'status']);
        // Cần check nếu là ngôn ngữ mặc định thay đổi phải kiểm tra trong DB để cập nhật phù hợp

        $language->fill($params);
        $language->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        // Cannot delete is_default
        if ($language->is_default) {
            return redirect()->back()->with('errorMessage', __('Record cannot be deleted!'));
        }

        $language->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function setLanguageIsDefault(Request $request)
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => __('Method not allowed!')]);
        } else {
            try {
                $id = request('id');
                Language::where('id', $id)->update(['is_default' => 1]);
                Language::where('id', '!=', $id)->update(['is_default' => 0]);

                session()->flash('successMessage', __('Successfully updated!'));

                return response()->json(['error' => 0, 'msg' => '']);
            } catch (Exception $ex) {
                return response()->json(['error' => 1, 'msg' => $ex->getMessage()]);
            }
        }
    }

    public function language($locale)
    {
        cookie()->queue(cookie('locale_admin', $locale, 7 * 24 * 60));

        return redirect()->back()->with('successMessage', __('New language is updated!'));
    }
}
