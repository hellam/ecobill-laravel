<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\check_permission;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\requires_maker_checker;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission_code)
    {
        if (Auth::guard('user')->check() && check_permission($permission_code)) {
            if (!$request->isMethod("GET") && requires_maker_checker($permission_code) != 'na') {
                return error_web_processor("Maker Checker required :" . requires_maker_checker($permission_code), 200, []);
            }
            return $next($request);
        }

        Toastr::warning(__('messages.access_denied'));
        return back();
    }
}
