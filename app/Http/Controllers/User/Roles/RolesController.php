<?php

namespace App\Http\Controllers\User\Roles;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index()
    {
        $permissions = PermissionGroup::with('permissions')->get();
        return $permissions;
    }
}
