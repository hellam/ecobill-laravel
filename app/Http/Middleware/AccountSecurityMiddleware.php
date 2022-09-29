<?php

namespace App\Http\Middleware;

use App\Models\BranchUser;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\{get_active_branch,
    is_account_expired,
    is_account_inactive,
    is_account_locked,
    is_first_time,
    is_password_expired,
    logout};

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
            $branch_user = BranchUser::with('branch:id,name')
                ->whereHas('branch', function ($q) {
                    $q->where('inactive', 0);
                })->where(['branch_id' => get_active_branch(),'user_id' => auth('user')->id()])
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
        }

        return $next($request);
    }
}
