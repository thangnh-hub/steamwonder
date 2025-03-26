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

}
