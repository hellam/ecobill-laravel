<?php

namespace App\Http\Controllers\User\Billing;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentTerm;
use App\Models\Tax;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currency = Currency::all();
        $tax = Tax::all();
        $payment_terms = PaymentTerm::orderBy('terms','desc')->get();
        return view('user.billing.new_invoice', compact('currency', 'payment_terms','tax'));
    }

    public function create(Request $request){
        $validator = UserValidators::newInvoiceCreateValidation($request);
        if ($validator != '') {
            return $validator;
        }

        $trans_no = generate_reff_no(ST_INVOICE, true, $request->reference);

        return success_web_processor(['id' => $trans_no], __('messages.msg_saved_success', ['attribute' => __('messages.invoice')]));
    }
}
