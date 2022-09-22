<?php

namespace App\Http\Controllers\User\Auth;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Models\SecurityConfig;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_security_configs;
use function App\CentralLogics\is_first_time;
use function App\CentralLogics\is_password_expired;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout', 'new_password', 'update_password');
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
     * @return RedirectResponse
     */
    public function index1(Request $request)
    {
        Toastr::warning(__('messages.msg_kicked_out'));
        return redirect()->route('user.auth.login');
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
                ST_LOGON_EVENT,
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
                        ST_LOGON_EVENT,
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
            ST_LOGON_EVENT,
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

    public function update_password(Request $request): JsonResponse
    {

        $validator = UserValidators::passwordUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $user = User::where('id', Auth::id())->first();
        $password_policy_array = json_decode(get_security_configs()->password_policy, true);

        $user->password = Hash::make(base64_decode($request->new_password));
        if (is_first_time())
            $user->first_time = 0;
        if (is_password_expired()) {
            if ($password_policy_array[0] == 0)
                $user->password_expiry_date = null;
            else {
                $user->password_expiry_date = Carbon::now()->addDays($password_policy_array[0]);
            }
        }
        $user->update();

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
            'created_by' => $user->id,
            'last_updated_by' => $user->id,
        ]);

        log_activity(
            ST_ACCOUNT_MANAGEMENT,
            $request->getClientIp(),
            trans('messages.msg_password_updated'),
            "",
            auth('user')->id()
        );
        auth()->guard('user')->login($user);
        return success_web_processor(null, __('messages.msg_password_updated', ['attribute' => __('messages.password')]));
    }

}
