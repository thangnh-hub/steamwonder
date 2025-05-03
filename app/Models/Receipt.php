<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_receipt';

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

    public static function getSqlReceipt($params = [])
    {
        $query = Receipt::select('tb_receipt.*')

            ->when(!empty($params['area_id']), function ($query) use ($params) {
                return $query->where('tb_receipt.area_id', $params['area_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_receipt.id', $params['id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_receipt.status', $params['status']);
            });

        $query->groupBy('tb_receipt.id');
        return $query;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id ', 'id');
    }
    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
}
