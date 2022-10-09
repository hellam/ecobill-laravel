<?php

namespace App\Http\Controllers\User\Banking;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $fx_count = ExchangeRate::count() ?? 0;
        $currency = Currency::where('abbreviation', '!=', session('currency'))->get();
        $exchangeRates = ExchangeRate::with('curr')->get();
        return view('user.banking_gl.fx_rates', compact('fx_count', 'currency', 'exchangeRates'));
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
        $validator = UserValidators::fxCreateUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'currency' => $request->currency,
            'buy_rate' => $request->buy_rate,
            'sell_rate' => $request->sell_rate,
            'date' => $request->date,
            'branch_id' => session('branch'),
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $fx = ExchangeRate::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_EXCHANGE_RATE_SETUP,
                $request->getClientIp(),
                'Create exchange rate',
                json_encode($post_data),
                auth('user')->id(),
                $fx->id
            );
        }

        return success_web_processor(['id' => $fx->id], __('messages.msg_saved_success', ['attribute' => __('messages.fx')]));
    }

    public function get_fx_rate(Request $request)
    {
        $validator = UserValidators::fxRateGetValidation($request);

        if ($validator != '') {
            return error_web_processor(__('messages.msg_invalid_cry'));
        }

        return success_web_processor(['fx_rate' => getFxRate($request->from, $request->to)], __('messages.msg_item_found', ['attribute' => __('messages.fx')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fx = ExchangeRate::find($id);
        if (isset($fx)) {
            return success_web_processor($fx, __('messages.msg_item_found', ['attribute' => __('messages.fx')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.fx')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::fxCreateUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $fx = ExchangeRate::find($id);
        $fx = set_update_parameters($fx, $created_at, $created_by, $supervised_by, $supervised_at);

        $fx->currency = $request->currency;
        $fx->buy_rate = $request->buy_rate;
        $fx->sell_rate = $request->sell_rate;
        $fx->date = $request->date;
        $fx->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.fx')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): JsonResponse
    {
        $fx = ExchangeRate::find($id);
        if (isset($fx)) {
            $fx->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.fx')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.fx')]));
    }
}
