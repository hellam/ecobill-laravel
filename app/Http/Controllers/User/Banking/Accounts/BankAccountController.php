<?php

namespace App\Http\Controllers\User\Banking\Accounts;

use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    public function index()
    {
        return view('user.banking_gl.accounts.account_maintenance');
    }


}
