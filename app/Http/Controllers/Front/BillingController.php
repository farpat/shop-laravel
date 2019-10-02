<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Pdf\BillingPdf;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Middleware\Authenticate;

class BillingController extends Controller
{

    public function __construct ()
    {
        $this->middleware(Authenticate::class);
    }

    public function export (Cart $billing)
    {
        if (!File::exists($billing->billing_path)) {
            $billing->load(['items.product_reference']);

            $billingPdf = new BillingPdf($billing);
            $billingPdf->save();
        }

        return response()->file($billing->billing_path);
    }

    public function view (Cart $billing)
    {
        $billing->load(['items.product_reference']);
        return view('cart.billing', compact('billing'));
    }
}
