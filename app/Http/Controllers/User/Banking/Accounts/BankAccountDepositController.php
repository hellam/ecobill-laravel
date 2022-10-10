<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
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
            return error_web_processor(json_encode(BankAccount::find($request->into_bank)));
        }

//        $post_data = [
//            'date' => $request->first_name,
//            'l_name' => $request->last_name,
//            'short_name' => $request->short_name,
//            'address' => $request->address,
//            'company' => $request->company,
//            'country' => $request->country,
//            'tax_id' => $request->tax_id == 'null' ? null : $request->tax_id,
//            'currency' => $request->currency,
//            'payment_terms' => $request->payment_terms,
//            'credit_limit' => $request->credit_limit,
//            'credit_status' => $request->credit_status,
//            'sales_type' => $request->sales_type,
//            'discount' => $request->discount,
//            'language' => $request->language,
//            'client_ref' => get_user_ref(),
//        ];
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

        return success_web_processor(['id' => 1], __('messages.msg_saved_success', ['attribute' => __('messages.customer')]));
    }
}
