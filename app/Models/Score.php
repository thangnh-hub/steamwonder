<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'tb_scores';

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  protected $with = array('student');

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'json_params' => 'object',
  ];

  public static function getSqlScore($params = [])
  {
    $query = Score::select('tb_scores.*')
      ->leftJoin('admins', 'admins.id', '=', 'tb_scores.user_id')
      ->leftJoin('tb_classs', 'tb_classs.id', '=', 'tb_scores.class_id')
      ->when(!empty($params['keyword']), function ($query) use ($params) {
        $keyword = $params['keyword'];
        return $query->where(function ($where) use ($keyword) {
          return $where->where('admins.name', 'like', '%' . $keyword . '%')
            ->orWhere('admins.admin_code', 'like', '%' . $keyword . '%')
            ->orWhere('admins.json_params->cccd', 'like', '%' . $keyword . '%');
        });
      })
      ->when(!empty($params['user_id']), function ($query) use ($params) {
        return $query->where('tb_scores.user_id', $params['user_id']);
      })
      ->when(!empty($params['staff_permission']), function ($query) use ($params) {
        return $query->whereIn('admins.admission_id', $params['staff_permission']);
      })
      ->when(!empty($params['status']), function ($query) use ($params) {
        return $query->where('tb_scores.status', $params['status']);
      })
      ->when(!empty($params['course_id']), function ($query) use ($params) {
        return $query->where('admins.course_id', $params['course_id']);
      })
      ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
        return $query->where('tb_classs.syllabus_id', $params['syllabus_id']);
      })
      ->when(!empty($params['list_level']), function ($query) use ($params) {
        return $query->where('tb_classs.level_id', $params['list_level']);
      })
      ->when(!empty($params['fail']), function ($query) {
        return $query->where(function ($where) {
          return $where->where('tb_scores.status', 'fail')
            ->orWhereJsonContains('tb_scores.json_params->check_retest', 'retest');
        });
      })
      ->when(!empty($params['list_id']), function ($query) use ($params) {
        return $query->whereIn('tb_scores.user_id', $params['list_id']);
      })
      ->when(!empty($params['id']), function ($query) use ($params) {
        return $query->where('tb_scores.id', $params['id']);
      });
    if (isset($params['class_id'])) {
      $query->where('tb_scores.class_id', $params['class_id']);
    }

    $query->groupBy('tb_scores.id');
    return $query;
  }

  public function class()
  {
    return $this->belongsTo(tbClass::class, 'class_id', 'id');
  }

  public function student()
  {
    return $this->belongsTo(Student::class, 'user_id', 'id');
  }
}
