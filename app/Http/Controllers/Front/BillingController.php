<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViewBillingRequest;
use App\Models\Cart;
use App\Pdf\BillingPdf;
use App\Repositories\ModuleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\View;

class BillingController extends Controller
{

    public function __construct ()
    {
        $this->middleware(Authenticate::class);
    }

    public function export (Cart $billing, ViewBillingRequest $request)
    {
        if ($request->query('force') == 1 || !File::exists($billing->billing_path)) {
            $billing->load(['items.product_reference', 'user']);
            $billingPdf = new BillingPdf($billing);
            $billingPdf->save();
        }

        return response()->file($billing->billing_path);
    }

    public function view (Cart $billing, ViewBillingRequest $request, ModuleRepository $moduleRepository)
    {
        $billing->load(['items.product_reference', 'user']);
        return view('cart.billing', compact('billing'));
    }
}
