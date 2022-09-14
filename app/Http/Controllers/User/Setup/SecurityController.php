<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\SecurityConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $audit_trail = AuditTrail::where('type', AUD_LOGON_EVENT)->orderBy('created_at','desc')->limit(5)->get();
        $security_configs = SecurityConfig::first();
        return view('user.setup.security', compact('audit_trail','security_configs'));
    }


}
