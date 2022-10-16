<?php

namespace App\Http\Controllers\User\Billing;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTrx;
use App\Models\Comment;
use App\Models\Currency;
use App\Models\CustomerBranch;
use App\Models\CustomerTrx;
use App\Models\GlTrx;
use App\Models\PaymentTerm;
use App\Models\Tax;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currency = Currency::all();
        $tax = Tax::all();
        $payment_terms = PaymentTerm::orderBy('terms', 'desc')->get();
        return view('user.billing.new_invoice', compact('currency', 'payment_terms', 'tax'));
    }

    public function create(Request $request)
    {
        $validator = UserValidators::newInvoiceCreateValidation($request);
        if ($validator != '') {
            return $validator;
        }

        $customer_branch = CustomerBranch::find($request->customer);

        $total = 0;
        $fx_rate = $request->filled('fx_rate') ? $request->fx_rate : 1;

        try {
            DB::beginTransaction();

            $trans_no = generate_reff_no(ST_INVOICE, true, $request->reference);


            $post_data = [
                'trans_no' => $trans_no,
                'trx_type' => ST_ACCOUNT_DEPOSIT,
                'trx_date' => $request->date,
                'branch_id' => get_active_branch(),
                'client_ref' => get_user_ref(),
            ];
            $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));


            //save payment transaction if it's a cash sale
            if ($request->filled('into_bank')){
                $payment_trans_no = generate_reff_no(ST_CUSTOMER_PAYMENT, true);
                $bank = BankAccount::find($request->into_bank);

                //Record Bank Account GL Transaction
                $bank_gl_trx_post = array_merge($post_data, [
                    'chart_code' => $bank->chart_code,
                    'narration' => $request->filled('customer_branch_id') ? $customer_branch->f_name . ' ' . $customer_branch->l_name . ' [' . $customer_branch->id . ']' : $request->misc ?? '',
                    'amount' => convert_currency_to_second_currency($total, $fx_rate)
                ]);
                GlTrx::create($bank_gl_trx_post);

                //Record Bank Transaction
                $bank_trx_post_data = array_merge($post_data, [
                    'reference' => $trans_no,
                    'bank_id' => $request->into_bank,
                    'amount' => $total,
                ]);
                BankTrx::create($bank_trx_post_data);

                $customer_trx_post_data = array_merge($post_data, [
                    'customer_id' => $customer_branch->customer_id,
                    'customer_branch_id' => $request->customer_branch_id,
                    'rate' => $fx_rate,
                    'amount' => $total,
                    'reference' => $trans_no,
                ]);
                CustomerTrx::create($customer_trx_post_data);
            }

            //Comments
            if ($request->filled('comments') )
                Comment::create([
                    'trx_type' => ST_ACCOUNT_DEPOSIT,
                    'trx_no' => $trans_no,
                    'trx_date' => $request->date,
                    'comment' => $request->comments,
                    'branch_id' => get_active_branch(),
                    'client_ref' => get_user_ref(),
                ]);
            DB::commit();
        } catch (\Exception $e) {
            return error_web_processor(__('messages.msg_something_went_wrong'));
        }
        return success_web_processor(['id' => $trans_no], __('messages.msg_saved_success', ['attribute' => __('messages.invoice')]));
    }
}
