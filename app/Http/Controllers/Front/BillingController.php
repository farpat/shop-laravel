<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViewBillingRequest;
use App\Models\Billing;
use App\Pdf\BillingPdf;
use App\Repositories\ModuleRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Middleware\Authenticate;

class BillingController extends Controller
{

    public function __construct ()
    {
        $this->middleware(Authenticate::class);
    }

    public function export (Billing $billing, ViewBillingRequest $request)
    {
        if ($request->query('force') == 1 || !File::exists($billing->billing_path)) {
            $billing->load(['items.product_reference', 'user']);
            $billingPdf = new BillingPdf($billing);
            $billingPdf->save();
        }

        return response()->file($billing->billing_path);
    }

    public function view (Billing $billing, ViewBillingRequest $request, ModuleRepository $moduleRepository)
    {
        $billing->load(['items.product_reference', 'user']);
        return view('billing.show', compact('billing'));
    }
}
