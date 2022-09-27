<?php

namespace App\Http\Controllers\user\setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    public function index(): Factory|View|Application
    {
        $user_roles_count = BranchUser::all() ?? 0;
        return view('user.setup.user_roles');
    }
    public function index(): Factory|View|Application
    {
        $users_count = User::where('created_by', '!=', 'system')->count() ?? 0;
        return view('user.setup.users', compact('users_count'));
    }
}
