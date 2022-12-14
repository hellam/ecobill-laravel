<?php

namespace App\Http\Controllers\User\Banking;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(): Factory|View|Application
    {
        $currency_count = Currency::count();
        $currency = Currency::all();
        return view('user.banking_gl.currency',
            compact('currency_count', 'currency'));
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
//        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

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
            return success_web_processor($currency, __('messages.msg_item_found', ['attribute' => __('messages.currency')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.currency')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::currencyUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $currency = Currency::find($id);
//        $currency = set_update_parameters($currency, $created_at, $created_by, $supervised_by, $supervised_at);

        $currency->name = $request->name;
        $currency->country = $request->country;
        $currency->symbol = $request->symbol;
        $currency->hundredths_name = $request->hundredths_name;
        $currency->auto_fx = $request->auto_fx;
        $currency->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.currency')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): JsonResponse
    {
        $currency = Currency::find($id);
        if (isset($currency)) {
            //TODO: check if the currency has transactions or relations
//            $chart_account = ChartAccount::where('account_group', $id)->count();
//            if ($chart_account > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.gl_group'), 'attribute1' => __('messages.gl_account')]));
//            }
            $currency->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.currency')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.currency')]));
    }
}
