<?php

namespace App\Http\Middleware;

use App\Models\BranchUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
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
            $branch_user = BranchUser::with('branch')
                ->whereHas('branch', function ($q) {
                    $q->where('inactive', 0);
                })->where(['branch_id' => get_active_branch(), 'user_id' => auth('user')->id()])
                ->first();
            if (!$branch_user) {
                logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_no_branch_assigned')]);
            } elseif (is_account_expired()) {
                logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_expired')]);
            } elseif (is_account_locked()) {
                logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.msg_account_locked')]);
            } elseif (is_account_inactive()) {
                logout($request);
                return redirect()->route('user.auth.login')->withErrors([trans('messages.user_deactivated')]);
            } elseif (is_first_time()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_factory_password'), 'Alert!');
                return redirect()->route('user.auth.new_password');
            } elseif (is_password_expired()) {//ask user to change their password
                Toastr::warning(trans('messages.msg_change_expired_password'), 'Alert!');
                return redirect()->route('user.auth.new_password');
            }

            Session::put('branch_name', $branch_user->branch->name);
            Session::put('branch_is_main', $branch_user->branch->is_main);
            Session::put('currency', $branch_user->branch->default_currency);
            Session::put('branch_bank', $branch_user->branch->bank_account);
            Session::put('branch_obj', serialize($branch_user->branch));
            Config::set('TIME_ZONE', $branch_user->branch->timezone);
        }

        return $next($request);
    }


}
