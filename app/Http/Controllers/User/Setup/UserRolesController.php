<?php

namespace App\Http\Controllers\user\setup;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    public function index(): Factory|View|Application
    {
        $user_roles_count = BranchUser::all() ?? 0;
        return view('user.setup.user_roles');
    }
}
