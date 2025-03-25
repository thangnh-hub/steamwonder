<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Score;
use App\Models\Syllabus;
use App\Models\RankAcademic;
use App\Models\Student;

class ScoreService
{
  // Khai báo mảng lưu công thức lưu điểm và đánh giá xếp loại cho các lớp thi từ tháng 3/2025
  const RATE_SCORE_TABLE = [
    '1' => [
      [
        'score_min' => 69.5,
        'score_max' => 100,
        'rank' => 'pass'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'level_up'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'fail'
      ],
    ],
    '2' => [
      [
        'score_min' => 69.5,
        'score_max' => 100,
        'rank' => 'pass'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'level_up'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'fail'
      ],
    ],
    '3' => [
      [
        'score_min' => 69.5,
        'score_max' => 100,
        'rank' => 'pass'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'level_up'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'fail'
      ],
    ],
    '4' => [
      [
        'score_min' => 69.5,
        'score_max' => 100,
        'rank' => 'pass'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'level_up'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'fail'
      ],
    ],
    '5' => [
      [
        'score_min' => 89.5,
        'score_max' => 100,
        'rank' => 'Rất tốt'
      ],
      [
        'score_min' => 79.5,
        'score_max' => 89.49,
        'rank' => 'Tốt'
      ],
      [
        'score_min' => 69.5,
        'score_max' => 79.49,
        'rank' => 'Khá'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'Vừa đủ đạt'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'Không đạt'
      ],
    ],
    '6' => [
      [
        'score_min' => 89.5,
        'score_max' => 100,
        'rank' => 'Rất tốt'
      ],
      [
        'score_min' => 79.5,
        'score_max' => 89.49,
        'rank' => 'Tốt'
      ],
      [
        'score_min' => 69.5,
        'score_max' => 79.49,
        'rank' => 'Khá'
      ],
      [
        'score_min' => 59.5,
        'score_max' => 69.49,
        'rank' => 'Vừa đủ đạt'
      ],
      [
        'score_min' => 0,
        'score_max' => 59.49,
        'rank' => 'Không đạt'
      ],
    ],

  ];

  public static function getData(array $conditions)
  {
    $query = Score::query();
    foreach ($conditions as $key => $value) {
      if (is_array($value)) {
        $query->where($key, $value[0], $value[1]);
      } else {
        $query->where($key, $value);
      }
    }
    return $query;
  }

  public static function updateJson_params($params = [], $arr_json = [])
  {
    $query = self::getData($params)->first();
    $query->update($arr_json);
    return $query;
  }

  /**
   * @param tbClass $class
   * @param array $list_scores
   * @param string $day_exam (YYYY-mm-dd: 2025-02-28) 
   */
  public static function saveScore($class, $list_scores, $day_exam)
  {
    $syllabus = Syllabus::find($class->syllabus_id);
    $useNewFormula = strtotime($day_exam) > strtotime('2025-02-17');

    // Lấy tất cả ID của score cần xử lý để giảm số lượng truy vấn CSDL
    $score_ids = array_column($list_scores, 'id');
    $scoresMap = Score::whereIn('id', $score_ids)->get()->keyBy('id');

    foreach ($list_scores as $key => $item) {
      $score_id = (int) $item['id'];
      // Check dữ liệu trước khi thực hiện tính toán và xếp loại
      if (!self::hasValidScore($item) || !isset($scoresMap[$score_id])) {
        continue;
      }

      $score = $scoresMap[$score_id];
      $status = $score_average = "";
      // telc -> giữ nguyên công thức vì chi áp dụng cho B1
      if ($syllabus->score_type == 'telc') {
        // B1.1
        if ($class->level_id == 5) {
          list($status, $score_average) = self::getTelcB11Status($item, $useNewFormula);
        }
        // B1.2
        elseif ($class->level_id == 6) {
          list($status, $score_average) = self::getTelcB12Status($item, $useNewFormula);
        }
      }
      // goethe -> thay đổi và áp dụng lại thang điểm cho các level từ A1->A2->B1
      else {
        $score_average = self::calculateGoetheScore($item);
        // Lấy thang điểm theo cấu hình 
        $status = self::findRank($item['level'], $score_average, $useNewFormula);
      }

      // Update level student nếu level mới lớn hơn hiện tại và <= 6
      $student = Student::find($score->user_id);
      if ($student && $student->level_id < $item['level'] && $item['level'] <= 6) {
        $student->update([
          'level_id' => $item['level'],
          'rank_score' => $status,
        ]);
      }

      // Dữ liệu cập nhật
      $score->update([
        'score_listen' => $item['score_listen'],
        'score_speak' => $item['score_speak'],
        'score_read' => $item['score_read'],
        'score_write' => $item['score_write'],
        'status' => $status,
        'json_params->score_average' => $score_average,
        'json_params->exam_1st->score_listen' => $item['score_listen'],
        'json_params->exam_1st->score_speak' => $item['score_speak'],
        'json_params->exam_1st->score_read' => $item['score_read'],
        'json_params->exam_1st->score_write' => $item['score_write'],
        'json_params->note' => $item['json_params']['note'] ?? '',
      ]);
    }
  }

  /**
   * @param tbClass $class
   * @param array $list_scores
   * @param string $day_exam (YYYY-mm-dd: 2025-02-28) 
   */
  public static function saveScoreAgain($class, $list_scores, $day_exam)
  {
    $syllabus = Syllabus::find($class->syllabus_id);
    $useNewFormula = strtotime($day_exam) > strtotime('2025-02-17');

    // Lấy tất cả ID của score cần xử lý để giảm số lượng truy vấn CSDL
    $score_ids = array_column($list_scores, 'id');
    $scoresMap = Score::whereIn('id', $score_ids)->get()->keyBy('id');
    // Chỉ geothe mới có thi lại và nhập điểm lần 2
    if ($syllabus->score_type != 'telc') {
      foreach ($list_scores as $key => $item) {
        $score_id = (int) $item['id'];
        // Check dữ liệu trước khi thực hiện tính toán và xếp loại
        if (!self::hasValidScore($item) || !isset($scoresMap[$score_id])) {
          continue;
        }

        $score = $scoresMap[$score_id];
        $status = $score_average = "";

        // Lấy giá trị điểm truyền lên để lưu vào điểm thi lần 2
        $score_listen_2nd = $item['score_listen'];
        $score_speak_2nd = $item['score_speak'];
        $score_read_2nd = $item['score_read'];
        $score_write_2nd = $item['score_write'];

        // Lấy điểm thi lần 2 nếu có, nếu không thì lấy điểm thi lần 1
        $score_types = ['score_listen', 'score_speak', 'score_read', 'score_write'];
        foreach ($score_types as $score_type) {
          $item[$score_type] = !empty($item[$score_type]) ? $item[$score_type] : $item['json_params']['exam_1st'][$score_type];
        }

        $score_average = self::calculateGoetheScore($item);
        // Lấy thang điểm theo cấu hình 
        $status = self::findRank($item['level'], $score_average, $useNewFormula);

        // Update level student nếu level mới lớn hơn hiện tại và <= 6
        $student = Student::find($score->user_id);
        if ($student && $student->level_id < $item['level'] && $item['level'] <= 6) {
          $student->update([
            'level_id' => $item['level'],
            'rank_score' => $status,
          ]);
        }

        $score->update([
          'score_listen' => $item['score_listen'],
          'score_speak' => $item['score_speak'],
          'score_read' => $item['score_read'],
          'score_write' => $item['score_write'],
          'status' => $status,
          'json_params->score_average' => $score_average,
          'json_params->exam_2nd->score_listen' => $score_listen_2nd,
          'json_params->exam_2nd->score_speak' => $score_speak_2nd,
          'json_params->exam_2nd->score_read' => $score_read_2nd,
          'json_params->exam_2nd->score_write' => $score_write_2nd,
          'json_params->check_retest' => "retest",
          'json_params->status' => $status,
        ]);
      }
    }
  }

  // Hàm kiểm tra nếu có ít nhất một giá trị > 0 trong các trường score_, đồng thời ép kiểu integer
  private static function hasValidScore($item)
  {
    $scoreKeys = ["score_listen", "score_speak", "score_read", "score_write"];
    foreach ($scoreKeys as $key) {
      $value = isset($item[$key]) ? (int) $item[$key] : 0; // Ép kiểu về integer
      if ($value > 0) {
        return true; // Nếu có ít nhất một giá trị > 0, giữ lại phần tử
      }
    }
    return false;
  }

  private static function getTelcB11Status($item, $useNewFormula)
  {
    $group_score = $item['score_listen'] + $item['score_read'];

    // Công thức mới
    if ($useNewFormula) {
      $score_average = 'Nghe, Đọc + Ngữ pháp: ' . ($item['score_listen'] + $item['score_read']);
      if ($group_score < 108) return ['Không đạt', $score_average];
      if ($group_score < 126) return ['Vừa đủ đạt', $score_average];
      if ($group_score < 144) return ['Khá', $score_average];
      if ($group_score < 162) return ['Tốt', $score_average];

      return ['Rất tốt', $score_average];
    }

    // Công thức cũ
    $score_average = "Modul Nghe và Đọc: " . ($item['score_listen'] + $item['score_read']);
    if ($group_score >= 108) return ['pass_listen_read', $score_average];
    return ['', $score_average];
  }

  private static function getTelcB12Status($item, $useNewFormula)
  {
    $modul_viet = $item['score_listen'] + $item['score_read'] + $item['score_write'];
    $module_noi = $item['score_speak'];
    $total = $modul_viet + $module_noi;
    $score_average = "Modul Viết: $modul_viet, Modul Nói: $module_noi";

    // Công thức mới
    if ($useNewFormula) {
      if ($modul_viet < 135 || $module_noi < 45 || $total < 180) return ['Không đạt', $score_average];
      if ($total < 210) return ['Vừa đủ đạt', $score_average];
      if ($total < 240) return ['Khá', $score_average];
      if ($total < 270) return ['Tốt', $score_average];
      return ['Rất tốt', $score_average];
    }

    // Công thức cũ
    if ($modul_viet > 135 && $module_noi > 45) return ['pass_full', $score_average];
    if ($modul_viet > 135) return ['pass_write', $score_average];
    if ($module_noi > 45) return ['pass_speak', $score_average];

    return ['fail', $score_average];
  }

  private static function calculateGoetheScore($item)
  {
    return (float) (
      ($item['score_listen'] * ($item['score_listen_weight'] / 100)) +
      ($item['score_speak'] * ($item['score_speak_weight'] / 100)) +
      ($item['score_read'] * ($item['score_read_weight'] / 100)) +
      ($item['score_write'] * ($item['score_write_weight'] / 100))
    );
  }

  private static function findRank($level, $score_average, $useNewFormula)
  {
    // Lấy thang điểm theo cấu hình mới nếu là trình độ từ A1->A2->B1
    if ($useNewFormula) {
      $ranks = self::RATE_SCORE_TABLE[$level] ?? [];
      foreach ($ranks as $rank) {
        if ($score_average >= $rank['score_min'] && $score_average <= $rank['score_max']) {
          return $rank['rank'];
        }
      }
      return '';
    }

    // Công thức cũ
    $rank = RankAcademic::where('level_id', $level)
      ->where('from_points', '<=', $score_average)
      ->where('to_points', '>=', $score_average)
      ->first();

    return $rank ? $rank->ranks : "";
  }
}
