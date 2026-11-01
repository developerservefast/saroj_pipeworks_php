<?php

namespace App\Support;

class Phone
{
    /** True for obviously fake 10-digit numbers. */
    public static function looksFake(string $d): bool
    {
        if (preg_match('/(\d)\1{5,}/', $d)) {
            return true;
        }
        if (count(array_unique(str_split($d))) <= 2) {
            return true;
        }
        $up = 1;
        $down = 1;
        for ($i = 1; $i < strlen($d); $i++) {
            $diff = (int) $d[$i] - (int) $d[$i - 1];
            $up = $diff === 1 ? $up + 1 : 1;
            $down = $diff === -1 ? $down + 1 : 1;
            if ($up >= 7 || $down >= 7) {
                return true;
            }
        }

        return false;
    }
}