<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = User::where('created_by', '!=', 'system')->count() ?? 0;
        return view('user.setup.user_roles', compact('users_count'));
    }
}
