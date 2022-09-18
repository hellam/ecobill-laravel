<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(): Factory|View|Application
    {
        $audit_trails = [];
        return view('user.reports.audit_trail', compact('audit_trails'));
    }
}
