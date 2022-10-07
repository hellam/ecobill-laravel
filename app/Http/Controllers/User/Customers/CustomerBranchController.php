<?php

namespace App\Http\Controllers\User\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerBranchController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('users.customers.branch');
    }
}
