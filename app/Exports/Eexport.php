<?php

namespace App\Exports;

use App\Models\CmsPost;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Config;

class Eexport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $params = [];
    protected $lang;
    public function __construct($params = [], $lang = '')
    {
        $this->params = $params;
        $this->lang = $lang;
    }

    public function array(): array
    {
        $data = $this->params;
        $lang = $this->lang;

        $arr_export = [];
        foreach ($data as $val) {
            $params_relation = [
                $val->iorder,
                $val->json_params->name->$lang ?? $val->name,
                $val->price,
                $val->json_params->brief->$lang ?? $val->brief,
                $val->json_params->content->$lang ?? $val->content,
                $val->json_params->seo_title->$lang ?? $val->seo_title,
                $val->json_params->seo_keyword->$lang ?? $val->seo_keyword,
                $val->json_params->seo_description->$lang ?? $val->seo_description,
            ];
            array_push($arr_export, $params_relation);
        }
        return $arr_export;
    }
    public function headings(): array
    {
        return ['Order', 'Title', 'Price', 'Brief', 'Content', 'Seo_title', 'Seo_keyword', 'Seo_description'];
    }
}
