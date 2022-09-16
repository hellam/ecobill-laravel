<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\{
    is_account_expired,
    is_account_locked,
    is_first_time,
    is_password_expired
};

class AccountSecurityMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('user')->check()) {
            if (is_account_expired()) {
                self::logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_expired')]);
            } elseif (is_account_locked()) {
                self::logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_locked')]);
            } elseif (is_first_time()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_factory_password'),'Alert!');
                return redirect()->route('user.auth.new_password');
            }elseif (is_password_expired()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_expired_password'),'Alert!');
                return redirect()->route('user.auth.new_password');
            }
        }

        return $next($request);
    }

    public function logout(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        auth()->guard('user')->logout();
    }
}
