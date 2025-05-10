<?php

namespace App;

use Illuminate\Support\Str;
use Hashids\Hashids;


class Helpers
{
    static function intToRoman($num)
    {
        $map = [
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        ];
        $result = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $result .= $roman;
                $num -= $value;
            }
        }
        return $result;
    }
}
