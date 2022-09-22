<?php

namespace App\CentralLogics;

use App\Http\Controllers\Controller;
use App\Models\MakerCheckerRule;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class UserValidators
{
    public static function makerCheckerRuleCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'action' => 'required|unique:' . MakerCheckerRule::class . ',permission_code,NULL,id,client_ref,' . get_user_ref(),
            'maker_type' => 'required|in:0,1',
        ]);
    }

    public static function makerCheckerRuleUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'action' => 'required|unique:' . MakerCheckerRule::class . ',permission_code,' . $id . ',id,client_ref,' . get_user_ref(),
            'maker_type' => 'required|in:0,1',
            'inactive' => 'required|in:0,1',
        ]);
    }

    public static function rolesUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Role::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'permissions' => 'required|array',
        ]);
    }

    public static function rolesCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Role::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'permissions' => 'required|array',
        ]);
    }

    public static function securityUpateValidation(Request $request)
    {
        $type = Route::current()->type;
        $rules = [
            'max_login' => 'required',
            'single_sign' => 'required',
        ];

        if ($type == 'password_policy')
            $rules = [
                'pass_expiry' => 'required',
                'min_length' => 'required',
                'strength' => 'required',
                'pass_history' => 'required',
                'first_time' => 'required',
            ];

        return self::ValidatorMake($request->all(), $rules);
    }

    public static function passwordUpdateValidation(Request $request)
    {
        $password_policy_array = json_decode(get_security_configs()->password_policy, true);
        $array = [
            'old_password' => base64_decode($request->old_password),
            'new_password' => base64_decode($request->new_password),
            'new_password_confirmation' => base64_decode($request->new_password_confirmation)
        ];

        $validator = self::ValidatorMake($array, [
            'old_password' => 'required',
            'new_password' => password_validation_rule($password_policy_array),
        ]);

        if ($validator != '') {
            return $validator;
        } elseif (!Auth::validate(['email' => auth('user')->user()->email, 'password' => $array['old_password']])) {
            return error_web_processor(__('messages.field_correction'),
                200, array(['field' => 'old_password', 'error' => 'Wrong Password!']));
        }

        return '';
    }


    public static function ValidatorMake(array $request_array, array $rules)
    {
        $validator = Validator::make($request_array, $rules);
        if ($validator->fails()) {
            return error_web_processor(__('messages.field_correction'),
                200, validation_error_processor($validator));
        } else
            return '';
    }
}