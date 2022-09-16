<?php

namespace App\Http\Middleware;

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
            if ($this->is_account_locked()){
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                auth()->guard('user')->logout();
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_locked')]);
            }
        }

        return $next($request);
    }

    public function is_account_locked(): bool
    {
        if(Auth::guard('user')->user()->account_locked == 1)
            return true;
        return false;
    }
}
