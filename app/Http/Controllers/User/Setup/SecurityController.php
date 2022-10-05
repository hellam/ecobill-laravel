<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\SecurityConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $audit_trail = AuditTrail::where('type', ST_LOGON_EVENT)->orderBy('created_at', 'desc')->limit(5)->get();
        $security_configs = SecurityConfig::first();
        return view('user.setup.security', compact('audit_trail', 'security_configs'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $type,
                                   $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::securityUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $sec_config = SecurityConfig::first();

        //set parameters
        $sec_config = set_update_parameters($sec_config, $created_at, $created_by,
            $supervised_by, $supervised_at);

        if ($type == 'general') {
            $configs[] = $request->max_login;
            $configs[] = $request->single_sign;

            $sec_config->general_security = $configs;
            $sec_config->update();
            return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.security')]));
        }

        $configs[] = $request->pass_expiry;
        $configs[] = $request->min_length;
        $configs[] = $request->strength;
        $configs[] = $request->pass_history;
        $configs[] = $request->first_time;

        $sec_config->password_policy = $configs;
        $sec_config->update();
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.security')]));
    }
}
