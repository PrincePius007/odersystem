<?php

if (!function_exists('numberFormat')) {
    function numberFormat($number, $decimals = 2, $decimalSeparator = '.', $thousandSeparator = ',') {
        if (!is_numeric($number)) {
            return number_format(0, $decimals, $decimalSeparator, $thousandSeparator);
        }

        return number_format(round($number, $decimals), $decimals, $decimalSeparator, $thousandSeparator);
    }
}


// <?php

// if (!function_exists('numberFormat')) {
//     function numberFormat($number, $decimals = 2, $decimalSeparator = '.', $thousandSeparator = ',') {
//         // Return '-' if null, empty, or not numeric
//         if (!is_numeric($number) || $number === null || $number === '') {
//             return '-';
//         }


//         // Return 0 formatted if number is exactly 0
//         if ($number == 0) {
//             return number_format(0, $decimals, $decimalSeparator, $thousandSeparator);
//         }

//         // Otherwise, format and return the number
//         return number_format(round($number, $decimals), $decimals, $decimalSeparator, $thousandSeparator);
//     }
// }
