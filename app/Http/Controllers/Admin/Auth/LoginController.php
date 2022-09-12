<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha'
        ],[
                'captcha.captcha' => 'Invalid captcha'
            ]
        );

        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            Toastr::success(trans('messages.login') . ' ' . trans('messages.successful'), trans('messages.welcome'), ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([trans('auth.failed')]);
    }


    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}
