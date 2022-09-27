<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserRolesController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = User::where('created_by', '!=', 'system')->count() ?? 0;
        return view('user.setup.user_roles', compact('users_count'));
    }


    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $users = BranchUser::with(['user','branch','role'])
            ->orderBy('user', 'desc');
        return (new DataTables)->eloquent($users)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "delete_url" => route('user.setup.user_role.delete', [$row->id])
                ];
            })->addColumn('user', function ($row) {
                return $row->user->username;
            })->addColumn('branch', function ($row) {
                return $row->branch->name;
            })->addColumn('role', function ($row) {
                return $row->role->name;
            })
            ->make(true);
    }

    public function create()
    {

    }
    public function destroy()
    {

    }
}
