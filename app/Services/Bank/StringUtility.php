<?php

namespace App\Services\Bank;


use App\Repositories\ModuleRepository;

class StringUtility
{
    public static function getFormattedPrice (float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' ' . app(ModuleRepository::class)->getParameter('billing', 'currency')->value;
    }
}