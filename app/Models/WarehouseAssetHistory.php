<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseAssetHistory extends Model
{
    protected $table = 'tb_warehouse_asset_history';

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

    public function admin_created()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id', 'id');
    }
    public function admin_updated()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id', 'id');
    }
    public function asset_his()
    {
        return $this->belongsTo(WarehouseAsset::class, 'asset_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(WareHouseProduct::class, 'product_id', 'id');
    }
    public function positions()
    {
        return $this->belongsTo(WareHousePosition::class, 'position_id', 'id');
    }
}
