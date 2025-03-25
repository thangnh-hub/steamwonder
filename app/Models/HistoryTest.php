<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryTest extends Model
{
    protected $table = 'tb_history_test';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
