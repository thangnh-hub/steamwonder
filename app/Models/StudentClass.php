<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentClass extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'tb_class_student';

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

  public function student()
  {
    return $this->belongsTo(Student::class, 'student_id', 'id');
  }
  public function class()
  {
    return $this->belongsTo(tbClass::class, 'class_id', 'id');
  }
}
