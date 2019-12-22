<?php

namespace App\Support;


use App\Repositories\ModuleRepository;
use Illuminate\Support\Str as BaseStr;

class StringUtility
{
    public static function getFormattedPrice (float $price): string
    {
        $currencyParameter = app(ModuleRepository::class)->getParameter('billing', 'currency')->value;

        switch ($currencyParameter->style) {
            case 'left':
                $number = number_format($price, 2);
                return $currencyParameter->symbol . ' ' . $number;
            case 'right':
                $number = number_format($price, 2, ',', ' ');
                return $number . ' ' . $currencyParameter->symbol;
            default:
                return (string)$price;

        }
    }

    /**
     * @param string $string
     *
     * @return \stdClass|array|null
     */
    public static function jsonDecode (string $string)
    {
        if (BaseStr::startsWith($string, ['{', '['])) {
            $newValue = json_decode($string);
            return json_last_error() === JSON_ERROR_NONE ? $newValue : null;
        }
    }
}