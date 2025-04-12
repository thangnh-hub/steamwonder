<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbParent extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_parents';

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

    public function dataCrm()
    {
        return $this->belongsTo(DataCrm::class, 'data_crm_id', 'id');
    }

    public function parentStudents()
    {
        return $this->hasMany(StudentParent::class, 'parent_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function admission()
    {
        return $this->belongsTo(Admin::class, 'admission_id', 'id');
    }
}
