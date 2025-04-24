<?php

if (!function_exists('numberFormat')) {
    function numberFormat($number, $decimals = 2, $decimalSeparator = '.', $thousandSeparator = ',') {
        if (!is_numeric($number)) {
            return number_format(0, $decimals, $decimalSeparator, $thousandSeparator);
        }

        return number_format(round($number, $decimals), $decimals, $decimalSeparator, $thousandSeparator);
    }
}
