<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Notify;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class NotifyService
{
    public static function add_notify($title = '', $type = '', $link = '', $id_object = '', $data = null)
    {
        if ($title != '' && $type != '') {
            $params['title'] = $title;
            $params['type'] = $type;
            $params['link'] = $link;
            $params['id_object'] = $id_object;
            $params['json_params'] = $data;
            $notify = Notify::create($params);
            return  $notify;
        }
    }
}
