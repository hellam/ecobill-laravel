<?php

namespace App\CentralLogics;

use App\Models\AuditTrail;
use App\Models\MakerCheckerRule;
use App\Models\PasswordHistory;
use App\Models\SecurityConfig;
use App\Rules\PasswordHistoryRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

function generateUniqueId($userId): string
{
    return md5(uniqid($userId, true));
}

function reloadCaptcha(): JsonResponse
{
    return response()->json(['captcha' => captcha_img('math')]);
}

function get_security_configs()
{
    return SecurityConfig::first();
}

function check_password_re_use($password): bool
{
    $password_policy_array = json_decode(get_security_configs()->password_policy, true);
    $password_history = PasswordHistory::where(['user_id' => auth('user')->id()])->limit($password_policy_array[3])->orderBy('created_at', 'DESC')->get();
    foreach ($password_history as $pass_history) {
        if (Hash::check($password, $pass_history->password)) {
            return false;
        }
    }
    return true;
}

function password_validation_rule($password_policy_array): array
{
    $password = Password::min($password_policy_array[1]);
    if ($password_policy_array[2] == 1) {
        $password = $password->letters()->numbers();
    } elseif ($password_policy_array[2] == 2) {
        $password = $password->letters()
            ->numbers()
            ->mixedCase()
            ->symbols()
            ->uncompromised();
    }

    return ['required', 'confirmed', $password, new PasswordHistoryRule($password_policy_array[3])];
}

function js_password_validation_rule($password_policy_array): string
{
    $output = 'a mix of ';
    if ($password_policy_array == 1) {
        $output .= 'digits and letters.';
    } elseif ($password_policy_array == 2) {
        $output .= 'digits, letters, special characters & not a common password.';
    }
    return $output;
}

function get_user_ref()
{
    return auth('user')->user()->uuid;
}

/**
 * Return a success JSON response.
 *
 * @param array|string $data
 * @param string $message
 * @param int|null $code
 * @return JsonResponse
 */
function success_api_processor($data, string $message = null, int $code = 200): JsonResponse
{
    return response()->json([
        'status' => 'Success',
        'message' => $message,
        'data' => $data
    ], $code);
}

function success_web_processor($data, string $message = null, int $code = 200): JsonResponse
{
    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data
    ], $code);
}

/**
 * @param string|null $message
 * @param int $code
 * @param $data
 * @return JsonResponse
 */
function error_api_processor(string $message = null, int $code = 200, $data = null): JsonResponse
{
    return response()->json([
        'status' => 'Error',
        'message' => $message,
        'data' => $data
    ], $code);
}

function error_web_processor(string $message = null, int $code = 200, $data = null): JsonResponse
{
    return response()->json([
        'status' => false,
        'message' => $message,
        'data' => $data
    ], $code);
}

function validation_error_processor($validator): array
{
    $err_keeper = [];
    foreach ($validator->errors()->getMessages() as $index => $error) {
        $err_keeper[] = ['field' => $index, 'error' => $error[0]];
    }
    return $err_keeper;
}

/**
 * @throws \Exception
 */
function getDateDifference($date1, $date2): string
{
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);

    $difference = $date1->diff($date2);
//        $diffInSeconds = $difference->s; //45
    $diffInMinutes = $difference->i; //23
    $diffInHours = $difference->h; //8
    $diffInDays = $difference->d; //21
    $diffInMonths = $difference->m; //4
    $diffInYears = $difference->y; //1
    $time = "Just Now";
    if ($diffInYears > 0)
        $time = $diffInYears . " Years ago";
    elseif ($diffInMonths > 0)
        $time = $diffInMonths . " Months ago";
    elseif ($diffInDays > 0)
        $time = $diffInDays . " Days ago";
    elseif ($diffInHours > 0)
        $time = $diffInHours . " Hours ago";
    elseif ($diffInMinutes > 0)
        $time = $diffInMinutes . " Minutes ago";

    return $time;
}

function check_permission($permission_code): bool
{
    $permissions = auth('user')->user()->permissions();
    if (is_array($permissions)) {
        if (in_array($permission_code, $permissions))
            return true;
    }

    return false;
}

function log_activity($type, $ip_address, $description, $request_details, $user = null, $trans_no = null, $api_token = null, $model = null): void
{
    try {
        AuditTrail::create([
            'type' => $type,
            'trans_no' => $trans_no,
            'user' => $user,
            'api_token' => $api_token,
            'description' => $description,
            'model' => $model,
            'request_details' => $request_details,
            'ip_address' => $ip_address,
            'client_ref' => get_user_ref(),
        ]);
    } catch (\Exception $e) {
    }
}

function array_equal($a, $b): bool
{
    return (
        is_array($a)
        && is_array($b)
        && count($a) == count($b)
        && array_diff($a, $b) === array_diff($b, $a)
    );
}

function is_account_locked(): bool
{
    if (Auth::guard('user')->user()->account_locked == 1)
        return true;
    return false;
}

function is_account_expired(): bool
{
    if (Auth::guard('user')->user()->account_expiry_date != null) {
        $expiry_date = Carbon::parse(Auth::guard('user')->user()->account_expiry_date);
        $now = Carbon::now();
        if ($now > $expiry_date)
            return true;
    }
    return false;
}

function is_first_time(): bool
{
    if (Auth::guard('user')->user()->first_time == 1)
        return true;
    return false;
}

function is_password_expired(): bool
{
    if (Auth::guard('user')->user()->password_expiry_date != null) {
        $expiry_date = Carbon::parse(Auth::guard('user')->user()->password_expiry_date);
        $now = Carbon::now();
        if ($now > $expiry_date)
            return true;
    }
    return false;
}

function requires_maker_checker($permission_code): ?int
{
    $maker_checker_rule = MakerCheckerRule::where('permission_code', $permission_code)->first();
    if ($maker_checker_rule)
        return $maker_checker_rule->maker_type;
    return null;
}

function checkif_has_any_permission($start, $end)
{
    $permissions = auth('user')->user()->permissions();
    $result = [];

    foreach ($permissions as $permission) {
        if ($permission >= $start && $permission <= $end) $result[] = $permission;
    }
    return count($result) > 0;
}
