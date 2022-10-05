<?php

namespace App\Http\Middleware;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\User\Utils\MakerCheckerTrxController;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Yoeunes\Toastr\Facades\Toastr;

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
            $permission = Permission::where('code', $permission_code)->first();
            if ($permission) {
                if ($permission->requires_hq)
                    if (!session('branch_is_main')) {
                        if ($request->getMethod() == "GET") {
                            abort(404);
                        }
                        return error_web_processor(__('messages.msg_no_permission'));
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

        if ($request->getMethod() == "GET") {
            Toastr::warning(__('messages.msg_no_permission'),__('messages.access_denied'));
            return back();
        }
        return error_web_processor(__('messages.msg_no_permission'));
    }
}
