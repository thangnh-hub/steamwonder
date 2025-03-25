<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\Evaluation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\tbClass;
use App\Models\StaffAdmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluationExport;
use App\Imports\Evalution;
use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'evaluations';
        $this->viewPart = 'admin.pages.evaluations';
        $this->responseData['module_name'] = 'Evaluations Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
            'class_id' => 'required',
        ]);
        $params = $request->only(['from_date', 'to_date', 'class_id']);

        DB::beginTransaction();
        try {
            $params = $request->all();
            $evaluations = Evaluation::getsqlEvaluation($params)->get();
            // Nếu đã có evaluation và is_type là NULL thì chuyển về mẫu view cũ để cập nhật nhận xét đánh giá, nếu ngược lại chuyển đến view mới (create)
            if ($evaluations->count() > 0 && $evaluations[0]->is_type == 'version_1') {
                return redirect()->route('evaluations.create', ['class_id' => $params['class_id'], 'from_date' => $params['from_date'], 'to_date' => $params['to_date']]);
            }
            $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
            if (isset($params['class_id']) && !in_array($params['class_id'], $permission)) {
                return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
            }
            $class = tbClass::getsqlClass(['id' => $params['class_id']])->first();
            // Lấy ra các giáo viên phụ nếu có
            $assistantTeacherArray = [];
            if ($class->assistant_teacher !== null && $class->assistant_teacher !== ' ') {
                $assistantTeacherArray = json_decode($class->assistant_teacher, true);
            }
            $teacher_assistant = Teacher::selectRaw('GROUP_CONCAT(" ", admins.name) AS teacher_assistant')->whereIn('id', $assistantTeacherArray)->first();
            $class->teacher_assistant = $teacher_assistant->teacher_assistant;
            $teacher = Teacher::where('id', $class->json_params->teacher ?? 0)->first();
            $class->teacher = $teacher;
            $this->responseData['this_class'] = $class;

            if ($evaluations->isEmpty()) {
                foreach ($class->students as $key => $item) {
                    $evaluation_params['student_id'] = $item->id;
                    $evaluation_params['teacher_id'] = $class->json_params->teacher;
                    $evaluation_params['class_id'] = $class->id;
                    $evaluation_params['status'] = Consts::STATUS['deactive'];
                    $evaluation_params['from_date'] = $params['from_date'];
                    $evaluation_params['to_date'] = $params['to_date'];
                    $evaluations = Evaluation::create($evaluation_params);
                }
            } else {
                $eva_student_ids = $evaluations->pluck('student_id')->toArray();
                $class_student_ids = $class->students->pluck('id')->toArray();
                $differentIds = array_values(array_diff($class_student_ids, $eva_student_ids));
                if (!empty($differentIds)) {
                    foreach ($differentIds as $key => $item) {
                        $evaluation_params['student_id'] = $item;
                        $evaluation_params['teacher_id'] = $class->json_params->teacher;
                        $evaluation_params['class_id'] = $class->id;
                        $evaluation_params['status'] = Consts::STATUS['deactive'];
                        $evaluation_params['from_date'] = $params['from_date'];
                        $evaluation_params['to_date'] = $params['to_date'];
                        $evaluations = Evaluation::create($evaluation_params);
                    }
                }
            }
            $rows = Evaluation::getsqlEvaluation($params)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['params'] = $params;

            DB::commit();
            return $this->responseView($this->viewPart . '.index');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }

        return redirect()->route('admin.home');
    }

    public function EvaluationClassIndex(Request $request)
    {
        $params = $request->all();
        $this->responseData['list_class'] = tbClass::orderBy('id', 'DESC')->get();
        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();
        $this->responseData['module_name'] = 'Lịch sử nhận xét đánh giá theo lớp';
        $this->responseData['params'] = $params;

        $list_admission = StaffAdmission::getsqlStaffAdmission(['admin_type' => 'admission'])->get();
        $this->responseData['list_admission'] =  $list_admission;

        if (isset($params['class_id'])) {
            $param_this_class['id'] = $params['class_id'];
            $this_class = tbClass::getsqlClass($param_this_class)->first();
            $this->responseData['this_class'] = $this_class;
            $this->responseData['teacher'] = Teacher::where('id', $this_class->json_params->teacher)->first();
            // Bổ sung thêm status là active để chỉ lấy ra nhận xét mà giáo viên đã lưu
            $list_evolution_class = Evaluation::where('class_id', $params['class_id'])->where('status', 'active')->whereNotNull('from_date')->whereNotNull('to_date')->groupBy('from_date')->groupBy('to_date')->get();
            $this->responseData['list_evolution_class'] = $list_evolution_class;
        }
        return $this->responseView($this->viewPart . '.evaluation_class_index');
    }

    public function EvaluationClassShow(Request $request)
    {

        $params = $request->all();
        $params['staff_permission'] = isset($params['admission_id']) ? DataPermissionService::getPermissionUsersAndSelfAll($params['admission_id']) : [];
        $this->responseData['cbts_name'] =  isset($params['admission_id']) ? StaffAdmission::find($params['admission_id'])->name : "";
        $class = tbClass::find($params['class_id'] ?? 0);
        $rows = Evaluation::getsqlEvaluation($params)->where('tb_evaluations.status', 'active')->get();
        $params['status'] = Consts::STATUS['active'];
        $this->responseData['rows'] =  $rows;
        $this->responseData['class'] =  $class;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['params'] = $params;
        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();

        if (isset($params['class_id'])) {
            $param_this_class['id'] = $params['class_id'];
            $this_class = tbClass::getsqlClass($param_this_class)->first();
            $this->responseData['this_class'] = $this_class;
            $this->responseData['teacher'] = Teacher::where('id', $this_class->json_params->teacher)->first();
        }

        return $this->responseView($this->viewPart . '.evaluation_class_show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
            'class_id' => 'required',
        ]);
        $params = $request->only(['from_date', 'to_date', 'class_id']);

        DB::beginTransaction();
        try {
            // Check evaluation đã tồn tại
            $evaluations = Evaluation::getsqlEvaluation($params)->get();
            // Nếu đã có evaluation và is_type là NULL thì chuyển về mẫu view cũ để cập nhật nhận xét đánh giá
            if ($evaluations->count() > 0 && $evaluations[0]->is_type == NULL) {
                return redirect()->route('evaluations.index', ['class_id' => $params['class_id'], 'from_date' => $params['from_date'], 'to_date' => $params['to_date']]);
            }
            // Check quyền thao tác dữ liệu với lớp
            $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
            if (isset($params['class_id']) && !in_array($params['class_id'], $permission)) {
                return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));
            }

            $class = tbClass::getsqlClass(['id' => $params['class_id']])->first();
            // Lấy ra các giáo viên phụ nếu có
            $assistantTeacherArray = [];
            if ($class->assistant_teacher !== null && $class->assistant_teacher !== ' ') {
                $assistantTeacherArray = json_decode($class->assistant_teacher, true);
            }
            $teacher_assistant = Teacher::selectRaw('GROUP_CONCAT(" ", admins.name) AS teacher_assistant')->whereIn('id', $assistantTeacherArray)->first();
            $class->teacher_assistant = $teacher_assistant->teacher_assistant;
            $teacher = Teacher::where('id', $class->json_params->teacher ?? 0)->first();
            $class->teacher = $teacher;
            $this->responseData['this_class'] = $class;

            // Create evaluation
            if ($evaluations->isEmpty()) {
                foreach ($class->students as $key => $item) {
                    $evaluation_params['student_id'] = $item->id;
                    $evaluation_params['teacher_id'] = $class->json_params->teacher;
                    $evaluation_params['class_id'] = $class->id;
                    $evaluation_params['status'] = Consts::STATUS['deactive'];
                    $evaluation_params['from_date'] = $params['from_date'];
                    $evaluation_params['to_date'] = $params['to_date'];
                    // Add type to new format evaluation
                    $evaluation_params['is_type'] = 'version_1';
                    $evaluations = Evaluation::create($evaluation_params);
                }
            } else {
                $eva_student_ids = $evaluations->pluck('student_id')->toArray();
                $class_student_ids = $class->students->pluck('id')->toArray();
                $differentIds = array_values(array_diff($class_student_ids, $eva_student_ids));
                if (!empty($differentIds)) {
                    foreach ($differentIds as $key => $item) {
                        $evaluation_params['student_id'] = $item;
                        $evaluation_params['teacher_id'] = $class->json_params->teacher;
                        $evaluation_params['class_id'] = $class->id;
                        $evaluation_params['status'] = Consts::STATUS['deactive'];
                        $evaluation_params['from_date'] = $params['from_date'];
                        $evaluation_params['to_date'] = $params['to_date'];
                        // Add type to new format evaluation
                        $evaluation_params['is_type'] = 'version_1';
                        $evaluations = Evaluation::create($evaluation_params);
                    }
                }
            }

            $rows = Evaluation::getsqlEvaluation($params)->get();
            $this->responseData['rows'] =  $rows;
            $this->responseData['params'] = $params;

            DB::commit();
            return $this->responseView($this->viewPart . '.create');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.home')->with('errorMessage', __($ex->getMessage()));
        }

        return redirect()->route('admin.home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        /**
         * Bổ sung code update thêm phần store
         * Tách tiếp phần import nếu có
         */
        DB::beginTransaction();
        try {
            $params = $request->only('evaluations');
            foreach ($params['evaluations'] as $key => $item) {
                $evaluation = Evaluation::find($item['id']);

                $ability = $item['json_params']['version_1']['ability'];
                $consciousness = $item['json_params']['version_1']['consciousness'];
                $knowledge = $item['json_params']['version_1']['knowledge'];
                $skill = $item['json_params']['version_1']['skill'];

                $json_params = $item['json_params'];

                $json_params['ability'] = $ability . ($ability != 'Chưa đánh giá' ? '/10' : '');
                $json_params['consciousness'] = 'Chuyên cần: ' . $consciousness['chuyen_can'] . ($consciousness['chuyen_can'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['consciousness'] .= 'Làm BT: ' . $consciousness['bai_tap'] . ($consciousness['bai_tap'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['consciousness'] .= 'Tương tác: ' . $consciousness['tuong_tac'] . '<br>';
                if ($consciousness['ghi_chu'] != '' || $consciousness['ghi_chu'] != NULL) {
                    $json_params['consciousness'] .= $consciousness['ghi_chu'];
                }

                $json_params['knowledge'] = 'Phát âm: ' . $knowledge['phat_am'] . ($knowledge['phat_am'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['knowledge'] .= 'Từ vựng: ' . $knowledge['tu_vung'] . ($knowledge['tu_vung'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['knowledge'] .= 'Ngữ pháp: ' . $knowledge['ngu_phap'] . ($knowledge['ngu_phap'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                if ($knowledge['ghi_chu'] != '' || $knowledge['ghi_chu'] != NULL) {
                    $json_params['knowledge'] .= $knowledge['ghi_chu'];
                }
                $json_params['skill'] = 'Nghe: ' . $skill['nghe'] . ($skill['nghe'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Nói: ' . $skill['noi'] . ($skill['noi'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Đọc: ' . $skill['doc'] . ($skill['doc'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Viết: ' . $skill['viet'] . ($skill['viet'] != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                if ($skill['ghi_chu'] != '' || $skill['ghi_chu'] != NULL) {
                    $json_params['skill'] .= $skill['ghi_chu'];
                }
                // Thêm phần status là active để xác nhận là các nhận xét này đã được giáo viên lưu lại (tách với việc lưu = ajax)
                $evaluation->update([
                    'json_params' => $json_params,
                    'status' => Consts::STATUS['active']
                ]);
            }
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    public function StoreSaveEvaluation(Request $request, Evaluation $evaluation)
    {
        $params = $request->all();
        foreach ($params['list'] as $key => $item) {
            $evaluation = $evaluation->find($item['id']);
            // Thêm phần status là active để xác nhận là các nhận xét này đã được giáo viên lưu lại (tách với việc lưu = ajax)
            $evaluation->update([
                'json_params' => $item['json_params'],
                'status' => Consts::STATUS['active']
            ]);
        }
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Evaluation $evaluation)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Evaluation $evaluation)
    {
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evaluation $evaluation)
    {
        return redirect()->back();
    }

    public function historyEvaluation(Request $request)
    {
        $request->validate(['class_id' => 'required']);
        $params = $request->only('class_id');
        $list_evolution_class = Evaluation::where('class_id', $params['class_id'])->groupBy('from_date')->groupBy('to_date')->get();
        $this->responseData['list_evolution_class'] = $list_evolution_class;
        $this->responseData['module_name'] = 'Lịch sử nhận xét đánh giá lớp';

        $paramStatus['status'] = Consts::STATUS['active'];
        $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();

        if (isset($params['class_id'])) {
            $param_this_class['id'] = $params['class_id'];
            $this->responseData['this_class'] = tbClass::getsqlClass($param_this_class)->first();
        }

        return $this->responseView($this->viewPart . '.history');
    }

    public function exportEvaluation(Request $request)
    {
        $params = $request->all();
        $class = tbClass::find($request->class_id);
        $class_name = isset($class) ? $class->name : "";
        $params['class_name'] = $class_name;
        return Excel::download(new EvaluationExport($params), 'DANH SÁCH NHẬN XÉT - ĐÁNH GIÁ LỚP-' . $class_name . '.xlsx');
    }

    public function ajaxUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $evaluation = Evaluation::find($request->id);
            // Check nếu là version_1 thì update theo dữ liệu kiểu mới
            if ($evaluation->is_type == 'version_1') {
                $json_params_get = json_decode($request->json_params);

                $ability = $json_params_get->ability;
                $consciousness = $json_params_get->consciousness;
                $knowledge = $json_params_get->knowledge;
                $skill = $json_params_get->skill;

                $json_params['version_1'] = $json_params_get;

                $json_params['ability'] = $ability . ($ability != 'Chưa đánh giá' ? '/10' : '');
                $json_params['consciousness'] = 'Chuyên cần: ' . $consciousness->chuyen_can . ($consciousness->chuyen_can != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['consciousness'] .= 'Làm BT: ' . $consciousness->bai_tap . ($consciousness->bai_tap != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['consciousness'] .= 'Tương tác: ' . $consciousness->tuong_tac . '<br>';
                if ($consciousness->ghi_chu != '' || $consciousness->ghi_chu != NULL) {
                    $json_params['consciousness'] .= $consciousness->ghi_chu;
                }

                $json_params['knowledge'] = 'Phát âm: ' . $knowledge->phat_am . ($knowledge->phat_am != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['knowledge'] .= 'Từ vựng: ' . $knowledge->tu_vung . ($knowledge->tu_vung != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['knowledge'] .= 'Ngữ pháp: ' . $knowledge->ngu_phap . ($knowledge->ngu_phap != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                if ($knowledge->ghi_chu != '' || $knowledge->ghi_chu != NULL) {
                    $json_params['knowledge'] .= $knowledge->ghi_chu;
                }
                $json_params['skill'] = 'Nghe: ' . $skill->nghe . ($skill->nghe != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Nói: ' . $skill->noi . ($skill->noi != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Đọc: ' . $skill->doc . ($skill->doc != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                $json_params['skill'] .= 'Viết: ' . $skill->viet . ($skill->viet != 'Chưa đánh giá' ? '/10</br>' : '</br>');
                if ($skill->ghi_chu != '' || $skill->ghi_chu != NULL) {
                    $json_params['skill'] .= $skill->ghi_chu;
                }

                $evaluation->update([
                    'json_params' => $json_params
                ]);
            } else {
                $json = [
                    "ability" =>  $request->ability,
                    "consciousness" =>  $request->consciousness,
                    "knowledge" =>  $request->knowledge,
                    "skill" =>  $request->skill,
                ];
                $evaluation->json_params = $json;
                $evaluation->save();
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
