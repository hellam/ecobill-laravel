<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BankAccountController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.banking_gl.accounts.account_maintenance');
    }

}
