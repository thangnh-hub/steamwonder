<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryBookDistribution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_history_book_distribution';

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
    public function product()
    {
        return $this->belongsTo(WareHouseProduct::class, 'product_id', 'id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
    public function class()
    {
        return $this->belongsTo(tbClass::class, 'class_id', 'id');
    }
}
