<?php

namespace App\Pdf;

use App\Models\Billing;
use App\Services\Pdf\Pdf;

class BillingPdf extends Pdf
{
    /**
     * @var Billing
     */
    private $billing;

    public function __construct (Billing $billing)
    {
        $this->billing = $billing;
    }

    protected function getOptions (): array
    {
        return [
            //
        ];
    }

    protected function getFilePath (): ?string
    {
        return $this->billing->billing_path;
    }

    public function getPages (): array
    {
        return [
            view('billing.show', ['billing' => $this->billing])->render()
        ];
    }
}