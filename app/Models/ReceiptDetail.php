<?php

namespace App\Models;
use App\Consts;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_receipt_detail';

    protected $guarded = [];
    protected $with = array('services_receipt');
    protected $casts = [
        'json_params' => 'object',
    ];

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
    public function services_receipt()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
