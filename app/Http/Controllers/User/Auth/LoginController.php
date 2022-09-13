<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => 'logout']);
    }

    public function index()
    {
        return view('user.auth.login');
    }
    public function index1(Request $request)
    {
        Toastr::warning(__('messages.msg_kicked_out'));
        return view('user.auth.login');
    }

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
            Toastr::success(trans('messages.msg_login_success'), trans('messages.welcome').'!', ["positionClass" => "toast-top-right"]);
            try {
                auth('user')->logoutOtherDevices($request->password);
            } catch (AuthenticationException $e) {
            }
            return redirect()->route('user.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([trans('auth.failed')]);
    }


    public function logout(Request $request)
    {
        auth()->guard('user')->logout();
        return redirect()->route('user.auth.login');
    }

}
