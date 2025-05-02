<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCycle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_payment_cycle';

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
    protected $casts = [];

    public static function getSqlPaymentCycle($params = [])
    {

        $query = PaymentCycle::select('tb_payment_cycle.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_payment_cycle.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_payment_cycle.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_payment_cycle.id', $params['id']);
            });
        
        $query->groupBy('tb_payment_cycle.id');
        return $query;
    }
}
