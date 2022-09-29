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
use function App\CentralLogics\log_activity;
use function App\CentralLogics\requires_maker_checker;

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
    public function handle(Request $request, Closure $next, $permission_code, $trx_type = '')
    {
        if (Auth::guard('user')->check() && check_permission($permission_code)) {
            $maker_checker = requires_maker_checker($permission_code);

            if (is_array($maker_checker)) {
                if ($maker_checker[3])
                    if (!session('branch_is_main')) {
                        abort(404);
                    }
            }

            if (!$request->isMethod("GET") && is_array($maker_checker)) {
                if ($maker_checker[1] != null) {
                    $validator = app()->call([UserValidators::class, $maker_checker[1]]);
                    if ($validator != '') {
                        return $validator;
                    }
                }
                if (!$request->filled('remarks'))
                    return error_web_processor(
                        __('messages.msg_remarks_required'),
                        203
                    );
                return app()
                    ->call([MakerCheckerTrxController::class, 'create'],
                        [
                            'mc_type' => $maker_checker[0],
                            'module' => $maker_checker[2],
                            'trx_type' => $trx_type,
                        ]);
            }

            //Log all put/delete requests that don't need supervision
            if ($request->getMethod() == "PUT") {
                log_activity(
                    $trx_type,
                    $request->getClientIp(),
                    'Update',
                    json_encode($request->all()),
                    auth('user')->id(),
                    Route::current()->id
                );
            } elseif ($request->getMethod() == "DELETE") {
                log_activity(
                    $trx_type,
                    $request->getClientIp(),
                    'Delete',
                    "",
                    auth('user')->id(),
                    Route::current()->id
                );
            }
            return $next($request);
        }

        Toastr::warning(__('messages.access_denied'));
        return back();
    }
}
