<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Language;
use App\Models\tbClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Syllabus;
use App\Models\RankAcademic;
use App\Models\Schedule;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Http\Services\DataPermissionService;
use App\Http\Services\ScoreService;
use App\Http\Services\BookDistributionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use stdClass;
use App\Exports\ScoreExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Services\NotifyService;
use Illuminate\Support\Facades\Log;

class ScoreController extends Controller
{
  public function __construct()
  {
    $this->routeDefault  = 'scores';
    $this->viewPart = 'admin.pages.scores';
    $this->responseData['module_name'] = 'Nhập điểm thi theo lớp';
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $params = $request->all();

    // Kiểm tra nếu không có class_id
    if (!isset($params['class_id'])) {
      return redirect()->back()->with('errorMessage', __('Không tìm thấy lớp học.'));
    }

    // Lấy thông tin lớp học
    $class = tbClass::getsqlClass(['id' => $params['class_id']])->first();
    // Nếu lớp không tồn tại, trả về lỗi
    if (!$class) {
      return redirect()->back()->with('errorMessage', __('Lớp không tồn tại.'));
    }

    $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
    if (!in_array($params['class_id'], $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));

    $score = Score::getSqlScore($params)->get();

    // Nếu chưa có điểm nào, tạo mới cho toàn bộ học viên
    if ($score->isEmpty() && isset($class->students)) {
      $newScores = [];
      foreach ($class->students as $student) {
        $newScores[] = [
          'user_id'  => $student->id,
          'class_id' => $class->id,
          'created_at' => now(),
        ];
      }
      Score::insert($newScores);
    } else {
      // Lấy danh sách ID học viên trong lớp và ID học viên có bản ghi điểm đã tạo
      $user_ids = $score->pluck('user_id')->toArray();
      $student_ids = isset($class->students) ? $class->students->pluck('id')->toArray() : [];

      // Tìm học viên cần thêm điểm
      $missingUserIds = array_values(array_diff($student_ids, $user_ids));
      if (!empty($missingUserIds)) {
        $newScores = [];
        foreach ($missingUserIds as $userId) {
          $newScores[] = [
            'user_id'  => $userId,
            'class_id' => $class->id,
            'created_at' => now(),
          ];
        }
        Score::insert($newScores);
      }

      // Xóa học viên không còn trong lớp và chưa có điểm
      $idsToDelete = array_values(array_diff($user_ids, $student_ids));
      if (!empty($idsToDelete)) {
        Score::whereIn('user_id', $idsToDelete)
          ->whereNull('score_listen')
          ->whereNull('score_speak')
          ->whereNull('score_read')
          ->whereNull('score_write')
          ->delete();
      }
    }
    // Get list post with filter params
    $rows = Score::getsqlScore($params)->get();
    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;

    $paramStatus['status'] = Consts::STATUS['active'];
    $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();

    $this->responseData['this_class'] = $class;

    return $this->responseView($this->viewPart . '.index');
  }

  public function scoreSecondIndex(Request $request)
  {
    $params = $request->all();
    // Kiểm tra nếu không có class_id
    if (!isset($params['class_id'])) {
      return redirect()->back()->with('errorMessage', __('Không tìm thấy lớp học.'));
    }
    // Lấy thông tin lớp học
    $class = tbClass::getsqlClass(['id' => $params['class_id']])->first();
    // Nếu lớp không tồn tại, trả về lỗi
    if (!$class) {
      return redirect()->back()->with('errorMessage', __('Lớp không tồn tại.'));
    }
    // Kiểm tra quyền truy cập lớp học
    $permission = DataPermissionService::getPermissionClasses(Auth::guard('admin')->user()->id);
    if (!in_array($params['class_id'], $permission)) return redirect()->back()->with('errorMessage', __('Bạn không có quyền truy cập lớp này'));

    $score = Score::getSqlScore($params)->get();
    // Nếu chưa có bản ghi điểm, tạo mới
    if ($score->isEmpty() && isset($class->students)) {
      $newScores = [];
      foreach ($class->students as $student) {
        $newScores[] = [
          'user_id'  => $student->id,
          'class_id' => $class->id,
          'created_at' => now(),
        ];
      }
      Score::insert($newScores);
    } else {
      // Lấy danh sách ID học viên trong lớp và ID học viên có bản ghi điểm đã tạo
      $user_ids = $score->pluck('user_id')->toArray();
      $student_ids = isset($class->students) ? $class->students->pluck('id')->toArray() : [];

      // Tìm học viên cần thêm điểm
      $missingUserIds = array_values(array_diff($student_ids, $user_ids));
      if (!empty($missingUserIds)) {
        $newScores = [];
        foreach ($missingUserIds as $userId) {
          $newScores[] = [
            'user_id'  => $userId,
            'class_id' => $class->id,
            'created_at' => now(),
          ];
        }
        Score::insert($newScores);
      }

      // Xóa học viên không còn trong lớp và chưa có điểm
      $idsToDelete = array_values(array_diff($user_ids, $student_ids));
      if (!empty($idsToDelete)) {
        Score::whereIn('user_id', $idsToDelete)
          ->whereNull('score_listen')
          ->whereNull('score_speak')
          ->whereNull('score_read')
          ->whereNull('score_write')
          ->delete();
      }
    }
    $params['fail'] = true;
    // Get list post with filter params
    $rows = Score::getsqlScore($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

    $this->responseData['rows'] =  $rows;
    $this->responseData['params'] = $params;

    $paramStatus['status'] = Consts::STATUS['active'];
    $this->responseData['list_teacher'] = Teacher::getSqlTeacher($paramStatus)->get();

    $this->responseData['this_class'] = $class;

    return $this->responseView($this->viewPart . '.score_2nd');
  }

  public function SaveScore(Request $request)
  {

    $request->validate([
      'day_exam' => 'required',
      'class_id' => 'required',
      'list' => 'required',
    ]);
    $params = $request->all();

    $class = tbClass::find($params['class_id']);

    // Kiểm tra nếu lớp không tồn tại
    if (!$class) {
      return redirect()->back()->with('errorMessage', __('Lớp không tồn tại!'));
    }

    // Kiểm tra trạng thái điểm của lớp
    if ($class->is_score == 'dachamdiemlan2') {
      return redirect()->back()->with('errorMessage', __('Lớp đã chấm điểm lần 2 không thể chỉnh sửa!'));
    }

    DB::beginTransaction();
    try {
      ScoreService::saveScore($class, $params['list'], $params['day_exam']);

      // Update class: ngay thi va da cham diem lan 1
      $class->update([
        'is_score' => 'dachamdiemlan1',
        'day_exam' => $params['day_exam'],
      ]);

      $user_name = Auth::guard('admin')->user()->name;
      $notify_title = "[Nhập điểm lần 1] Lớp {$class->name} vừa cập nhật điểm lần 1. ({$user_name})";
      $link = route('scores.index', ['class_id' => $class->id]);
      NotifyService::add_notify($notify_title, Consts::TYPE_NOTIFY['point1'], $link, $class->id, '');
      DB::commit();
      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    } catch (\Throwable $ex) {
      DB::rollBack();

      // Log lỗi để debug
      Log::error('Lỗi khi nhập điểm lần 1: ' . $ex->getMessage(), ['trace' => $ex->getTraceAsString()]);

      return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra, vui lòng thử lại!'));
    }
  }

  public function SaveScore_2nd(Request $request)
  {
    $params = $request->all();
    $request->validate([
      'day_exam2' => 'required',
      'class_id' => 'required',
      'list' => 'required',
    ]);

    $class = tbClass::find($params['class_id']);
    // Kiểm tra nếu lớp không tồn tại
    if (!$class) {
      return redirect()->back()->with('errorMessage', __('Lớp không tồn tại!'));
    }
    // Kiểm tra trạng thái điểm của lớp
    if (!in_array($class->is_score, ['dachamdiemlan1', 'dachamdiemlan2'])) {
      return redirect()->back()->with('errorMessage', __('Lớp chưa chấm điểm lần 1 không thể chấm điểm lần 2!'));
    }
    $syllabus = Syllabus::find($class->syllabus_id);
    // Kiểm tra nếu syllabus không tồn tại hoặc không phải loại Goethe
    if (!$syllabus || $syllabus->score_type !== 'goethe') {
      return redirect()->back()->with('errorMessage', __('Chỉ loại Goethe mới có thi lại lần 2!'));
    }

    DB::beginTransaction();
    try {
      ScoreService::saveScoreAgain($class, $params['list'], $params['day_exam2']);

      $class->update([
        'is_score' => 'dachamdiemlan2',
        'day_exam2' => $params['day_exam2']
      ]);

      $user_name = Auth::guard('admin')->user()->name;
      $notify_title = "[Nhập điểm lần 2] Lớp {$class->name} vừa cập nhật điểm lần 2. ({$user_name})";
      $link = route('scores.index', ['class_id' => $class->id]);
      NotifyService::add_notify($notify_title, Consts::TYPE_NOTIFY['point2'], $link, $class->id, '');

      DB::commit();
      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    } catch (\Throwable $ex) {
      DB::rollBack();

      // Log lỗi để dễ debug
      Log::error('Lỗi khi nhập điểm lần 2: ' . $ex->getMessage(), ['trace' => $ex->getTraceAsString()]);

      return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra, vui lòng thử lại!'));
    }
  }

  public function export(Request $request)
  {
    $params = $request->all();
    return Excel::download(new ScoreExport($params), 'Score.xlsx');
  }

  public function ajaxUpdate(Request $request)
  {
    DB::beginTransaction();
    try {
      $class = tbClass::find($request->class_id);
      if (($class->is_score) != "dachamdiem") {
        $score = Score::find($request->id);
        $score->update([
          'score_listen' => $request->listen,
          'score_speak' => $request->speak,
          'score_read' => $request->read,
          'score_write' => $request->write,
          'json_params->score_average' => $score->json_params->score_average ?? "",
          'json_params->exam_1st->score_listen' => $request->listen,
          'json_params->exam_1st->score_speak' => $request->speak,
          'json_params->exam_1st->score_read' => $request->read,
          'json_params->exam_1st->score_write' => $request->write,
          'json_params->note' => $request->note,
        ]);
        DB::commit();
      }
    } catch (Exception $ex) {
      DB::rollBack();
      abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
    }
  }

  public function updateJsonScore(Request $request)
  {
    DB::beginTransaction();
    try {
      $mess = "Lưu thông tin thành công!";
      $data = "success";
      $params = $request->all();
      $params_search['user_id'] = $params['user_id'] ?? '';
      $params_search['class_id'] = $params['class_id'] ?? '';
      $json = [];
      if (isset($params['json']) && count($params['json']) > 0) {
        foreach ($params['json'] as $key => $val) {
          $json['json_params->' . $key] = $val;
        }
      }
      ScoreService::updateJson_params($params_search, $json);
      DB::commit();
      return $this->sendResponse($data, $mess);
    } catch (Exception $ex) {
      DB::rollBack();
      abort(500, 'Có lỗi xảy ra trong quá trình thực hiện. Vui lòng thử lại sau.');
    }
  }
}
