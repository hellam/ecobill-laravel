<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yoeunes\Toastr\Facades\Toastr;

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
            if (self::is_account_expired()) {
                self::logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_expired')]);
            } elseif (self::is_account_locked()) {
                self::logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_locked')]);
            } elseif (self::is_first_time()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_factory_password'),'Alert!');
                return redirect()->route('user.auth.new_password');
            }elseif (self::is_password_expired()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_expired_password'),'Alert!');
                return redirect()->route('user.auth.new_password');
            }
        }

        return $next($request);
    }

    public function is_account_locked(): bool
    {
        if (Auth::guard('user')->user()->account_locked == 1)
            return true;
        return false;
    }

    private function is_account_expired(): bool
    {
        if (Auth::guard('user')->user()->account_expiry_date != null) {
            $expiry_date = Carbon::parse(Auth::guard('user')->user()->account_expiry_date);
            $now = Carbon::now();
            if ($now > $expiry_date)
                return true;
        }
        return false;
    }

    private function is_first_time(): bool
    {
        if (Auth::guard('user')->user()->first_time == 1)
            return true;
        return false;
    }

    private function is_password_expired(): bool
    {
        if (Auth::guard('user')->user()->password_expiry_date != null) {
            $expiry_date = Carbon::parse(Auth::guard('user')->user()->password_expiry_date);
            $now = Carbon::now();
            if ($now > $expiry_date)
                return true;
        }
        return false;
    }

    public function logout(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        auth()->guard('user')->logout();
    }
}
