<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use App\Models\Tax;
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

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $taxes = Tax::all();
        $taxes_count = Tax::count() ?? 0;
        return view('user.setup.tax', compact('taxes','taxes_count'));
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
        $validator = UserValidators::taxCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'name' => $request->name,
            'description' => $request->description,
            'rate' => $request->rate,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $tax = Tax::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_TAX_SETUP,
                $request->getClientIp(),
                'Create Tax Setup',
                json_encode($post_data),
                auth('user')->id(),
                $tax->id
            );
        }

        return success_web_processor(['id' => $tax->id], __('messages.msg_saved_success', ['attribute' => __('messages.tax')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pay_terms = PaymentTerm::find($id);
        if (isset($pay_terms)) {
            return success_web_processor($pay_terms, __('messages.msg_item_found', ['attribute' => __('messages.pay_terms')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.pay_terms')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::payTermsUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $pay_terms = PaymentTerm::find($id);
        $pay_terms = set_update_parameters($pay_terms, $created_at, $created_by, $supervised_by, $supervised_at);

        $pay_terms->terms = $request->terms;
        $pay_terms->type = $request->type;
        $pay_terms->days = $request->days ?? 0;
        $pay_terms->update();

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.pay_terms')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): JsonResponse
    {
        $pay_terms = PaymentTerm::find($id);
        if (isset($pay_terms)) {
            $pay_terms->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.pay_terms')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.pay_terms')]));
    }
}
