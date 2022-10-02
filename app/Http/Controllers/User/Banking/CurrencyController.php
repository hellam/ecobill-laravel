<?php

namespace App\Http\Controllers\User\Banking;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Scopes\BranchScope;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class CurrencyController extends Controller
{
    public function index(): Factory|View|Application
    {
        $currency_count = 0;
        $fx_rate = 0;
        return view('user.banking_gl.currency_fx', compact('currency_count', 'fx_rate'));
    }


    /**
     * @param Request $request
     * @param null $created_at
     * @param null $created_by
     * @param null $supervised_by
     * @param null $supervised_at
     * @return JsonResponse
     */

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::currencyCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'abbreviation' => $request->abbreviation,
            'name' => $request->name,
            'country' => $request->country,
            'symbol' => $request->symbol,
            'hundredths_name' => $request->hundredths_name,
            'auto_fx' => $request->auto_fx,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $currency = Currency::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_CURRENCY_SETUP,
                $request->getClientIp(),
                'Create new Currency',
                json_encode($post_data),
                auth('user')->id(),
                $currency->id
            );
        }

        return success_web_processor(['id' => $currency->id], __('messages.msg_saved_success', ['attribute' => __('messages.currency')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $currency = Currency::find($id);
        if (isset($currency)) {
            return success_web_processor($currency, __('messages.msg_item_found', ['attribute' => __('messages.bank_account')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.bank_account')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::bankAccountsUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $bank_account = BankAccount::withoutGlobalScope(BranchScope::class)
            ->with('chart_account')
            ->with('charge_chart_account')
            ->with('branch')
            ->find($id);
        $bank_account = set_update_parameters($bank_account, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $bank_account->account_name = $request->account_name;
        $bank_account->account_number = $request->account_number;
        $bank_account->entity_name = $request->entity_name;
        $bank_account->entity_address = $request->entity_address;
        $bank_account->charge_chart_code = $request->charge_chart_code;
        $bank_account->inactive = $request->inactive;
        $bank_account->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.bank_account')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): JsonResponse
    {
        $bank_account = BankAccount::withoutGlobalScope(BranchScope::class)
            ->with('chart_account')
            ->with('charge_chart_account')
            ->with('branch')
            ->find($id);
        if (isset($bank_account)) {
            //TODO: check if the bank account has transactions
//            $chart_account = ChartAccount::where('account_group', $id)->count();
//            if ($chart_account > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.gl_group'), 'attribute1' => __('messages.gl_account')]));
//            }
            $bank_account->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.bank_account')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.bank_account')]));
    }
}
