<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentService extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_student_services';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
    ];

    public function adminCreated()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function paymentcycle()
    {
        return $this->belongsTo(PaymentCycle::class, 'payment_cycle_id', 'id');
    }
}
