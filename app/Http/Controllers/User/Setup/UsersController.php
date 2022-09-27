<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UsersController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = [];
        return view('user.setup.users', compact('users_count'));
    }
}
