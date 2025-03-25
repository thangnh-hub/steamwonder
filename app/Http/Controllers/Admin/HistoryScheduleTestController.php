<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\HistoryScheduleTest;
use App\Models\ScheduleTest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class HistoryScheduleTestController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'history_schedule_test';
        $this->viewPart = 'admin.pages.history_schedule_test';
        $this->responseData['module_name'] = __('History Schedule Test - Training Management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryScheduleTest  $historyScheduleTest
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryScheduleTest $historyScheduleTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HistoryScheduleTest  $historyScheduleTest
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoryScheduleTest $historyScheduleTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryScheduleTest  $historyScheduleTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HistoryScheduleTest $historyScheduleTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryScheduleTest  $historyScheduleTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoryScheduleTest $historyScheduleTest)
    {
        //
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
