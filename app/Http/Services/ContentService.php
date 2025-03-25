<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\CmsPost;
use App\Models\CmsTaxonomy;
use App\Models\Menu;
use App\Models\Course;
use App\Models\Option;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ContentService
{
    public static function getOption()
    {
        return Option::get();
    }

    public static function getCourse($params = null)
    {

        $query = Course::select('tb_courses.*')
            ->selectRaw(' tb_syllabuss.name as syllabuss_name, tb_syllabuss.type as syllabuss_type')
            ->leftJoin('tb_syllabuss', 'tb_syllabuss.id', '=', 'tb_courses.syllabus_id')

            ->when(!empty($params['level_id']), function ($query) use ($params) {
                return $query->where('tb_courses.level_id', $params['level_id']);
            })
            ->when(!empty($params['syllabus_id']), function ($query) use ($params) {
                return $query->where('tb_courses.syllabus_id', $params['syllabus_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_courses.id', $params['id']);
            })
            ->when(!empty($params['type_syll']), function ($query) use ($params) {
                return $query->where('tb_syllabuss.type','=', $params['type_syll']);
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                $query->whereJsonContains('tb_courses.json_params->is_featured', $params['is_featured']);
            });

        if (!empty($params['status'])) {
            $query->where('tb_courses.status', $params['status']);
        } else {
            $query->where('tb_courses.status', "!=", Consts::STATUS_DELETE);
        }

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_courses.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_courses.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_courses.id desc');
        }
        return $query;
    }
}
