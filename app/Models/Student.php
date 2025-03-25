<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = 'admins';

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['is_super_admin'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'json_params' => 'object',
  ];

  /**
   * Add a mutator to ensure hashed passwords
   */
  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = bcrypt($password);
  }

  public static function getSqlStudent($params = [])
  {
    $query = Student::select('admins.*', 'tb_roles.name AS role_name')
      ->selectRaw('tb_areas.name as area_name, tb_status_students.name as status_study_name')
      ->selectRaw('DATEDIFF(CURDATE(), admins.day_official) AS days_since_official')
      ->leftJoin('tb_areas', 'tb_areas.id', '=', 'admins.area_id')
      ->leftJoin('tb_status_students', 'admins.status_study', '=', 'tb_status_students.id')
      ->selectRaw('GROUP_CONCAT("", tb_user_class.class_id) as class_id')
      ->leftJoin('tb_user_class', 'admins.id', '=', 'tb_user_class.user_id')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.email', 'like', '%' . $keyword . '%')
            ->orWhere('admins.name', 'like', '%' . $keyword . '%')
            ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
            ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
        });
      })
      ->when(!empty($params['student_code']), function ($query) use ($params) {
        $keyword = $params['student_code'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.admin_code', 'like', '%' . $keyword . '%');
        });
      })
      ->when(!empty($params['area_id']), function ($query) use ($params) {
        return $query->where('admins.area_id', $params['area_id']);
      })
      ->when(!empty($params['class_id']), function ($query) use ($params) {
        return $query->where('tb_user_class.class_id', $params['class_id']);
      })
      ->when(!empty($params['course_id']), function ($query) use ($params) {
        return $query->where('admins.course_id', $params['course_id']);
      })
      ->when(!empty($params['list_id']), function ($query) use ($params) {
        return $query->whereIn('admins.id', $params['list_id']);
      })
      ->when(!empty($params['id']), function ($query) use ($params) {
        return $query->where('admins.id', $params['id']);
      })
      ->when(!empty($params['different_id']), function ($query) use ($params) {
        if (is_array($params['different_id'])) {
          return $query->whereNotIn('admins.id', $params['different_id']);
        }
        return $query->where('admins.id', '!=', $params['different_id']);
      })
      ->when(!empty($params['admission_id']), function ($query) use ($params) {
        $query->where('admins.admission_id', $params['admission_id']);
      })
      ->when(!empty($params['list_admission_id']), function ($query) use ($params) {
        $query->whereIn('admins.admission_id', $params['list_admission_id']);
      })
      ->when(!empty($params['admin_code']), function ($query) use ($params) {
        return $query->where('admins.admin_code', $params['admin_code']);
      })
      ->when(!empty($params['ketoan_xacnhan']), function ($query) use ($params) {
        return $query->where('admins.ketoan_xacnhan', $params['ketoan_xacnhan']);
      })
      ->when(!empty($params['version']), function ($query) use ($params) {
        return $query->where('admins.version', $params['version']);
      })
      ->when(!empty($params['contract_type']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->contract_type', $params['contract_type']);
      })
      ->when(!empty($params['other_list']), function ($query) use ($params) {
        return $query->whereNotIn('admins.id', $params['other_list']);
      })
      ->when(!empty($params['day_official']), function ($query) use ($params) {
        return $query->whereRaw('DATEDIFF(CURDATE(), admins.day_official) > 120');
      })
      ->orderBy('admins.id', 'desc');

    $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');
    $query->where('admins.admin_type', Consts::ADMIN_TYPE['student']);

    if (!empty($params['level_id'])) {
      $query->where('admins.level_id', $params['level_id']);
    }
    if (!empty($params['rank_score'])) {
      $query->where('admins.rank_score', $params['rank_score']);
    }
    if (!empty($params['status_study'])) {
      $query->where('admins.status_study', $params['status_study']);
    }
    if (!empty($params['status_study_null'])) {
      $query->where('admins.status_study', Null);
    }
    if (!empty($params['status'])) {
      $query->where('admins.status', $params['status']);
    } else {
      $query->where('admins.status', "!=", Consts::STATUS_DELETE);
    }
    if (!empty($params['state'])) {
      $query->where('admins.state', $params['state']);
    } else {
      $query->where('admins.status', "!=", Consts::STATUS_DELETE);
    }
    // Check with order_by params
    if (!empty($params['order_by'])) {
      if (is_array($params['order_by'])) {
        foreach ($params['order_by'] as $key => $value) {
          $query->orderBy('admins.' . $key, $value);
        }
      } else {
        $query->orderByRaw('admins.' . $params['order_by'] . ' desc');
      }
    } else {
      $query->orderByRaw('admins.id DESC');
    }

    $query->groupBy('admins.id');

    return $query;
  }
  public static function getsqlStudentIndex($params = [])
  {
    $query = Student::select('admins.*', 'tb_roles.name AS role_name')
      ->selectRaw('tb_status_students.name as status_study_name')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.email', 'like', '%' . $keyword . '%')
            ->orWhere('admins.name', 'like', '%' . $keyword . '%')
            ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
            ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
        });
      })
      ->leftJoin('tb_status_students', 'admins.status_study', '=', 'tb_status_students.id')
      ->when(!empty($params['area_id']), function ($query) use ($params) {
        return $query->where('admins.area_id', $params['area_id']);
      })
      ->when(!empty($params['class_id']), function ($query) use ($params) {
        $query->leftJoin('tb_user_class', 'admins.id', '=', 'tb_user_class.user_id');
        if ($params['class_id'] == 'null') {
          $query->whereNull('tb_user_class.class_id');
        } else {
          $query->where('tb_user_class.class_id', $params['class_id']);
        }
      })
      ->when(!empty($params['admission_id']), function ($query) use ($params) {
        $query->where('admins.admission_id', $params['admission_id']);
      })
      ->when(!empty($params['status_study']), function ($query) use ($params) {
        return $query->where('admins.status_study', $params['status_study']);
      })
      ->when(!empty($params['year_offical']), function ($query) use ($params) {
        return $query->whereYear('admins.day_official', $params['year_offical']);
      })
      ->when(!empty($params['status_dormitory']), function ($query) use ($params) {
        return $query->where('admins.status_dormitory', $params['status_dormitory']);
      })
      ->when(!empty($params['course_id']), function ($query) use ($params) {
        return $query->where('admins.course_id', $params['course_id']);
      })
      ->when(!empty($params['list_id']), function ($query) use ($params) {
        return $query->whereIn('admins.id', $params['list_id']);
      })
      ->when(!empty($params['id']), function ($query) use ($params) {
        return $query->where('admins.id', $params['id']);
      })
      ->when(!empty($params['level_id']), function ($query) use ($params) {
        return $query->where('admins.level_id', $params['level_id']);
      })
      ->when(!empty($params['dormitory']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->dormitory', $params['dormitory']);
      })
      ->when(!empty($params['contract_type']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->contract_type', $params['contract_type']);
      })
      ->when(!empty($params['contract_status']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->contract_status', $params['contract_status']);
      })
      ->when(!empty($params['field_id']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->field_id', $params['field_id']);
      })
      ->when(!empty($params['version']), function ($query) use ($params) {
        return $query->where('admins.version', $params['version']);
      })

      ->orderBy('admins.id', 'desc');

    $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');
    $query->where('admins.admin_type', Consts::ADMIN_TYPE['student']);

    if (!empty($params['status'])) {
      $query->where('admins.status', $params['status']);
    } else {
      $query->where('admins.status', "!=", Consts::STATUS_DELETE);
    }
    if (!empty($params['state'])) {
      $query->where('admins.state', $params['state']);
    }
    // Check with order_by params
    if (!empty($params['order_by'])) {
      if (is_array($params['order_by'])) {
        foreach ($params['order_by'] as $key => $value) {
          $query->orderBy('admins.' . $key, $value);
        }
      } else {
        $query->orderByRaw('admins.' . $params['order_by'] . ' desc');
      }
    } else {
      $query->orderByRaw('admins.id DESC');
    }

    $query->groupBy('admins.id');

    return $query;
  }

  public static function getsqlStudentAccounting($params = [])
  {
    $query = Student::select('admins.*', 'tb_roles.name AS role_name')
      ->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id')
      ->selectRaw('tb_status_students.name as status_study_name')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.email', 'like', '%' . $keyword . '%')
            ->orWhere('admins.name', 'like', '%' . $keyword . '%')
            ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
            ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
        });
      })

      ->leftJoin('tb_status_students', 'admins.status_study', '=', 'tb_status_students.id')
      ->when(!empty($params['area_id']), function ($query) use ($params) {
        if ($params['area_id'] == 'null') {
          return $query->whereNull('admins.area_id');
        } else {
          return $query->where('admins.area_id', $params['area_id']);
        }
      })
      ->when(!empty($params['class_id']), function ($query) use ($params) {
        $query->leftJoin('tb_user_class', 'admins.id', '=', 'tb_user_class.user_id');
        if ($params['class_id'] == 'null') {
          $query->whereNull('tb_user_class.class_id');
        } else {
          $query->where('tb_user_class.class_id', $params['class_id']);
        }
      })
      ->when(!empty($params['admission_id']), function ($query) use ($params) {
        if ($params['admission_id'] == 'null') {
          return $query->whereNull('admins.admission_id');
        } else {
          return $query->where('admins.admission_id', $params['admission_id']);
        }
      })
      ->when(!empty($params['status_study']), function ($query) use ($params) {
        if ($params['status_study'] == 'null') {
          return $query->whereNull('admins.status_study');
        } else {
          return $query->where('admins.status_study', $params['status_study']);
        }
      })
      ->when(!empty($params['status_dormitory']), function ($query) use ($params) {
        return $query->where('admins.status_dormitory', $params['status_dormitory']);
      })
      ->when(!empty($params['course_id']), function ($query) use ($params) {
        if ($params['course_id'] == 'null') {
          return $query->whereNull('admins.course_id');
        } else {
          return $query->where('admins.course_id', $params['course_id']);
        }
      })
      ->when(!empty($params['list_id']), function ($query) use ($params) {
        return $query->whereIn('admins.id', $params['list_id']);
      })
      ->when(!empty($params['id']), function ($query) use ($params) {
        return $query->where('admins.id', $params['id']);
      })
      ->when(!empty($params['level_id']), function ($query) use ($params) {
        if ($params['level_id'] == 'null') {
          return $query->whereNull('admins.level_id');
        } else {
          return $query->where('admins.level_id', $params['level_id']);
        }
      })
      ->when(!empty($params['dormitory']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->dormitory', $params['dormitory']);
      })
      ->when(!empty($params['contract_type']), function ($query) use ($params) {
        if ($params['contract_type'] == 'null') {
          return $query->where(function ($where) {
            return $where->whereNull('admins.json_params->contract_type')
              ->orWhere('admins.json_params->contract_type', '');
          });
        } else {
          return $query->whereJsonContains('admins.json_params->contract_type', $params['contract_type']);
        }
      })
      ->when(!empty($params['contract_status']), function ($query) use ($params) {
        if ($params['contract_status'] == 'null') {
          return $query->where(function ($where) {
            return $where->whereNull('admins.json_params->contract_status')
              ->orWhere('admins.json_params->contract_status', '');
          });
        } else {
          return $query->whereJsonContains('admins.json_params->contract_status', $params['contract_status']);
        }
      })
      ->when(!empty($params['field_id']), function ($query) use ($params) {
        $query->whereJsonContains('admins.json_params->field_id', $params['field_id']);
      })
      ->when(!empty($params['from_date_official']), function ($query) use ($params) {
        $query->where('admins.day_official', '>=', $params['from_date_official']);
      })
      ->when(!empty($params['to_date_official']), function ($query) use ($params) {
        $query->where('admins.day_official', '<=', $params['to_date_official']);
      })
      ->when(!empty($params['version']), function ($query) use ($params) {
        if ($params['version'] == 'null') {
          return $query->whereNull('admins.version');
        } else {
          return $query->where('admins.version', $params['version']);
        }
      });

    if (!empty($params['status'])) {
      $query->where('admins.status', $params['status']);
    } else {
      $query->where('admins.status', "!=", Consts::STATUS_DELETE);
    }

    if (!empty($params['state'])) {
      if ($params['state'] == 'null') {
        return $query->whereNull('admins.state');
      } else {
        return $query->where('admins.state', $params['state']);
      }
    }
    // Check with order_by params
    if (!empty($params['order_by'])) {
      if (is_array($params['order_by'])) {
        foreach ($params['order_by'] as $key => $value) {
          $query->orderBy('admins.' . $key, $value);
        }
      } else {
        $query->orderByRaw('admins.' . $params['order_by'] . ' desc');
      }
    } else {
      $query->orderByRaw('admins.id DESC');
    }

    $query->where('admins.admin_type', Consts::ADMIN_TYPE['student']);
    $query->groupBy('admins.id');
    return $query;
  }

  public function class_detal()
  {
    return $this->belongsTo(tbClass::class, 'class_id', 'id');
  }

  public function classs()
  {
    return $this
      ->belongsToMany(tbClass::class, UserClass::class, 'user_id', 'class_id')
      ->withPivot('status', 'json_params')
      ->withTimestamps();
  }

  public function allClassesWithStatus()
  {
    // Lấy danh sách class từ UserClass (many-to-many với pivot)
    $userClasses = $this->classs()->withPivot('status')->get();

    // Lấy danh sách class từ Attendance
    $attendanceClasses = Attendance::where('user_id', $this->id)
      ->pluck('class_id')
      ->toArray();

    // Gộp danh sách ID của lớp từ cả 2 bảng
    $allClassIds = array_unique(array_merge($userClasses->pluck('id')->toArray(), $attendanceClasses));

    // Lấy danh sách lớp từ tbClass
    $allClasses = tbClass::whereIn('id', $allClassIds)->get();


    // Gắn trạng thái từ bảng trung gian (nếu có)
    foreach ($allClasses as $class) {
      $check_history = History::where('student_id', $this->id)->where('class_id_new', $class->id)->where('type', Consts::HISTORY_TYPE['change_class'])->first();
      $pivotData = $userClasses->firstWhere('id', $class->id);
      // $class->pivot_status = $pivotData ? $pivotData->pivot->status : null;
      $class->pivot_status = isset($check_history->status_change_class) ? $check_history->status_change_class : "";
    }

    return $allClasses;
  }


  public function evaluations()
  {
    return $this->hasMany(Evaluation::class, 'student_id', 'id');
  }
  public function attendances()
  {
    return $this->hasMany(Attendance::class, 'user_id', 'id');
  }
  public function scores()
  {
    return $this->hasMany(Score::class, 'user_id', 'id');
  }
  public function StatusStudent()
  {
    return $this->belongsTo(StatusStudent::class, 'status_study', 'id');
  }
  public function admission()
  {
    return $this->belongsTo(StaffAdmission::class, 'admission_id', 'id');
  }
  public function area()
  {
    return $this->belongsTo(Area::class, 'area_id', 'id');
  }
  public function course()
  {
    return $this->belongsTo(Course::class, 'course_id', 'id');
  }
  public function AccountingDebt()
  {
    return $this->hasMany(AccountingDebt::class, 'student_id', 'id');
  }
  public function level()
  {
    return $this->belongsTo(Level::class, 'level_id', 'id');
  }
  public function history_book_active()
  {
    return $this->hasMany(HistoryBookDistribution::class, 'student_id', 'id')->where('status', Consts::STATUS_BOOK_DISTRIBUTION_STUDENT['daphatsach']);
  }

  public function lichSuBaoLuu(): HasOne
  {
    return $this->hasOne(History::class, 'student_id')
      ->where('status_study_new', 7)
      ->latest('updated_at');
  }
  public function soNgayBaoLuu(): ?int
  {
    $lichSu = $this->lichSuBaoLuu;
    if ($lichSu) {
      return Carbon::parse($lichSu->updated_at)->diffInDays(Carbon::now());
    }
    return null;
  }

  // Sử dụng cho phần lấy các lớp mà sinh viên đó theo học
  public function userClasses()
  {
    return $this->hasMany(UserClass::class, 'user_id', 'id');
  }
}
