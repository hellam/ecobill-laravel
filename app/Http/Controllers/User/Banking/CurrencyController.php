<?php

namespace App\Http\Controllers\User\Banking;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(): Factory|View|Application
    {
        $currency_count = Currency::count();
        return view('user.banking_gl.currency_fx', compact('currency_count'));
    }
}
