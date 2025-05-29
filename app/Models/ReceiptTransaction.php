<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptTransaction extends Model
{
    protected $table = 'tb_receipt_transaction';

    protected $guarded = [];

    protected $casts = [
        'json_params' => 'object',
    ];

    public function user_cashier()
    {
        return $this->belongsTo(Admin::class, 'cashier','id');
    }
}
