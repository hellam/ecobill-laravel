<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\CentralLogics\is_account_inactive;

class UserMiddleware
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
        if (is_account_inactive()) {
            self::logout($request);
            return redirect()->route('user.auth.login')->withErrors([trans('messages.user_deactivated')]);
        }
            return $next($request);
        }

        return redirect()->route('user.auth.login');
    }

    public function logout(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        auth()->guard('user')->logout();
    }
}
