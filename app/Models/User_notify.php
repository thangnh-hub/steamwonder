<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_notify extends Model
{
    protected $table = 'tb_notify_user';
    protected $casts = [
        'json_params' => 'object',
    ];
    protected $guarded = [];
}
