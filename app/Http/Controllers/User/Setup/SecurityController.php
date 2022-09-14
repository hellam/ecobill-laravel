<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\SecurityConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class SecurityController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $audit_trail = AuditTrail::where('type', AUD_LOGON_EVENT)->orderBy('created_at', 'desc')->limit(5)->get();
        $security_configs = SecurityConfig::first();
        return view('user.setup.security', compact('audit_trail', 'security_configs'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $type)
    {
        if ($type == 'general') {
            $validator = Validator::make($request->all(), [
                'max_login' => 'required',
                'single_sign' => 'required',
            ]);

            if ($validator->fails()) {
                return error_web_processor(__('messages.field_correction'),
                    200, validation_error_processor($validator));
            }

            $configs[] = $request->max_login;
            $configs[] = $request->single_sign;

            $sec_config = SecurityConfig::first();
            $sec_config->general_security = $configs;
            $sec_config->update();
            return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.security')]));
        } elseif ($type == 'password_policy') {
            $validator = Validator::make($request->all(), [
                'pass_expiry' => 'required',
                'min_length' => 'required',
                'combination' => 'required|array',
                'pass_history' => 'required',
                'first_time' => 'required',
            ]);


            if ($validator->fails()) {
                return error_web_processor(__('messages.field_correction'),
                    200, validation_error_processor($validator));
            }


            $configs[] = $request->pass_expiry;
            $configs[] = $request->min_length;
            $configs[] = $request->combination;
            $configs[] = $request->pass_history;
            $configs[] = $request->first_time;

            $sec_config = SecurityConfig::first();
            $sec_config->general_security = $configs;
            $sec_config->update();
            return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.security')]));
        }

        return error_web_processor(null, __('messages.update_error'));

    }
}
