<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\ScheduleTest;
use App\Models\UserAction;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\HistoryScheduleTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class ScheduleTestController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'schedule_test';
        $this->viewPart = 'admin.pages.schedule_test';
        $this->responseData['module_name'] = __('Schedule Test - Training Management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['from_date']=isset($params['from_date'])?$params['from_date']:date('Y-m-d',time());

        $sheduele_test_id = $request->schedule_id;
        if ($sheduele_test_id) {
            $params_history['id_schedule_test']=$sheduele_test_id;
            $list_history = HistoryScheduleTest::getSqlHistory($params_history)->get();
            return $this->sendResponse($list_history);
        }
        // Get list post with filter params
        $rows = ScheduleTest::getSqlScheduleTest($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $history_schedule_test=HistoryScheduleTest::get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['history_schedule_test'] =  $history_schedule_test;
        $this->responseData['type'] = Consts::SCHEDULE_TEST;
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
        $this->responseData['type'] = Consts::SCHEDULE_TEST;
        $this->responseData['admin_action'] = Admin::where('admin_type',Consts::ADMIN_TYPE['diplomatic'])->where('status',Consts::STATUS['active'])->get();
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
            'is_type' => 'required',
        ]);
        $params = $request->except('list');
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        foreach($request->list as $item){
            $params['time']=$item['time'];
            $params['slot']=$item['slot'];
            ScheduleTest::create($params);
        }

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Tạo lịch thành công!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScheduleTest  $scheduleTest
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleTest $scheduleTest)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScheduleTest  $scheduleTest
     * @return \Illuminate\Http\Response
     */
    public function edit(ScheduleTest $scheduleTest)
    {
        $this->responseData['detail'] = $scheduleTest;
        $this->responseData['type'] = Consts::SCHEDULE_TEST;
        $this->responseData['admin_action'] = Admin::where('admin_type',Consts::ADMIN_TYPE['diplomatic'])->where('status',Consts::STATUS['active'])->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleTest  $scheduleTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleTest $scheduleTest)
    {
        $request->validate([
            'is_type' => 'required',
            'time' => 'required',
        ]);
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $scheduleTest->fill($params);
        $scheduleTest->save();
        return redirect()->back()->with('successMessage', __('Cập nhật thành công!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScheduleTest  $scheduleTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleTest $scheduleTest)
    {
        $scheduleTest->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Đã xóa thành công!'));

    }

    public function ajaxUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            if(isset($request->idHistory)){
                $historyScheduleTest=HistoryScheduleTest::find($request->idHistory);
                //update
                if($historyScheduleTest){
                    $json = [
                        "note" =>  $request->note,
                    ];
                    $historyScheduleTest->json_params=$json;
                    $historyScheduleTest->result=$request->result;
                    $historyScheduleTest->save();
                }
                    //Nếu không đạt hay vắng mặt thì chuyển sang buổi test tiếp
                    $shecdule_test_now=ScheduleTest::find($historyScheduleTest->id_schedule_test)->time;//Lịch test hiện tại
                    if($request->typeHistory=='test') $shecdule_test_next=ScheduleTest::where('is_type','test')->where('time', '>', $shecdule_test_now)->where('time', '>', Carbon::now())->orderBy('created_at', 'asc')->first();//lịch test tiếp theo
                    if($request->typeHistory=='training')$shecdule_test_next=ScheduleTest::where('is_type','training')->where('time', '>', $shecdule_test_now)->where('time', '>', Carbon::now())->orderBy('created_at', 'asc')->first();//lịch test tiếp theo
                    $count_notpass_or_absent=HistoryScheduleTest::where('id_user_action',$request->id_user_action)->where('result','nopass')->orwhere('result','absent')->get();
                    if(count($count_notpass_or_absent)==1 ||count($count_notpass_or_absent)==2){
                        if(isset($shecdule_test_next)){
                            $params_add['id_user_action']=$request->id_user_action;
                            $params_add['id_schedule_test']=$shecdule_test_next->id;
                            $params_add['id_admin']=$request->id_admin;
                            $js = [
                                "note" =>  "Test lại",
                            ];
                            $params_add['json_params']=$js;
                            HistoryScheduleTest::create($params_add);
                        }
                    }
                    //Không đạt hoặc vắng lần 3 thì bị loại
                    elseif(count($count_notpass_or_absent)==3){
                        $param_update['is_type']="test";
                        $param_update['id_user_action']=$request->id_user_action;
                        $update=HistoryScheduleTest::getSqlScheduleTestUser($param_update)->update(['result' => 'cancel']);
                    }    
                
                $messageResult = "Cập nhật thành công !";
            }
            else $messageResult = "Không tìm thấy bản ghi";
            $data = 'success';
            DB::commit();
            return $this->sendResponse($data, $messageResult);
        } catch (Exception $ex) {
            DB::rollBack();
            abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
        }
    }
}
