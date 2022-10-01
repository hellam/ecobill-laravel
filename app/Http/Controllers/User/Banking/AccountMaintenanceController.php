<?php

namespace App\Http\Controllers\User\Banking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountMaintenanceController extends Controller
{
    public function index()
    {
        return view('user.banking_gl.account_maintenance');
    }
}
