<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\ChartAccount;
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
use function App\CentralLogics\success_web_processor;

class BankAccountController extends Controller
{
    public function index(): Factory|View|Application
    {
        $account_count = BankAccount::withoutGlobalScope(BranchScope::class)->count();
        $bank_accounts = BankAccount::withoutGlobalScope(BranchScope::class)
            ->with('chart_account')
            ->get();
        $currency = Currency::all();
        $gl_accounts = ChartAccount::all();
        $branches = Branch::all();
        return view('user.banking_gl.accounts.account_maintenance', compact(
            'account_count', 'currency', 'branches', 'gl_accounts', 'bank_accounts'));
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
        $validator = UserValidators::bankAccountsCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'entity_name' => $request->entity_name,
            'entity_address' => $request->entity_address,
            'currency' => $request->currency,
            'chart_code' => $request->chart_code,
            'charge_chart_code' => $request->charge_chart_code,
            'branch_id' => $request->branch_id,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $bank_account = BankAccount::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_BANK_ACCOUNT_SETUP,
                $request->getClientIp(),
                'Create Bank Account',
                json_encode($post_data),
                auth('user')->id(),
                $bank_account->id
            );
        }

        return success_web_processor(['id' => $bank_account->id], __('messages.msg_saved_success', ['attribute' => __('messages.bank_account')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bank_account = ChartAccount::where('id', $id)->first();
        if (isset($bank_account)) {
            return success_web_processor($bank_account, __('messages.msg_item_found', ['attribute' => __('messages.gl_account')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.gl_account')]),200,$chart_account);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::glAccountsUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $chart_account = ChartAccount::find($id);
        $chart_account = set_update_parameters($chart_account, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $chart_account->account_code = $request->account_code;
        $chart_account->account_name = $request->account_name;
        $chart_account->account_group = $request->account_group;
        $chart_account->inactive = $request->inactive;
        $chart_account->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.gl_account')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): JsonResponse
    {
        $chart_account = ChartAccount::find($id);
        if (isset($chart_account)) {
            //TODO: check if the chart account has transactions
//            $chart_account = ChartAccount::where('account_group', $id)->count();
//            if ($chart_account > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.gl_group'), 'attribute1' => __('messages.gl_account')]));
//            }
            $chart_account->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.gl_account')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.gl_account')]));
    }

}
