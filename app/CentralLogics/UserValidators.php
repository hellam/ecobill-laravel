<?php

namespace App\CentralLogics;

use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Category;
use App\Models\ChartAccount;
use App\Models\ChartClass;
use App\Models\ChartGroup;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
use App\Models\MakerCheckerRule;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\Ref;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
//            'default_currency' => 'required',
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
//            'default_currency' => 'required',
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
        $validator = self::ValidatorMake($request->all(), [
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

    //end user roles


    public static function glClassCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'class_name' => 'required|unique:' . ChartClass::class . ',class_name,NULL,id,client_ref,' . get_user_ref(),
        ]);
    }

    public static function glClassUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'class_name' => 'required|unique:' . ChartClass::class . ',class_name,' . $id . ',id,client_ref,' . get_user_ref(),
            'inactive' => 'in:1,0',
        ]);
    }

    public static function glGroupCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . ChartGroup::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'class_id' => 'required|exists:' . ChartClass::class . ',id',
        ]);
    }

    public static function glGroupUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . ChartGroup::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'class_id' => 'required|exists:' . ChartClass::class . ',id',
            'inactive' => 'in:1,0',
        ]);
    }

    public static function glAccountsCreateValidation(Request $request)
    {

        return self::ValidatorMake($request->all(), [
            'account_code' => 'required|unique:' . ChartAccount::class . ',account_code,NULL,id,client_ref,' . get_user_ref(),
            'account_name' => 'required|unique:' . ChartAccount::class . ',account_name,NULL,id,client_ref,' . get_user_ref(),
            'account_group' => 'required|exists:' . ChartGroup::class . ',id',
        ]);
    }

    public static function glAccountsUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'account_code' => 'required|unique:' . ChartAccount::class . ',account_code,' . $id . ',id,client_ref,' . get_user_ref(),
            'account_name' => 'required|unique:' . ChartAccount::class . ',account_name,' . $id . ',id,client_ref,' . get_user_ref(),
            'account_group' => 'required|exists:' . ChartGroup::class . ',id',
            'inactive' => 'in:1,0',
        ]);
    }

    public static function bankAccountsCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'account_name' => 'required|unique:' . BankAccount::class . ',account_name,NULL,id,client_ref,' . get_user_ref(),
            'account_number' => 'required|unique:' . BankAccount::class . ',account_number,NULL,id,client_ref,' . get_user_ref(),
            'currency' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
            'chart_code' => 'required|unique:' . BankAccount::class . ',chart_code,NULL,id,client_ref,' . get_user_ref()
                . '|exists:' . ChartAccount::class . ',account_code,client_ref,' . get_user_ref(),
            'charge_chart_code' => 'required|exists:' . ChartAccount::class . ',account_code,client_ref,' . get_user_ref(),
            'branch_id' => 'required|exists:' . Branch::class . ',id,client_ref,' . get_user_ref(),
        ]);
    }

    public static function bankAccountsUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'account_name' => 'required|unique:' . BankAccount::class . ',account_name,' . $id . ',id,client_ref,' . get_user_ref(),
            'account_number' => 'required|unique:' . BankAccount::class . ',account_number,' . $id . ',id,client_ref,' . get_user_ref(),
            'charge_chart_code' => 'required|exists:' . ChartAccount::class . ',account_code,client_ref,' . get_user_ref(),
            'inactive' => 'in:1,0',
        ]);
    }


    public static function currencyCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'abbreviation' => 'required|unique:' . Currency::class . ',abbreviation,NULL,id,client_ref,' . get_user_ref(),
            'name' => 'required|unique:' . Currency::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'country' => 'required|unique:' . Currency::class . ',country,NULL,id,client_ref,' . get_user_ref(),
            'symbol' => 'required',
            'hundredths_name' => 'required',
            'auto_fx' => 'in:1,0',
        ]);
    }

    public static function currencyUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Currency::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'country' => 'required|unique:' . Currency::class . ',country,' . $id . ',id,client_ref,' . get_user_ref(),
            'symbol' => 'required',
            'hundredths_name' => 'required',
            'auto_fx' => 'in:1,0',
        ]);
    }


    public static function fxCreateUpdateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'currency' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
            'buy_rate' => 'required',
            'sell_rate' => 'required',
            'date' => 'required|date_format:d/m/Y H:i:s',
        ]);
    }

    public static function fxRateGetValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'from' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
            'to' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
        ]);
    }

    public static function bankAccountsGetValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'currency' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
        ]);
    }

    public static function payTermsCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'terms' => 'required|unique:' . PaymentTerm::class . ',terms,NULL,id,client_ref,' . get_user_ref(),
            'type' => 'required|in:0,1,2',
            'days' => 'required_if:type,1,2',
        ]);
    }

    public static function payTermsUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'terms' => 'required|unique:' . PaymentTerm::class . ',terms,' . $id . ',id,client_ref,' . get_user_ref(),
            'type' => 'required|in:0,1,2',
            'days' => 'required_if:type,1,2',
        ]);
    }

    public static function taxCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Tax::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'description' => 'required',
            'rate' => 'required|numeric',
        ]);
    }

    public static function taxUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Tax::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'description' => 'required',
            'rate' => 'required',
        ]);
    }

    public static function categoryCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Category::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'description' => 'required',
//            'image' => 'string',
            'default_tax_id' => 'required|numeric',
        ]);
    }

    public static function categoryUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Category::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'description' => 'required',
            'default_tax_id' => 'required|numeric',
            'inactive' => 'in:1,0',
        ]);
    }

    public static function productCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'barcode' => 'required|unique:' . Product::class . ',barcode,NULL,id,client_ref,' . get_user_ref(),
            'name' => 'required|unique:' . Product::class . ',name,NULL,id,client_ref,' . get_user_ref(),
//            'image' => 'string',
            'description' => 'required',
            'price' => 'required',
            'cost' => 'required',
            'order' => 'required',
            'category_id' => 'required|exists:' . Category::class . ',id,client_ref,' . get_user_ref(),
            'tax_id' => 'required|numeric',
            'type' => 'required|numeric',
        ]);
    }

    public static function productUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'name' => 'required|unique:' . Product::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
//            'image' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'order' => 'required|numeric',
            'category_id' => 'required|exists:' . Category::class . ',id,client_ref,' . get_user_ref(),
            'tax_id' => 'required|numeric',
            'type' => 'required|numeric',//TODO: validate if type subscription has packages
            'inactive' => 'in:1,0',
        ]);
    }


    public static function subscriptionCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'product_id' => 'required',
            'name' => 'required|unique:' . Subscription::class . ',name,NULL,id,client_ref,' . get_user_ref(),
            'description' => 'required',
            'order' => 'required',
            'features' => 'required',
            'validity' => 'required'
        ]);
    }

    public static function subscriptionUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'product_id' => 'required',
            'name' => 'required|unique:' . Subscription::class . ',name,' . $id . ',id,client_ref,' . get_user_ref(),
            'description' => 'required',
            'order' => 'required',
            'features' => 'required',
            'validity' => 'required',
            'inactive' => 'in:1,0',
        ]);
    }

    public static function customerCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'short_name' => 'required|unique:' . Customer::class . ',short_name,NULL,id,client_ref,' . get_user_ref(),
            'country' => 'required',
            'tax_id' => 'required',
            'currency' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
            'payment_terms' => 'required|exists:' . PaymentTerm::class . ',id,client_ref,' . get_user_ref(),
            'credit_limit' => 'required',
            'credit_status' => 'required|in:0,1',
            'sales_type' => 'required',
            'discount' => 'required|numeric',
            'language' => 'required',
            'email' => 'required|unique:' . CustomerBranch::class . ',email,NULL,id,client_ref,' . get_user_ref() . '|email:rfc,dns',//TODO: Add spoof
            'phone' => 'required|unique:' . CustomerBranch::class . ',phone,NULL,id,client_ref,' . get_user_ref() . '|min:13|max:13',
        ]);
    }


    public static function customerUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        return self::ValidatorMake($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required',
            'customer_branch_id' => 'required|exists:' . CustomerBranch::class . ',id,client_ref,' . get_user_ref(),
            'tax_id' => 'required',
            'payment_terms' => 'required|exists:' . PaymentTerm::class . ',id,client_ref,' . get_user_ref(),
            'credit_limit' => 'required',
            'credit_status' => 'required|in:0,1',
            'sales_type' => 'required',
            'discount' => 'required|numeric',
            'language' => 'required',
            'inactive' => 'required|in:0,1',
            'email' => 'required|email:rfc,dns|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($id, 'customer_id'),//TODO: Add spoof
            'phone' => 'required|min:13|max:13|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($id, 'customer_id'),
        ]);
    }

    public static function customerBranchCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'customer_id' => 'required|exists:' . Customer::class . ',id,client_ref,' . get_user_ref(),
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'currency' => 'required|exists:' . Currency::class . ',abbreviation,client_ref,' . get_user_ref(),
            'short_name' => 'required|unique:' . CustomerBranch::class . ',short_name,NULL,id,client_ref,' . get_user_ref(),
            'country' => 'required',
            'email' => 'required|email:rfc,dns|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($request->customer_id, 'customer_id'),//TODO: Add spoof
            'phone' => 'required|min:13|max:13|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($request->customer_id, 'customer_id'),
        ]);
    }

    public static function customerBranchUpdateValidation(Request $request)
    {
        $id = Route::current()->id;
        $customer_id = CustomerBranch::findOrFail($id)->customer_id;
        return self::ValidatorMake($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required',
            'inactive' => 'required|in:0,1',
            'email' => 'required|email:rfc,dns|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($customer_id, 'customer_id'),//TODO: Add spoof
            'phone' => 'required|min:13|max:13|' . Rule::unique(CustomerBranch::class)->where(fn($query) => $query->where('client_ref', get_user_ref()))->ignore($customer_id, 'customer_id'),
        ]);
    }

    public static function accountDepositCreateValidation(Request $request)
    {
        return self::ValidatorMake($request->all(), [
            'date' => 'required|date_format:' . get_date_format(),
            'reference' => 'required|numeric|unique:' . Ref::class . ',reference,NULL,id,client_ref,' . get_user_ref() . ',type,' . ST_ACCOUNT_DEPOSIT,
            'from' => 'required|in:0,1',
//            'misc' => 'required_if:from,0',
            'customer_branch_id' => 'required_if:from,1|exists:' . CustomerBranch::class . ',id,client_ref,' . get_user_ref(),
            'into_bank' => 'required|exists:' . BankAccount::class . ',id,client_ref,' . get_user_ref(),
            'fx_rate' => Rule::requiredIf(fn() => (BankAccount::find($request->into_bank)?->currency != session('currency'))),
            'deposit_options' => 'required|array|min:1',
            'deposit_options.*.chat_code' => 'required|exists:' . ChartAccount::class . ',account_code,client_ref,' . get_user_ref(),
            'deposit_options.*.amount' => 'required|numeric|min:1',
        ], [
            'deposit_options.*.chat_code.required' => __('validation.required', ['attribute' => 'Chat Code']),
            'deposit_options.*.chat_code.exists' => __('validation.exists', ['attribute' => 'Chat Code']),
            'deposit_options.*.amount.required' => __('validation.required', ['attribute' => 'Amount']),
            'deposit_options.*.amount.numeric' => __('validation.numeric', ['attribute' => 'Amount']),
            'deposit_options.*.amount.min' => __('validation.min', ['attribute' => 'Amount']),
            'deposit_options.*' => "At least one deposit item is required",
        ]);
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


    public static function ValidatorMake(array $request_array, array $rules, $messages = [])
    {
        $validator = Validator::make($request_array, $rules, $messages);
        if ($validator->fails()) {
            return error_web_processor(__('messages.field_correction'),
                200, validation_error_processor($validator));
        } else
            return '';
    }
}
