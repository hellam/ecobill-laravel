<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
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


    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $users = BranchUser::with(['user','branch','role'])
            ->orderBy('user', 'desc');
        return (new DataTables)->eloquent($users)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.setup.users.edit', [$row->id]),
                    "update_url" => route('user.setup.users.update', [$row->id]),
//                    "delete_url" => route('user.setup.users.delete', [$row->id])
                ];
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->addColumn('last_visit', function ($row) {
                $login_log = AuditTrail::where('user', $row->id)
                    ->where('type', ST_LOGON_EVENT)
                    ->orderBy('created_at', 'desc')
                    ->first();
                return $login_log ? Carbon::parse($login_log->created_at)->format('Y/m/d H:i:s') : 'Never';
            })
            ->make(true);
    }

    public function create(): Factory|View|Application
    {

    }
}
