<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class LessonGrammar extends Model
{
    protected $table = 'tb_vocabulary_grammars';

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
}
