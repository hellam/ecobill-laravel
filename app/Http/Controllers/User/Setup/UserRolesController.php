<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Role;
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
        $user_roles_count = BranchUser::count() ?? 0;
        $users = User::
//        where('created_by', '!=', 'system')->
        get();
        $branches = Branch::where('inactive',0)->get();
        $roles = Role::all();
        return view('user.setup.user_roles', compact(
            'user_roles_count', 'branches', 'users', 'roles'
        ));
    }


    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $users = BranchUser::with(['user', 'branch', 'role'])
            ->whereHas('user', function ($q) {
                $q->where('created_by', '!=', 'system');
            })->orderBy('user_id', 'desc');
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
    public function view($id){
        $branch_users = BranchUser::with(['user', 'branch', 'role'])
            ->find($id);
        $user_role = [
            'User' => $branch_users->user->username,
            'Branch' => $branch_users->branch->name,
            'Role' => $branch_users->role->name,
        ];
        return success_web_processor($user_role, __('messages.msg_item_found', ['attribute' => __('messages.role')]));
    }

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::userRoleCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'user_id' => $request->user,
            'branch_id' => $request->branch,
            'role_id' => $request->role,
            'client_ref' => get_user_ref(),
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $branch_user = BranchUser::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_ROLE_ASSIGNMENT,
                $request->getClientIp(),
                'User Role Assignment',
                json_encode($post_data),
                auth('user')->id(),
                $branch_user->id
            );
        }

        return success_web_processor(['id' => $branch_user->id], __('messages.role_assigned'));
    }

    public function destroy($id)
    {
        $branch_user = BranchUser::with('user')
            ->whereHas('user', function ($q) {
                $q->where('created_by', '!=', 'system');
            })
        ->find($id);
        if (isset($branch_user)) {
            $branch_user->delete();
            return success_web_processor(null, __('messages.msg_removed_success', ['attribute' => __('messages.role')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.role')]));
    }
}
