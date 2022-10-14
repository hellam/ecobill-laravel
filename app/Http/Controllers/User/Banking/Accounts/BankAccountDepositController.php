<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTrx;
use App\Models\Comment;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
use App\Models\CustomerTrx;
use App\Models\GlTrx;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountDepositController extends Controller
{
    public function index(): Factory|View|Application
    {
        $currency = Currency::all();
        return view('user.banking_gl.accounts.bank_deposit', compact('currency'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::accountDepositCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $customer_branch = null;
        if ($request->filled('customer_branch_id'))
            $customer_branch = CustomerBranch::find($request->customer_branch_id);
        $bank = BankAccount::find($request->into_bank);

        $total = 0;
        $fx_rate = $request->filled('fx_rate') ? $request->fx_rate : 1;

        try {
            DB::beginTransaction();


            $trans_no = generate_reff_no(ST_ACCOUNT_DEPOSIT, true, $request->reference);

            $post_data = [
                'trans_no' => $trans_no,
                'trx_type' => ST_ACCOUNT_DEPOSIT,
                'trx_date' => $request->date,
                'branch_id' => get_active_branch(),
                'client_ref' => get_user_ref(),
            ];
            $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));


            //Record entered GL Transactions
            foreach ($request->deposit_options as $key => $val) {
                $chart_code = $request->deposit_options[$key]['chat_code'];
                $amount = $request->deposit_options[$key]['amount'];
                $narration = $request->deposit_options[$key]['narration'];

                $total += $amount;

                $gl_trx_post = array_merge($post_data, [
                    'chart_code' => $chart_code,
                    'narration' => $narration ?? '',
                    'amount' => -convert_currency_to_second_currency($amount, $fx_rate),
                ]);

                GlTrx::create($gl_trx_post);
            }

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

            //Record Customer Transaction
            if ($request->filled('customer_branch_id')) {
                $customer_trx_post_data = array_merge($post_data, [
                    'customer_id' => $customer_branch->customer_id,
                    'customer_branch_id' => $request->customer_branch_id,
                    'rate' => $fx_rate,
                    'amount' => $total,
                    'reference' => $trans_no,
                ]);
                CustomerTrx::create($customer_trx_post_data);
            }

            if ($request->filled('comments') )
                Comment::create([
                    'trx_type' => ST_ACCOUNT_DEPOSIT,
                    'trans_no' => $trans_no,
                    'trx_date' => $request->date,
                    'comments' => $request->comments,
                    'branch_id' => get_active_branch(),
                    'client_ref' => get_user_ref(),
                ]);

            if ($created_at == null) {
                //if not supervised, log data from create request
                //Creator log
                log_activity(
                    ST_ACCOUNT_DEPOSIT,
                    $request->getClientIp(),
                    'Bank deposit',
                    json_encode($bank_trx_post_data),
                    auth('user')->id(),
                    $trans_no
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            return error_web_processor(__('messages.msg_something_went_wrong'));
        }

        return success_web_processor(['id' => $trans_no], __('messages.msg_saved_success', ['attribute' => __('messages.deposit')]));
    }
}
