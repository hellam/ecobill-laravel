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
        $current = Currency::all();
        $payment_terms = PaymentTerm::all();
        return view('user.billing.new_invoice', compact('current', 'payment_terms'));
    }
}
