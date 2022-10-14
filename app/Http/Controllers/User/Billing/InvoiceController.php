<?php

namespace App\Http\Controllers\User\Billing;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentTerm;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class InvoiceController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currency = Currency::all();
        $payment_terms = PaymentTerm::orderBy('terms','desc')->get();
        return view('user.billing.new_invoice', compact('currency', 'payment_terms'));
    }
}
