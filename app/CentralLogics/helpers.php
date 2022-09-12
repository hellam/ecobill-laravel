<?php

namespace App\CentralLogics;

use Illuminate\Http\JsonResponse;

function generateUniqueId($userId): string{
    return md5(uniqid($userId, true));
}

function reloadCaptcha(): JsonResponse
{
    return response()->json(['captcha'=> captcha_img('math')]);
}
function get_user_ref(){
    return null;
}
