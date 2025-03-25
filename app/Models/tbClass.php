<?php

namespace App\Models;

use App\Consts;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class tbClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_classs';
    protected $with = array('level', 'syllabus', 'course');

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getSqlClass($params = [])
    {
        $query = tbClass::select('tb_classs.*', 'tb_syllabuss.lesson')
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->leftJoin('tb_syllabuss', 'tb_classs.syllabus_id', '=', 'tb_syllabuss.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_classs.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_classs.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('tb_classs.status', $params['status']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                $query->whereYear('tb_schedules.date', $params['year']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_classs.id', $params['permission']);
            })
            ->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_classs.id', $params['other_list']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type']);
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_classs.level_id', $params['level_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['room_id']), function ($query) use ($params) {
                return $query->where('tb_classs.room_id', $params['room_id']);
            })
            ->when(!empty($params['list_level']), function ($query) use ($params) {
                return $query->whereIn('tb_classs.level_id', $params['list_level']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_classs.id', $params['class_id']);
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_classs.course_id', $params['course_id']);
            })
            ->when(!empty($params['before_month']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', '<=', $params['before_month']);
            })
            ->when(!empty($params['from_day_exam']), function ($query) use ($params) {
                return $query->whereDate('tb_classs.day_exam', '>=', $params['from_day_exam']);
            })
            ->when(!empty($params['to_day_exam']), function ($query) use ($params) {
                return $query->whereDate('tb_classs.day_exam', '<', $params['to_day_exam']);
            })
            ->when(!empty($params['from_date']), function ($query) use ($params) {
                return $query->whereDate('tb_schedules.date', '>=', $params['from_date']);
            })
            ->when(!empty($params['to_date']), function ($query) use ($params) {
                return $query->whereDate('tb_schedules.date', '<=', $params['to_date']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_classs.syllabus_id', $params['syllabus_id']);
            })

            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                $teacher_id = $params['teacher_id'];
                return $query->where(function ($where) use ($teacher_id) {
                    return $where->where('tb_classs.assistant_teacher', 'like', '%' . $teacher_id . '%')
                        ->orWhere('tb_classs.json_params->teacher', $teacher_id);
                });
            })
            ->when(!empty($params['end_date']), function ($query) use ($params) {
                return $query
                    ->addSelect([
                        'last_schedule_date' => Schedule::select('date')
                            ->whereColumn('tb_schedules.class_id', 'tb_classs.id')
                            ->orderByDesc('date')
                            ->limit(1)
                    ])
                    ->whereDate('tb_schedules.date', '=', $params['end_date'])
                    ->having('last_schedule_date', '=', $params['end_date']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_classs.id', $params['id']);
                } else {
                    return $query->where('tb_classs.id', $params['id']);
                }
            });
        if (isset($params['school_day'])) {
            $query->whereDate('tb_schedules.date', '=', $params['school_day']);
        }
        $query->groupBy('tb_classs.id');
        $query->selectRaw('COUNT(tb_schedules.id) AS total_schedules');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.status = "dadiemdanh" THEN 1 END) AS total_attendance');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type = "gv" THEN 1 END) AS total_schedules_gv');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type = "gvnn" THEN 1 END) AS total_schedules_gvnn');
        $query->selectRaw('MIN(tb_schedules.date) AS day_start, MAX(tb_schedules.date) AS day_end');
        $query->selectRaw('MAX(CASE WHEN tb_schedules.is_add_more IS NULL THEN tb_schedules.date END) AS day_end_expected');

        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type_schedule = "fulltime" THEN 1 END) AS total_fulltime');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type_schedule = "parttime" THEN 1 END) AS total_parttime');

        return $query;
    }
    public static function getSqlClassEnding($params = [])
    {

        $query = tbClass::select('tb_classs.*')
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_classs.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_classs.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('tb_classs.status', $params['status']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_classs.id', $params['permission']);
            })

            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_classs.level_id', $params['level_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_classs.syllabus_id', $params['syllabus_id']);
            })

            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_classs.course_id', $params['course_id']);
            })

            ->when(!empty($params['ketoan_xacnhan']), function ($query) use ($params) {
                return $query->where('tb_classs.json_params->ketoan_xacnhan', $params['ketoan_xacnhan']);
            })
            ->when(!empty($params['status_book_distribution']), function ($query) use ($params) {
                if ($params['status_book_distribution'] == 'null') {
                    return $query->whereNull('status_book_distribution');
                } else {
                    if (is_array($params['status_book_distribution'])) {
                        return $query->whereIn('tb_classs.status_book_distribution', $params['status_book_distribution']);
                    } else {

                        return $query->where('tb_classs.status_book_distribution', $params['status_book_distribution']);
                    }
                }
            })
            ->when(empty($params['ketoan_xacnhan']), function ($query) use ($params) {
                return  $query->whereNull('tb_classs.json_params->ketoan_xacnhan');
            })

            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                $teacher_id = $params['teacher_id'];
                return $query->where(function ($where) use ($teacher_id) {
                    return $where->where('tb_classs.assistant_teacher', 'like', '%' . $teacher_id . '%')
                        ->orWhere('tb_classs.json_params->teacher', $teacher_id);
                });
            });

        $query->groupBy('tb_classs.id');
        $query->selectRaw('COUNT(tb_schedules.id) AS total_schedules');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.status = "dadiemdanh" THEN 1 END) AS total_attendance');
        $query->selectRaw('MIN(tb_schedules.date) AS day_start, MAX(tb_schedules.date) AS day_end'); //ngày kết thúc thực tế
        $query->selectRaw('MAX(CASE WHEN tb_schedules.is_add_more IS NULL THEN tb_schedules.date END) AS day_end_expected'); //ngày kết thúc dự kiến
        return $query;
    }
    public static function getSqlClassAtendanceEmpty($params = [])
    {
        $query = tbClass::where('status', 'dang_hoc')
            ->withCount(['schedules as unattendance_count' => function ($query) {
                $query->where('status', 'chuahoc')->where('date', '<', now());
            }])
            ->with(['schedules' => function ($query) {
                $query->where('status', 'chuahoc')
                    ->where('date', '<', now())
                    ->select('id', 'class_id', 'date'); // Chỉ lấy các cột cần thiết
            }])
            ->having('unattendance_count', '>=', 3)
            ->orderBy('unattendance_count', "asc")
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_classs.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->whereIn('tb_classs.area_id', $params['area_id']);
            });
        return $query;
    }
    public static function getSqlClassgvnn($params = [])
    {
        $query = tbClass::select('tb_classs.*')

            ->join('tb_schedules', function ($join) {
                $join->on('tb_classs.id', '=', 'tb_schedules.class_id')
                    ->where('tb_schedules.type', 'gvnn');
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_classs.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_classs.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('tb_classs.status', $params['status']);
            })
            ->when(!empty($params['permission']), function ($query) use ($params) {
                $query->whereIn('tb_classs.id', $params['permission']);
            })
            ->when(!empty($params['other_list']), function ($query) use ($params) {
                return $query->whereNotIn('tb_classs.id', $params['other_list']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                return $query->where('tb_classs.type', $params['type']);
            })
            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_classs.level_id', $params['level_id']);
            })
            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_classs.area_id', $params['area_id']);
            })
            ->when(!empty($params['room_id']), function ($query) use ($params) {
                return $query->where('tb_classs.room_id', $params['room_id']);
            })
            ->when(!empty($params['list_level']), function ($query) use ($params) {
                return $query->where('tb_classs.list_level', $params['list_level']);
            })
            ->when(!empty($params['class_id']), function ($query) use ($params) {
                return $query->where('tb_classs.id', $params['class_id']);
            })
            ->when(!empty($params['course_id']), function ($query) use ($params) {
                return $query->where('tb_classs.course_id', $params['course_id']);
            })
            ->when(!empty($params['before_month']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', '<=', $params['before_month']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_classs.syllabus_id', $params['syllabus_id']);
            })

            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                $teacher_id = $params['teacher_id'];
                return $query->where(function ($where) use ($teacher_id) {
                    return $where->where('tb_classs.assistant_teacher', 'like', '%' . $teacher_id . '%')
                        ->orWhere('tb_classs.json_params->teacher', $teacher_id);
                });
            })
            ->when(!empty($params['end_date']), function ($query) use ($params) {
                return $query
                    ->addSelect([
                        'last_schedule_date' => Schedule::select('date')
                            ->whereColumn('tb_schedules.class_id', 'tb_classs.id')
                            ->orderByDesc('date')
                            ->limit(1)
                    ])
                    ->whereDate('tb_schedules.date', '=', $params['end_date'])
                    ->having('last_schedule_date', '=', $params['end_date']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                if (is_array($params['id'])) {
                    return $query->whereIn('tb_classs.id', $params['id']);
                } else {
                    return $query->where('tb_classs.id', $params['id']);
                }
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                return $query->where('tb_schedules.date', '=', $params['date']);
            });
        if (isset($params['school_day'])) {
            $query->whereDate('tb_schedules.date', '=', $params['school_day']);
        }
        $query->groupBy('tb_classs.id');
        $query->selectRaw('COUNT(tb_schedules.id) AS total_schedules');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.status = "dadiemdanh" THEN 1 END) AS total_attendance');
        $query->selectRaw('MIN(tb_schedules.date) AS day_start, MAX(tb_schedules.date) AS day_end');
        $query->selectRaw('MAX(CASE WHEN tb_schedules.is_add_more IS NULL THEN tb_schedules.date END) AS day_end_expected');

        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type_schedule = "fulltime" THEN 1 END) AS total_fulltime');
        $query->selectRaw('COUNT(CASE WHEN tb_schedules.type_schedule = "parttime" THEN 1 END) AS total_parttime');

        return $query;
    }


    public static function getClassInMonthByTeacher($params = [])
    {
        $query = tbClass::select('tb_classs.*')
            ->selectRaw('GROUP_CONCAT(tb_schedules.teacher_id, "") AS list_teacher_schedule_id')
            ->leftJoin('tb_schedules', 'tb_classs.id', '=', 'tb_schedules.class_id')
            ->when(!empty($params['months']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', $params['months']);
            })
            ->when(!empty($params['year']), function ($query) use ($params) {
                return $query->whereYear('tb_schedules.date', $params['year']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_schedules.id', $params['id']);
            })
            ->when(!empty($params['teacher_id']), function ($query) use ($params) {
                return $query->where('tb_schedules.teacher_id', $params['teacher_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_schedules.status', $params['status']);
            })
            ->when(!empty($params['before_month']), function ($query) use ($params) {
                return $query->whereMonth('tb_schedules.date', '<=', $params['before_month']);
            })
            ->groupBy('tb_classs.id');
        return $query;
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id', 'id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function students()
    {
        return $this
            ->belongsToMany(Student::class, UserClass::class, 'class_id', 'user_id')
            ->withPivot('status', 'json_params')
            ->withTimestamps();
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'class_id', 'id');
    }

    // Accessor để lấy teacher_id từ JSON
    public function getTeacherIdAttribute()
    {
        return isset($this->json_params->teacher) ? $this->json_params->teacher : null;
    }

    // Relation với bảng teachers
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}
