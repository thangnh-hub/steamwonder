<?php

namespace App;

use Illuminate\Support\Str;
use Hashids\Hashids;
use Carbon\Carbon;
use App\Consts;


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

    public static function convert_number_to_words_vi($number)
    {
        $number = (int)$number;
        if ($number == 0) return 'Không đồng';

        $units = ['', 'nghìn', 'triệu', 'tỷ'];
        $text = [
            0 => 'không',
            1 => 'một',
            2 => 'hai',
            3 => 'ba',
            4 => 'bốn',
            5 => 'năm',
            6 => 'sáu',
            7 => 'bảy',
            8 => 'tám',
            9 => 'chín'
        ];

        $result = '';
        $unitIndex = 0;

        while ($number > 0) {
            $block = $number % 1000;
            $number = floor($number / 1000);

            if ($block > 0 || ($unitIndex == 0 && $result != '')) {
                $blockText = '';

                $hundreds = floor($block / 100);
                $tensUnits = $block % 100;
                $tens = floor($tensUnits / 10);
                $unitsDigit = $tensUnits % 10;

                if ($hundreds > 0) {
                    $blockText .= $text[$hundreds] . ' trăm';
                } elseif ($number > 0 && ($tens > 0 || $unitsDigit > 0)) {
                    $blockText .= 'không trăm';
                }

                if ($tens > 0) {
                    if ($blockText !== '') $blockText .= ' ';
                    if ($tens == 1) {
                        $blockText .= 'mười';
                    } else {
                        $blockText .= $text[$tens] . ' mươi';
                    }
                } elseif ($unitsDigit > 0) {
                    if ($blockText !== '') $blockText .= ' lẻ';
                }

                if ($unitsDigit > 0) {
                    if ($blockText !== '') $blockText .= ' ';
                    if ($unitsDigit == 1 && $tens > 1) {
                        $blockText .= 'mốt';
                    } elseif ($unitsDigit == 5 && $tens > 0) {
                        $blockText .= 'lăm';
                    } else {
                        $blockText .= $text[$unitsDigit];
                    }
                }

                if ($blockText !== '') {
                    if ($unitIndex > 0) $blockText .= ' ' . $units[$unitIndex];
                    $result = $blockText . ' ' . $result;
                }
            }

            $unitIndex++;
        }

        return ucfirst(trim($result));
    }


    public static function getYearDefault()
    {
        $year = (int)Carbon::now()->format('Y');
        $school_year = [];
        for ($i = -2; $i <= 2; $i++) {
            $school_year[$year + (int)($i)] = ($year + (int)($i)) . ' - ' . ($year + (int)($i + 1));
        }
        return $school_year;
    }
}
