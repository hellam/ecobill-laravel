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
        $this->middleware('guest:user', ['except' => 'logout']);
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
        $request->password = encrypt($request->password);

        if (auth('user')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            Toastr::success(trans('messages.msg_login_success'), trans('messages.welcome') . '!', ["positionClass" => "toast-top-right"]);
            log_activity(
                AUD_LOGON_EVENT,
                $request->getClientIp(),
                trans('messages.msg_login_success'),
                "",
                auth('user')->id()
            );
            try {
                auth('user')->logoutOtherDevices($request->password);
            } catch (AuthenticationException $e) {
            }
            return redirect()->route('user.dashboard');
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
        $security_array = json_decode($security_configs->password_policy,true);
        return view('user.auth.passwords.new_password', compact('security_array'));
    }

}
