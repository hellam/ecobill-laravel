<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
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

        $trans_no = generate_reff_no(ST_ACCOUNT_DEPOSIT, true, $request->reference);

        $post_data = [
            'trans_no' => $trans_no,
            'trx_type' => ST_ACCOUNT_DEPOSIT,
            'trx_date' => $request->date,
            'client_ref' => get_user_ref(),
        ];
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $total = 0;
        $fx_rate = $request->filled('fx_rate') ? $request->fx_rate : 1;

        try {
            DB::beginTransaction();

            //Record entered GL Transactions
            foreach ($request->deposit_options as $key => $val) {
                $chart_code = $request->deposit_options[$key]['chat_code'];
                $amount = $request->deposit_options[$key]['amount'];
                $narration = $request->deposit_options[$key]['narration'];

                $total += $amount;

                $gl_trx_post = array_merge($post_data, [
                    'chart_code' => $chart_code,
                    'narration' => $narration ?? '',
                    'amount' => convert_currency_to_second_currency($amount, $fx_rate),
                ]);

                GlTrx::create($gl_trx_post);
            }

            //Record Bank Account GL Transaction
            $bank_gl_trx_post = array_merge($post_data, [
                'chart_code' => $bank->chart_code,
                'narration' => '',
                'amount' => convert_currency_to_second_currency($total, $fx_rate)
            ]);
            GlTrx::create($bank_gl_trx_post);


            $customer_trx_post_data = [
                'customer_id' => $customer_branch->customer_id,
                'customer_branch_id' => $request->customer_branch_id,
                'reference' => $trans_no,
            ];
            $bank_trx_post_data = [
                'reference' => $trans_no,
                'bank_id' => $request->into_bank,
                'amount' => $total,
            ];

            DB::commit();
        } catch (\Exception $e) {
            return error_web_processor('Something went wrong! Please try again later');
        }

//
//        //set_create_parameters($created_at, $created_by, ...)
//        $post_data1 = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));
//
//        try {
//            DB::beginTransaction();
//            $customer = Customer::create($post_data1);
//
//            $post_data = [
//                'customer_id' => $customer->id,
//                'f_name' => $request->first_name,
//                'l_name' => $request->last_name,
//                'short_name' => $request->short_name,
//                'branch' => $request->company,
//                'country' => $request->country,
//                'phone' => $request->phone,
//                'email' => $request->email,
//                'address' => $request->address,
//                'currency' => $request->currency,
//                'client_ref' => get_user_ref(),
//            ];
//
//            //set_create_parameters($created_at, $created_by, ...)
//            $post_data2 = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));
//
//
//            if ($created_at == null) {
//                //if not supervised, log data from create request
//                //Creator log
//                log_activity(
//                    ST_CUSTOMER_SETUP,
//                    $request->getClientIp(),
//                    'Create Customer and Branch',
//                    json_encode($post_data1),
//                    auth('user')->id(),
//                    $customer->id
//                );
//            }
//            CustomerBranch::create($post_data2);
//
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return error_web_processor($e);
//        }

        return success_web_processor(['id' => 1], __('messages.msg_saved_success', ['attribute' => __('messages.deposit')]));
    }
}
