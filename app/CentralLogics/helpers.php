<?php

use App\Models\AuditTrail;
use App\Models\BusinessSetting;
use App\Models\ExchangeRate;
use App\Models\MakerCheckerRule;
use App\Models\PasswordHistory;
use App\Models\Ref;
use App\Models\SecurityConfig;
use App\Rules\PasswordHistoryRule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;

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

function password_validation_rule($password_policy_array, $is_new_user = false): array
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

    return ['required', 'confirmed', $password, $is_new_user ? 'string' : new PasswordHistoryRule($password_policy_array[3])];
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


function logout(Request $request): void
{
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    auth()->guard('user')->logout();
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
            'branch_id' => get_active_branch(),
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

function get_file_url($folder, $filename = '')
{
    try {
        return route('user.files',
            [
                'folder' => $folder,
                'fileName' => $filename ?? 'null'
            ]
        );
    } catch (\Exception $e) {
        return '';
    }
}

function store_base64_image($requestImage, $fileName, $folderName)
{
    $old_fileName = $fileName; //store old file name so that we can try to delete it
    $image = get_base64_image($requestImage)['image'];
    $fileName = get_base64_image($requestImage)['fileName'];

    Storage::put('public/' . $folderName . '/' . $fileName, base64_decode($image));

    // open an image file
    $img = Image::make(storage_path('app/public/' . $folderName . '/' . $fileName));

    // now you are able to resize the instance
    $img->orientate()
        ->fit(400, 400, function ($constraint) {
            $constraint->upsize();
        })->save(storage_path('app/public/' . $folderName . '/' . $fileName), 60);

    delete_file($folderName, $old_fileName);

    return $fileName;
}

function delete_file($folderName, $old_fileName)
{
    //find if file exists
    $path = storage_path('app/public/' . $folderName . '/' . $old_fileName);
    //if so, then unlink from storage
    if (File::exists($path)) {
        try {
            unlink($path);
        } catch (\Exception $e) {
        }
    }
    return '';
}

function get_base64_image($base64_image): array
{
    $extension = explode('/', explode(':', substr($base64_image, 0, strpos($base64_image, ';')))[1])[1];   // .jpg .png .pdf
    //find substring from replace here eg: data:image/png;base64,
    $replace = substr($base64_image, 0, strpos($base64_image, ',') + 1);
    $image = str_replace($replace, '', $base64_image);
    $image = str_replace(' ', '+', $image);
    $fileName = md5(uniqid(auth::user()->id, true)) . '.' . $extension;
    return ['image' => $image, 'fileName' => $fileName];
}


function is_account_locked(): bool
{
    if (Auth::guard('user')->user()->account_locked == 1)
        return true;
    return false;
}

function is_account_inactive(): bool
{
    if (Auth::guard('user')->user()->inactive == 1)
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
            $maker_checker_rule->permission->permission_group->name,
        ];
    return 'na';
}

function get_company_default_currency()
{
    $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()->value, true);

    return $general_settings['default_currency'] ?? 'USD';
}

function get_company_setting($key_type)
{
    $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()->value, true);

    return $general_settings[$key_type] ?? 'USD';
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

function getFxRate($from, $to, $date = null)
{
    $date = $date == null ? Carbon::now() : $date;

    if ($from == $to)
        return toRateDecimal(1);

    $fx = ExchangeRate::where('currency', $to)
        ->whereDate('date', '>=', $date)
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->first();
    return toRateDecimal($fx->buy_rate ?? 1);
//
//    if ($from == session('currency'))//direct conversion: use buy rate
//    {
//        $fx = ExchangeRate::where('currency', $to)
//            ->where('date', '<=', $date)
//            ->orderBy('date', 'desc')
//            ->orderBy('id', 'desc')
//            ->first();
//        return toRateDecimal($fx->sell_rate ?? 1);
//    } elseif ($to == session('currency')) {//direct conversion: use sell rate
//        $fx = ExchangeRate::where('currency', $from)
//            ->where('date', '<=', $date)
//            ->orderBy('date', 'desc')
//            ->orderBy('id', 'desc')
//            ->first();
//        return toRateDecimal($fx->buy_rate ?? 1);
//    } else {//convert to home currency (sell_rate) and convert to second currency (buy_rate)
//
//        $fx_from = ExchangeRate::where('currency', $from)
//            ->where('date', '<=', $date)
//            ->orderBy('date', 'desc')
//            ->orderBy('id', 'desc')
//            ->first();
//
//        $fx_to = ExchangeRate::where('currency', $to)
//            ->where('date', '<=', $date)
//            ->orderBy('date', 'desc')
//            ->orderBy('id', 'desc')
//            ->first();
//        $final_rate = ($fx_from->sell_rate ?? 1) * ($fx_to->sell_rate ?? 1);
//
//        return toRateDecimal($final_rate);
//    }
}

function convert_currency_to_second_currency($amount, $fx_rate = null)
{
    return $amount * ($fx_rate ?? 1);
}

function generate_reff_no($type, $save = false, int $reference = null)
{
    $refno = Ref::where('type', (string)$type)->max('id');
    $refno = ($refno ?? 1000000) + 1;
    if ($reference == null) $reference = $refno;

    if ($save) {
        try {
            Ref::create([
                'id' => $reference,
                'type' => $type,
                'reference' => $reference,
                'client_ref' => get_user_ref(),
            ]);
        } catch (\Exception $e) {
            $refno = Ref::where(['type' => $type])->max('id');
            $refno = ($refno ?? 1000000) + 1;
            if ($reference == null) $reference = $refno;
            Ref::create([
                'id' => $reference,
                'type' => $type,
                'reference' => $reference,
                'client_ref' => get_user_ref(),
            ]);
        }
        return $refno;
    }

    return $reference;

}

function saveRefNo()
{

}

function number_suffix($number): string
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}
