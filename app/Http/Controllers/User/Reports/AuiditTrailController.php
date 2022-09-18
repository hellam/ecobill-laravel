<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AuiditTrailController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.reports.audit_trail');
    }
}
