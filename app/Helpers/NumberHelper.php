<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toWords($number)
    {
        $no = floor($number);
        $decimal = round($number - $no, 2) * 100;

        $words = [
            0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen',
            17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            20 => 'twenty', 30 => 'thirty', 40 => 'forty',
            50 => 'fifty', 60 => 'sixty', 70 => 'seventy',
            80 => 'eighty', 90 => 'ninety'
        ];

        $digits = ['', 'hundred', 'thousand', 'lakh', 'crore'];
        $str = [];
        $i = 0;

        while ($no > 0) {
            $divider = ($i == 1) ? 10 : 100;
            $numberPart = $no % $divider;
            $no = (int)($no / $divider);

            if ($numberPart) {
                $plural = (($counter = count($str)) && $numberPart > 9) ? 's' : '';
                $hundred = ($counter == 1 && $str[0]) ? 'and ' : '';
                $str[] = ($numberPart < 21)
                    ? $words[$numberPart] . ' ' . $digits[$counter] . ' ' . $plural . ' ' . $hundred
                    : $words[floor($numberPart / 10) * 10] . ' ' . $words[$numberPart % 10] . ' ' . $digits[$counter] . ' ' . $plural . ' ' . $hundred;
            } else {
                $str[] = '';
            }
            $i++;
        }

        $rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0)
            ? "and " . $words[floor($decimal / 10) * 10] . " " . $words[$decimal % 10] . " paise"
            : '';

        return trim($rupees . ' rupees ' . $paise);
    }
}
