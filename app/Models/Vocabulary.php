<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Vocabulary extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_vocabulary';

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
    public static function getSqlVocabulary($params = [])
    {
        $query = Vocabulary::select('tb_vocabulary.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_vocabulary.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['arr_keyword']), function ($query) use ($params) {
                $keywords = array_map('strtolower', $params['arr_keyword']);
                return $query->where(function ($where) use ($keywords) {
                    return $where->whereIn(DB::raw('LOWER(tb_vocabulary.name)'), $keywords);
                });
            });
        $query->orderBy('tb_vocabulary.id','DESC');
        return $query;
    }
}
