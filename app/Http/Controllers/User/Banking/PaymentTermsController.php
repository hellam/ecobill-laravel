<?php

namespace App\Http\Controllers\User\Banking;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\PaymentTerm;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class PaymentTermsController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.banking_gl.pay_terms');
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
        $validator = UserValidators::payTermsCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'name' => $request->namespace,
            'type' => $request->type,
            'days' => $request->days,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $pay_terms = PaymentTerm::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_PAYMENT_TERMS_SETUP,
                $request->getClientIp(),
                'Create Payment Term',
                json_encode($post_data),
                auth('user')->id(),
                $pay_terms->id
            );
        }

        return success_web_processor(['id' => $pay_terms->id], __('messages.msg_saved_success', ['attribute' => __('messages.pay_terms')]));
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
