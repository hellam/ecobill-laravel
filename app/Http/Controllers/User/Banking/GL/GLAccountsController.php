<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\ChartClass;
use App\Models\ChartGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class GLAccountsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $gl_accounts_count = ChartAccount::count() ?? 0;
        $gl_groups_count = ChartGroup::count() ?? 0;
        $gl_classes_count = ChartClass::count() ?? 0;
        return view('user.banking_gl.gl_maintenance', compact('gl_accounts_count', 'gl_groups_count', 'gl_classes_count'));
    }


}
