<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BusinessSettingsController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.setup.business_settings');
    }
}
