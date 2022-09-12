<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param String|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {

        switch ($guard) {
            case 'admin':
                if (Auth::guard($guard)->check()) {
                    //TODO: Redirect to admin default redirect
                    return redirect()->route('admin.dashboard');
                }
                break;
            case 'user':
                if (Auth::guard($guard)->check()) {
                    //TODO: Redirect to user default redirect
                    return redirect()->route('user.dashboard');
                }
                break;
            default:
                if (Auth::guard($guard)->check()) {
                    return response()->json([], 404);
                }
                break;
        }

        return $next($request);
    }
}
