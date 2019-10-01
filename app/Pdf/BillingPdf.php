<?php

namespace App\Pdf;

use App\Models\Cart;
use App\Services\Pdf\Pdf;
use Throwable;

class BillingPdf extends Pdf
{
    /**
     * @var Cart
     */
    private $billing;

    public function __construct (Cart $billing)
    {
        $billing->load(['items.product_reference']);
        $this->billing = $billing;
    }

    /**
     * @return array|string
     * @throws Throwable
     */
    protected function render (): string
    {
        return view('cart.billing', ['billing' => $this->billing])->render();
    }

    public function save (string $billingPath = null): bool
    {
        return parent::save($billingPath ?? $this->billing->billing_path);
    }
}