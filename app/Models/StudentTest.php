<?php

namespace App\Models;

use App\Consts;
use Illuminate\Database\Eloquent\Model;

class StudentTest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_exam_questions';

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

    public static function getSqlStudentTest($params = [])
    {

        $query = StudentTest::select('tb_exam_questions.*')
            ->when(!empty($params['id_topic']), function ($query) use ($params) {
                return $query->where('tb_exam_questions.id_topic', $params['id_topic']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_exam_questions.status', $params['status']);
            })
            ->when(!empty($params['list_topic']), function ($query) use ($params) {
                return $query->whereIn('tb_exam_questions.id_topic', $params['list_topic']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_exam_questions.id', $params['id']);
            });
        return $query;
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'id_topic', 'id');
    }
}
