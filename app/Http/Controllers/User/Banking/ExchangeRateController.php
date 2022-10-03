<?php

namespace App\Http\Controllers\User\Banking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $fx_rates_count = 0;
        return view('user.banking_gl.fx_rates', compact('fx_rates_count'));
    }
}
