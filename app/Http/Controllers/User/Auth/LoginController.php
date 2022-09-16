<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityConfig;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\log_activity;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout', 'new_password');
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('user.auth.login');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index1(Request $request): View|Factory|Application
    {
        Toastr::warning(__('messages.msg_kicked_out'));
        return view('user.auth.login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha'
        ], [
                'captcha.captcha' => 'Invalid captcha'
            ]
        );

        if (auth('user')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            Toastr::success(trans('messages.msg_login_success'), trans('messages.welcome') . '!', ["positionClass" => "toast-top-right"]);
            //log success login action
            log_activity(
                AUD_LOGON_EVENT,
                $request->getClientIp(),
                trans('messages.msg_login_success'),
                "",
                auth('user')->id()
            );

            //reset failed login attempts
            if (auth('user')->user()->failed_login_attempts > 0) {
                $user = User::find(auth('user')->id());
                $user->failed_login_attempts = 0;
                $user->update();
            }

            //check if SSO is enabled and apply
            try {
                $security_configs = SecurityConfig::first();
                $general_security_array = json_decode($security_configs->general_security, true);

                if ($general_security_array[1] == 1)
                    auth('user')->logoutOtherDevices($request->password);
            } catch (\Exception $e) {
            }

            //redirect authenticated user
            return redirect()->route('user.dashboard');
        } elseif ($user = User::where('email', $request->email)->first()) {
            try {
                $security_configs = SecurityConfig::where('client_ref', $user->uuid)->first();

                $general_security_array = json_decode($security_configs->general_security, true);

                if ($user->failed_login_attempts < $general_security_array[0]) {//check if failed attempts are allowed
                    $user->failed_login_attempts += 1; //increment failed login attempts counter
                    $msg = __('messages.msg_login_failed') . ', ' . trans('messages.msg_login_attempts_remaining', ['attribute' => $general_security_array[0] - $user->failed_login_attempts]);
                    $log_msg = __('messages.msg_login_failed');
                    if ($user->failed_login_attempts >= $general_security_array[0]) {
                        $user->account_locked = 1;
                        $log_msg = $msg = __('messages.msg_account_locked');
                    }
                    $user->update();
                    log_activity(
                        AUD_LOGON_EVENT,
                        $request->getClientIp(),
                        $log_msg,
                        "",
                        $user->id
                    );
                    return redirect()->back()->withInput($request->only('email', 'remember'))
                        ->withErrors([$msg]);
                } elseif ($user->account_locked == 1) {//account locked
                    return redirect()->back()->withInput($request->only('email', 'remember'))
                        ->withErrors([__('messages.msg_account_locked')]);
                }
                $user = null;
            } catch (\Exception $e) {
            }
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([trans('auth.failed')]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        log_activity(
            AUD_LOGON_EVENT,
            $request->getClientIp(),
            trans('messages.msg_logout_success'),
            "",
            auth('user')->id()
        );
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        auth()->guard('user')->logout();
        return redirect()->route('user.auth.login');
    }

    /**
     * @return Application|Factory|View
     */
    public function new_password(): View|Factory|Application
    {
        $security_configs = SecurityConfig::first();
        $security_array = json_decode($security_configs->password_policy, true);
        return view('user.auth.passwords.new_password', compact('security_array'));
    }

}
