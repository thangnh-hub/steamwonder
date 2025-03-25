<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Score;
use App\Models\tbClass;
use Illuminate\Support\Facades\DB;
use App\Consts;
use Exception;

class KpiService
{
    /**
     * Lớp kết thúc trong năm 
     */
    protected static $arr_level = [1, 2, 3, 4, 5, 6];
    protected static $score_min_goethe = 60;
    protected static $score_min_telc_moduleWrite = 134;
    protected static $score_min_telc_moduleSpeak = 45;
    protected static $score_min_telc_b1_1 = 108;
    public static function getClassInYear($teacher_id, $year)
    {
        $arr_level = self::$arr_level;
        // Truy vấn các lớp học có lịch cuối cùng trong năm truyền vào
        $query = tbClass::select('tb_classs.id')
            ->join('tb_schedules', 'tb_schedules.class_id', '=', 'tb_classs.id')
            ->where('tb_classs.status', 'hoan_thanh') // Lớp đã hoàn thành
            // ->whereIn('tb_classs.level_id', $arr_level) // lấy lớp thuộc trong 6 trình độ => cho lấy full các trình độ tính KPI tiến độ
            // ->where('tb_schedules.teacher_id', $teacher_id) // Lấy buổi theo giáo viên ==> không hợp lý
            ->where('tb_classs.json_params->teacher', $teacher_id) // Lớp của giáo viên
            ->whereYear('tb_schedules.date', $year) // Lấy các lịch học trong năm truyền vào
            ->groupBy('tb_schedules.class_id'); // Nhóm theo lớp học
        $param_class['id'] = $query->get()->pluck('id')->toArray();
        $class = count($param_class['id']) > 0 ? tbClass::getSqlClass($param_class)->get() : [];
        return $class;
    }

    /**
     * KPI tiến độ đào tạo
     */

    public static function calculatorKpiLearnProcess($delay_ratio)
    {
        try {
            $array = Consts::KPI_CONFIG['learn_process']['percent_delay_max'];
            $result = 0;
            foreach ($array as $key => $value) {
                if ($delay_ratio <= $key) {
                    $result  = max($result, $value);
                }
            }
            return $result * Consts::KPI_CONFIG['learn_process']['total_percent_kpi'];
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }


    /**
     * lớp có điểm trong năm
     */
    public static function getClassHasScoreInYear($teacher_id, $year)
    {
        $arr_level = self::$arr_level;
        $query = tbClass::select('tb_classs.*')
            ->where('tb_classs.status', 'hoan_thanh') // Lớp đã hoàn thành
            ->where('tb_classs.json_params->teacher', $teacher_id) // Lớp của giáo viên
            ->whereYear('tb_classs.day_exam', $year) // Lớp có điểm
            ->whereIn('tb_classs.level_id', $arr_level) // lấy lớp thuộc trong 6 trình độ
            ->withCount('students')
            ->orderBy('tb_classs.level_id'); // lấy lớp thuộc trong 6 trình độ
        return $query->get();
    }

    /**
     * KPI kết quả đào tạo : điều hướng đến hàm kiểm tra trình độ lớp học để tính kpi tương ứng
     */
    public static function redirectKpiLearnScore(tbClass $class)
    {
        try {
            $level = $class->level_id;
            switch (true) {
                case $level == "1" || $level == "2" || $level == "3" || $level == "4":
                    return self::calculatorKpiLearnScoreA($class);
                case $level == "5" || $level == "6":
                    return self::calculatorKpiLearnScoreB($class);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }

    /**
     * trả về thông tin KPI dưới dạng mảng : KPI nhận đc, số học sinh, số % học sinh
     */
    public static function calculatorKpiLearnScoreA(tbClass $class)
    {
        try {
            $kpi = [];
            $condition = Consts::KPI_CONFIG['learn_score'][$class->level->name][$class->type_normal_special];
            $total_student = $class->students_count;

            $studentAchieved = 0; // Số học viên đạt điều kiện
            $percent_receive = 0; // Phần trăm nhận đc

            $param_score['class_id'] = $class->id;
            $list_score = Score::getSqlScore($param_score)->get();

            if ($condition && $total_student > 0) {
                foreach ($condition as $con) {
                    $studentAchieved  = $list_score->filter(function ($it) use ($con) {
                        return isset($it->json_params->score_average) && $it->json_params->score_average >= $con['score_min'];
                    })->count(); // Số học viên đạt điểm tối thiểu

                    $percentAchieved = $studentAchieved / $total_student; // Tính % học viên đạt
                    // Nếu % học viên đạt >= điều kiện tối thiểu thì trả về % KPI
                    if ($percentAchieved >= $con['percent_min']) {
                        $percent_receive  = max($percent_receive, $con['percent_receive']);
                    }
                    $kpi[$con['score_min']]['score_min'] = $con['score_min'];
                    $kpi[$con['score_min']]['studentAchieved'] = $studentAchieved;
                    $kpi[$con['score_min']]['percentAchieved'] = $percentAchieved;
                    $kpi['percent_receive'] = $percent_receive;
                }
            }
            return $kpi;
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
    /**
     * KPI kết quả đào tạo A1 trả về kpi của trình độ B1
     */
    public static function calculatorKpiLearnScoreB(tbClass $class)
    {
        $score_min_goethe = self::$score_min_goethe;
        $score_min_telc_moduleWrite = self::$score_min_telc_moduleWrite;
        $score_min_telc_moduleSpeak = self::$score_min_telc_moduleSpeak;
        $score_min_telc_b1_1 = self::$score_min_telc_b1_1;

        try {
            $kpi = [];
            $condition = Consts::KPI_CONFIG['learn_score'][$class->level->name][$class->type_normal_special];
            $total_student = $class->students_count;

            $percent_receive = 0; // Phần trăm nhận đc

            $param_score['class_id'] = $class->id;
            $list_score = Score::getSqlScore($param_score)->get();
            if ($condition && $total_student > 0) {
                foreach ($condition as $con) {
                    if ($class->syllabus->score_type == "goethe") {
                        $studentAchievedListen  = $list_score->filter(function ($it) use ($score_min_goethe) {
                            return isset($it->score_listen) && $it->score_listen >= $score_min_goethe;
                        })->count(); // Số học viên đạt điểm tối thiểu nghe >= 60

                        $studentAchievedSpeak  = $list_score->filter(function ($it) use ($score_min_goethe) {
                            return isset($it->score_speak) && $it->score_speak >= $score_min_goethe;
                        })->count(); // Số học viên đạt điểm tối thiểu nói >= 60

                        $studentAchievedRead  = $list_score->filter(function ($it) use ($score_min_goethe) {
                            return isset($it->score_read) && $it->score_read >= $score_min_goethe;
                        })->count(); // Số học viên đạt điểm tối thiểu đọc >= 60

                        $studentAchievedWrite  = $list_score->filter(function ($it) use ($score_min_goethe) {
                            return isset($it->score_write) && $it->score_write >= $score_min_goethe;
                        })->count(); // Số học viên đạt điểm tối thiểu viết >= 60
                    }
                    // telc
                    else {
                        // b1.1
                        if ($class->level_id == 5) {
                            $studentAchievedListen = $studentAchievedRead = $list_score->filter(function ($it) use ($score_min_telc_b1_1) {
                                return $it->score_listen + $it->score_read >= $score_min_telc_b1_1;
                            })->count(); // Số học viên đạt điểm module viết >= 108
                            $studentAchievedSpeak = $studentAchievedWrite = 0;
                        }
                        // b1.2
                        else{
                            $studentAchievedListen = $studentAchievedRead = $studentAchievedWrite = $list_score->filter(function ($it) use ($score_min_telc_moduleWrite) {
                                return $it->score_listen + $it->score_read + $it->score_write >= $score_min_telc_moduleWrite;
                            })->count(); // Số học viên đạt điểm module viết >= 134
    
                            $studentAchievedSpeak  = $list_score->filter(function ($it) use ($score_min_telc_moduleSpeak) {
                                return isset($it->score_speak) && $it->score_speak >= $score_min_telc_moduleSpeak;
                            })->count(); // Số học viên đạt điểm tối thiểu nói >= 45
                        }
                        
                    }
                    // Xử lý tính toán tỉ lệ
                    $percentAchievedListen = $studentAchievedListen / $total_student; // Tính % học viên đạt nghe
                    $percentAchievedSpeak = $studentAchievedSpeak / $total_student; // Tính % học viên đạt nói
                    $percentAchievedRead = $studentAchievedRead / $total_student; // Tính % học viên đạt đọc
                    $percentAchievedWrite = $studentAchievedWrite / $total_student; // Tính % học viên đạt viết

                    $totalAchieved = ($studentAchievedListen + $studentAchievedSpeak + $studentAchievedRead + $studentAchievedWrite);
                    if($class->syllabus->score_type == "telc" && $class->level_id == 5 )$totalskill = $total_student * 2;//telc b1.1
                    else $totalskill = $total_student * 4;
                    
                    $percentAchieved = $totalAchieved / $totalskill;

                    $kpi['totalAchieved'] = $totalAchieved; // Tổng kỹ năng đỗ
                    $kpi['totalskill'] = $totalskill; // Tổng kỹ năng

                    // Nếu % học viên đạt >= điều kiện tối thiểu thì trả về % KPI
                    if ($percentAchieved >= $con['percent_min']) {
                        $percent_receive  = max($percent_receive, $con['percent_receive']);
                    }

                    $kpi['listen']['studentAchieved'] = $studentAchievedListen;
                    $kpi['listen']['percentAchieved'] = $percentAchievedListen;

                    $kpi['speak']['studentAchieved'] = $studentAchievedSpeak;
                    $kpi['speak']['percentAchieved'] = $percentAchievedSpeak;

                    $kpi['read']['studentAchieved'] = $studentAchievedRead;
                    $kpi['read']['percentAchieved'] = $percentAchievedRead;

                    $kpi['write']['studentAchieved'] = $studentAchievedWrite;
                    $kpi['write']['percentAchieved'] = $percentAchievedWrite;

                    $kpi['percent_receive'] = $percent_receive; // KPI cuối cùng nhận đc
                }
            }
            return $kpi;
        } catch (Exception $ex) {
            DB::rollBack();
            abort(422, __($ex->getMessage()));
        }
    }
}
