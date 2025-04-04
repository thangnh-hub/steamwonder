<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'tb_admin_menus';

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  // Quan hệ để lấy các menu con
  public function children()
  {
    return $this->hasMany(AdminMenu::class, 'parent_id')->with('children')->orderBy('iorder');
  }

  // Quan hệ để lấy menu cha
  public function parent()
  {
    return $this->belongsTo(AdminMenu::class, 'parent_id');
  }
}
