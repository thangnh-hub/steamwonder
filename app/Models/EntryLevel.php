<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class EntryLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_entry_levels';

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

    public static function getSqlEntryLevel($params = [])
    {
        $query = EntryLevel::select('tb_entry_levels.*')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_entry_levels.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_entry_levels.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_entry_levels.id', $params['id']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_entry_levels.status', $params['status']);
        } else {
            $query->where('tb_entry_levels.status', "!=", Consts::STATUS_DELETE);
        }

        $query->groupBy('tb_entry_levels.id');

        return $query;
    }
}
