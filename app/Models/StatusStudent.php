<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusStudent extends Model
{
    protected $table = 'tb_status_students';

    public static function getSqlStatusStudent($params = [])
    {
        $query = StatusStudent::select('tb_status_students.*')
            ->when(!empty($params['status_student']), function ($query) use ($params) {
                return $query->where('tb_status_students.id', $params['status_student']);
            });

        $query->groupBy('tb_status_students.id');

        return $query;
    }
}
