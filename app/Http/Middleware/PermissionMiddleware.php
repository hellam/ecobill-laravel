<?php

namespace App\Http\Middleware;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\User\Utils\MakerCheckerTrxController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\check_permission;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\requires_maker_checker;
use function App\CentralLogics\success_web_processor;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param $permission_code
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission_code)
    {
        if (Auth::guard('user')->check() && check_permission($permission_code)) {
            $maker_checker = requires_maker_checker($permission_code);
            if (!$request->isMethod("GET") && is_array($maker_checker)) {
                if (!$request->has('remarks'))
                    return error_web_processor(
                        __('messages.msg_remarks_required'),
                        203
                    );
                if ($maker_checker[1] != null) {
                    $validator = app()->call([UserValidators::class, $maker_checker[1]]);
                    if ($validator != '') {
                        return $validator;
                    }
                }
                return app()->call([MakerCheckerTrxController::class, 'create'], ['mc_type' => $maker_checker[0],'module'=>$maker_checker[2]]);
            }
            return $next($request);
        }

        Toastr::warning(__('messages.access_denied'));
        return back();
    }
}
