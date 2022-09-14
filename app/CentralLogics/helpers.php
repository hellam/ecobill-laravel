<?php

namespace App\CentralLogics;

use App\Models\AuditTrail;
use App\Models\Role;
use DateTime;
use Illuminate\Http\JsonResponse;

function generateUniqueId($userId): string
{
    return md5(uniqid($userId, true));
}

function reloadCaptcha(): JsonResponse
{
    return response()->json(['captcha' => captcha_img('math')]);
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
            'user'=>$user,
            'api_token'=>$api_token,
            'description'=>$description,
            'model'=>$model,
            'request_details'=>$request_details,
            'ip_address'=>$ip_address,
        ]);
    }catch (\Exception $e) {}
}
