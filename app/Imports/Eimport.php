<?php

namespace App\Imports;

use App\Models\CmsPost;
use App\Models\CmsRelationship;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Consts;

class Eimport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $params = [];
    public function __construct($params=[])
    {
        $this->params = $params;
    }

    public function model(array $row)
    {
        if (!isset($row[0]) || !is_numeric($row[0]) || $row[0] == null) {
            return null;
        }

        $detail = CmsPost::where('alias', Str::slug($row[1]))->where('is_type', $this->params['is_type'])->first();
        if ($detail) {
            //
            return null;
        } else {
            $json = [
                "brief" => [
                    $this->params['lang'] => $row[3]??'',
                ],
                "content" => [
                    $this->params['lang'] => $row[4]??'',
                ],
                "seo_title" => [
                    $this->params['lang'] => $row[5]??'',
                ],
                "seo_keyword" => [
                    $this->params['lang'] => $row[6]??'',
                ],
                "seo_description" => [
                    $this->params['lang'] => $row[7]??'',
                ],
                "name" => [
                    $this->params['lang'] => $row[1]??'',
                ],
                "route_name" =>  $this->params['route_name'],
                "template" =>  $this->params['template'],
            ];
            $CmsPost = CmsPost::create([
                'name' => $row[1],
                'alias' => Str::slug($row[1]),
                'is_type' => $this->params['is_type'],
                'json_params' => $json,
                'iorder' => $row[0]??'',
                'price' => $row[2]??'',
                'status' => Consts::STATUS['active'],
            ]);
            $arr_insert = [];
            if(isset($this->params['relation'])){
                foreach ($this->params['relation'] as $val) {
                $params_relation['object_id'] = $CmsPost->id;
                $params_relation['taxonomy_id'] = $val;
                $params_relation['object_type'] = $CmsPost->is_type;
                array_push($arr_insert, $params_relation);
            }
            }
            CmsRelationship::insert($arr_insert);
        }
    }
}
