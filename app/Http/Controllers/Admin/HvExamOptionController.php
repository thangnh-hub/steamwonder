<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\HvExamOption;
use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HvExamOptionController extends Controller
{
    protected $arr_lervel;
    protected $arr_group;

    public function __construct()
    {
        $this->arr_lervel = [1, 2, 3, 4, 5, 6];
        $this->arr_group = ['1','1a', '1b', '2', '3', '4', '5'];
        $this->routeDefault  = 'hv_exam_option';
        $this->viewPart = 'admin.pages.hv_exam_option';
        $this->responseData['module_name'] = __('Cấu hình đề thi');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $rows = HvExamOption::getSqlHvExamOption($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        $this->responseData['arr_group'] = $this->arr_group;
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
            'id_level' => "required",
            'skill_test' => "required",
            'topic' => "required",
        ]);

        DB::beginTransaction();
        try {
            $params = $request->only(
                'id_level',
                'organization',
                'skill_test',
                'json_params',
            );

            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
            $params['json_params']['topic'] = $request->only('topic')['topic'];
            $option = HvExamOption::create($params);
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HvExamOption  $hvExamOption
     * @return \Illuminate\Http\Response
     */
    public function show(HvExamOption $hvExamOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HvExamOption  $hvExamOption
     * @return \Illuminate\Http\Response
     */
    public function edit(HvExamOption $hvExamOption)
    {
        $this->responseData['skill'] = Consts::TYPE_SKILL;
        $this->responseData['type'] = Consts::SCORE_TYPE;
        $this->responseData['levels'] = Level::whereIn('id', $this->arr_lervel)->get();
        $this->responseData['detail'] = $hvExamOption;
        $this->responseData['arr_group'] = $this->arr_group;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HvExamOption  $hvExamOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HvExamOption $hvExamOption)
    {
        $request->validate([
            'id_level' => "required",
            'skill_test' => "required",
            'topic' => "required",
        ]);

        DB::beginTransaction();
        try {
            $params = $request->only(
                'id_level',
                'organization',
                'skill_test',
                'json_params',
            );
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $params['json_params']['topic'] = $request->only('topic')['topic'];
            $hvExamOption->fill($params);
            $hvExamOption->save();
            DB::commit();
            return redirect()->route($this->routeDefault . '.edit',$hvExamOption->id)->with('successMessage', __('Cập nhật thành công!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HvExamOption  $hvExamOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(HvExamOption $hvExamOption)
    {
        $hvExamOption->delete();
        return redirect()->back()->with('successMessage', __('Xóa thông tin thành công!'));
    }
}
