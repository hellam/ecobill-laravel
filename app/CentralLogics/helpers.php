<?php

namespace App\CentralLogics;

use App\Models\AuditTrail;
use App\Models\MakerCheckerRule;
use App\Models\PasswordHistory;
use App\Models\SecurityConfig;
use App\Rules\PasswordHistoryRule;
use Illuminate\Http\Request;
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

function set_update_parameters($obj, $created_at, $created_by,
                               $supervised_by, $supervised_at)
{
    $obj->updated_by = auth('user')->user()->username;

    if ($created_at != null) {
        $obj->updated_at = $created_at;
    }
    if ($created_by != null) {
        $obj->updated_by = $created_by;
    }
    if ($supervised_by != null) {
        $obj->supervised_by = $supervised_by;
    }
    if ($supervised_at != null) {
        $obj->supervised_at = $supervised_at;
    }
    return $obj;
}

function set_create_parameters($created_at, $created_by,
                               $supervised_by, $supervised_at)
{
    $array = [];

    if ($created_at != null) {
        $array = array_merge($array, ['created_at' => $created_at]);
    }

    if ($created_by != null) {
        $array = array_merge($array, ['created_by' => $created_by]);
    } else {
        $array = array_merge($array, ['created_by' => auth('user')->user()->username]);
    }

    if ($supervised_by != null) {
        $array = array_merge($array, ['supervised_by' => $supervised_by]);
    }
    if ($supervised_at != null) {
        $array = array_merge($array, ['supervised_at' => $supervised_at]);
    }
    return $array;
}

function get_active_branch()
{
    return session('branch');
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

function error_web_processor(string $message = null, int $code = 200, $data = []): JsonResponse
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

function requires_maker_checker($permission_code): string|array
{
    $maker_checker_rule = MakerCheckerRule::with('permission')
        ->where(['permission_code' => $permission_code, 'inactive' => 0])
        ->first();

    if ($maker_checker_rule)
        return [
            $maker_checker_rule->maker_type,
            $maker_checker_rule->permission->maker_validator_function,
            $maker_checker_rule->permission->permission_group->name];
    return 'na';
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

function decode_form_data($data, $trx_type, $method)
{
    $output = 'Nothing to display!';
    if ($method == 'DELETE') {
        $id = $data['parameters']['id'];
        $edit_route = str_replace('delete', 'edit', $data['route']);
        $request = Request::create(route($edit_route, ['id' => $id]), 'GET', [
//            'name'=>Input::get('email'),
//            'password'=>Input::get('password')
        ]);

        $response = app()->handle($request);
        $response_data = json_decode($response->getContent(), true)['data'];

        $output = '<style>
           table,th,td,tr {
                border-top: 1px solid black;;
                border-collapse: collapse;
            }
        </style>';
        $output .= '<table style="width: 100%">';
        $output .= '<tr>';
        $output .= '<th>Fields</th>';
        $output .= '<th>Data</th>';
        $output .= '</tr>';
        foreach ($response_data as $key => $value) {
            $output .= '<tr>';
            $output .= '<td>' . $key . '</td>';
            $output .= '<td>';
            if (is_array($value))
                $output .= json_encode($value);
            else
                $output .= $value;
            $output .= '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';

        return $output;
    } elseif ($method == 'PUT') {
        $id = $data['parameters']['id'];
        $edit_route = str_replace('update', 'edit', $data['route']);
        $request = Request::create(route($edit_route, ['id' => $id]), 'GET', [
//            'name'=>Input::get('email'),
//            'password'=>Input::get('password')
        ]);

        $response = app()->handle($request);
        $response_data = json_decode($response->getContent(), true)['data'];

        $output = '<style>
           table,th,td,tr {
                border-top: 1px solid black;;
                border-collapse: collapse;
            }
        </style>';
        $output .= '<table style="width: 100%">';
        $output .= '<tr>';
        $output .= '<th>Fields</th>';
        $output .= '<th>Old</th>';
        $output .= '<th>New</th>';
        $output .= '</tr>';
        foreach ($data['inputs'] as $key => $value) {
            $output .= '<tr>';
            $output .= '<td>' . $key . '</td>';
            $output .= '<td>';
            if (is_array($value))
                $output .= implode(",", $value);
            else
                $output .= $value;
            $output .= '</td>';
            $output .= '<td>';
            if (key_exists($key, $response_data)) {
                if (is_array($response_data[$key]))
                    $output .= json_encode($response_data[$key]);
                else
                    $output .= $response_data[$key];
            }
            $output .= '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';
        return $output;
    } elseif ($method == 'POST') {
        $output = '<style>
           table,th,td,tr {
                border-top: 1px solid black;;
                border-collapse: collapse;
            }
        </style>';
        $output .= '<table style="width: 100%">';
        $output .= '<tr>';
        $output .= '<th>Fields</th>';
        $output .= '<th>Data</th>';
        $output .= '</tr>';
        foreach ($data['inputs'] as $key => $value) {
            $output .= '<tr>';
            $output .= '<td>' . $key . '</td>';

            $output .= '<td>';
            if (is_array($value))
                $output .= json_encode($value);
            else
                $output .= $value;
            $output .= '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';
        return $output;
    }

    return $output;

}
