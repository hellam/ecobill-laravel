<?php

namespace App\CentralLogics;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\MakerCheckerRule;
use App\Models\Role;
use App\Models\User;
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

    //Begin Branches
    public static function branchCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Branch::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'email' => 'required',
            'phone' => 'required',
            'tax_no' => 'required',
            'tax_period' => 'required',
            'default_currency' => 'required',
            'default_bank_account' => 'required',
            'fiscal_year' => 'required',
            'timezone' => 'required',
            'address' => 'required',
        ]);
    }

    public static function branchUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Branch::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'email' => 'required',
            'phone' => 'required',
            'tax_no' => 'required',
            'tax_period' => 'required',
            'default_currency' => 'required',
            'default_bank_account' => 'required',
            'fiscal_year' => 'required',
            'timezone' => 'required',
            'address' => 'required',
        ]);
    }

    //End Branches

    //Begin Users
    public static function userCreateValidation(Request $request)
    {
        $password_policy_array = json_decode(get_security_configs()->password_policy, true);
        $array = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'password_confirmation' => $request->password,
            'full_name' => $request->full_name,
        ];

        return self::ValidatorMake($array, [
            'username' => 'required|unique:' . User::class . ',username,NULL,id,uuid,' . get_user_ref(),
            'email' => 'required|email|unique:' . User::class . ',email,NULL,id,uuid,' . get_user_ref(),
            'phone' => 'required|unique:' . User::class . ',phone,NULL,id,uuid,' . get_user_ref(),
            'password' => password_validation_rule($password_policy_array, true),
            'full_name' => 'required',
        ]);
    }

    public static function userUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        $password_policy_array = json_decode(get_security_configs()->password_policy, true);
        $rule = [
            'email' => 'required|unique:' . User::class . ',email,' . $id . ',id,uuid,' . get_user_ref(),
            'phone' => 'required|unique:' . User::class . ',phone,' . $id . ',id,uuid,' . get_user_ref(),
            'full_name' => 'required',
        ];

        $array = [
            'email' => $request->email,
            'phone' => $request->phone,
            'full_name' => $request->full_name,
        ];

        if ($request->filled('password')) {
            $rule = array_merge($rule, [
                'password' =>
                    password_validation_rule($password_policy_array, true)
            ]);
            $array = array_merge($array,
                [
                    'password' => $request->password,
                    'password_confirmation' => $request->password
                ]);
        }
        return self::ValidatorMake($array, $rule);
    }
    //End Users


    //Start User Roles
    public static function userRoleCreateValidation(Request $request)
    {
        $validator =  self::ValidatorMake($request->all(), [
            'user' => 'required',
            'branch' => 'required',
            'role' => 'required',
        ]);
        $params = ['user_id' => $request->user,
            'role_id' => $request->role,
            'branch_id' => $request->branch];
        if ($validator != '') {
            return $validator;
        } elseif (BranchUser::where($params)->first()) {
            return error_web_processor(__('messages.role_already_assigned'));
        }

        return '';
    }

    public static function securityUpdateValidation(Request $request)
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

    public static function generalSettingsUpdateValidation(Request $request)
    {
        $type = Route::current()->tab;

        $rules = [
            'logo' => 'string',
            'company_name' => 'required',
            'inv_footer' => 'required',
        ];
        if ($type == 'sms')
            $rules = [
                'provider' => 'required',
            ];
        elseif ($type == 'email')
            $rules = [
                'security' => 'required',
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
