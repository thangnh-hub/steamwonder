<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;
use App\Models\StudentTest;
use Illuminate\Http\Request;
use App\Consts;
use Illuminate\Support\Facades\DB;
use Exception;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'topic';
        $this->viewPart = 'admin.pages.topic';
        $this->responseData['module_name'] = __('Quản lý đề thi test học viên');
    }
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = Topic::getSqlTopic($params)->orderBy('id', 'desc')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['type'] = Consts::TYPE_STUDENT_TEST;
        $this->responseData['status'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['type'] = Consts::TYPE_STUDENT_TEST;
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
        $request->validate([
            'name' => 'required',
            'type' => "required",
            'content' => "required",
        ]);
        $params = $request->all();
        $topic = Topic::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Topic $topic)
    {

        $this->responseData['type'] = Consts::TYPE_STUDENT_TEST;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['detail'] = $topic;
        $this->responseData['list_question'] = StudentTest::where('id_topic', $topic->id)->get();

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required',
            'content' => "required",
        ]);
        $params = $request->all();
        $topic->fill($params);
        $topic->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        DB::beginTransaction();
        try {
            StudentTest::where('id_topic', $topic->id)->delete();
            $topic->delete();
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    public function formAddQuestion(Request $request)
    {
        try {
            $params = $request->all();
            return $this->responseView($this->viewPart . '.insert.' . $params['type']);
            // if($params['type']=='text') return $this->responseView($this->viewPart . '.insert.text');
            // if($params['type']=='math') return $this->responseView($this->viewPart . '.insert.math');
            // if($params['type']=='eye_training') return $this->responseView($this->viewPart . '.insert.eye_training');
            // if($params['type']=='logic') return $this->responseView($this->viewPart . '.insert.logic');

        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
    public function formEditQuestion(Request $request)
    {
        try {
            $question = StudentTest::find($request->id);
            if ($question) {
                $this->responseData['question'] = $question;
                if ($request->type == 'text')  return $this->responseView($this->viewPart . '.update.text');
                if ($request->type == 'math')  return $this->responseView($this->viewPart . '.update.math');
                if ($request->type == 'eye_training')  return $this->responseView($this->viewPart . '.update.eye_training');
                if ($request->type == 'logic')  return $this->responseView($this->viewPart . '.update.logic');
            } else return redirect()->back()->with('errorMessage', __('Không tìm thấy thông tin câu hỏi'));
        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
