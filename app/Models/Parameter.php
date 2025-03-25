<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Parameter extends Model
{
    protected $table = 'tb_properties';
    protected $casts = [
        'json_params' => 'object',
    ];
    protected $guarded = [];

    public function reSort(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $key => $menu) {
                $this->where('id', $key)->update($menu);
            }
            DB::commit();
            $return = ['error' => 0, 'msg' => ""];
        } catch (\Throwable $e) {
            DB::rollBack();
            $return = ['error' => 1, 'msg' => $e->getMessage()];
        }
        return $return;
    }
}
