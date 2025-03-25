<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassProcess extends Model
{
    protected $table = 'tb_class_process';

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
        'a11' => 'object',
        'a12' => 'object',
        'a21' => 'object',
        'a22' => 'object',
        'b11' => 'object',
        'b12' => 'object',
        'otcs' => 'object',
    ];
    public static function getSqlProcessClass($params = [])
    {
        $query = ClassProcess::select('tb_class_process.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_class_process.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_class_process.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_class_process.id', '!=', $params['different_id']);
            });

        $query->groupBy('tb_class_process.id');

        return $query;
    }
}
