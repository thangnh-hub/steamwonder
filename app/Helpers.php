<?php

namespace App;

use Illuminate\Support\Str;
use Hashids\Hashids;
use Carbon\Carbon;


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

    public static function getYear($day)
    {
        $parsedDay = Carbon::parse($day);
        $year = (int) $parsedDay->year;
        $month = (int)$parsedDay->month;
        if ($month >= 1 && $month < 6) {
            return ($year - 1) . ' - ' . $year;
        } else {
            return $year . ' - ' . ($year + 1);
        }
    }
}
