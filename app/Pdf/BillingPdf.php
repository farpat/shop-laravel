<?php

namespace App\Pdf;

use App\Models\Cart;
use App\Services\Pdf\Pdf;

class BillingPdf extends Pdf
{
    /**
     * @var Cart
     */
    private $billing;

    public function __construct (Cart $billing)
    {
        $this->billing = $billing;
    }

    protected function getFilePath ()
    {
        return $this->billing->billing_path;
    }

    protected function getPages (): array
    {
        return [
            view('cart.billing', ['billing' => $this->billing])->render()
        ];
    }
}