<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotify extends Model
{
    protected $table = 'tb_notify_user';
    protected $casts = [
        'json_params' => 'object',
    ];
    protected $guarded = [];
}
