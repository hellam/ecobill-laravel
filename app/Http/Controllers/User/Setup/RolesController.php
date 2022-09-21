<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class RolesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $permission_groups = PermissionGroup::with('permissions')->get();
        $roles = Role::all();
        return view('user.setup.roles', compact('permission_groups', 'roles'));
    }

    public function create(Request $request): JsonResponse
    {

        $validator = UserValidators::rolesCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $request->permissions = implode(',', $request->permissions);

        Role::create([
            'name' => $request->name,
            'permissions' => $request->permissions,
            'client_ref' => get_user_ref(),
            'created_by' => auth('user')->user()->username,
        ]);


        return success_web_processor(null, __('messages.msg_saved_success', ['attribute' => __('messages.role')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if (isset($role)) {
            $permissions = explode(',', $role->permissions);
            $response['role'] = $role;
            $response['permissions'] = [];

            $all_permissions = Permission::with('permission_group')->orderBy('parent_id')->get();
            foreach ($all_permissions as $permission) {
                $response['permissions'][] = ['group_name' => $permission->permission_group->name, 'code' => $permission->code, 'name' => $permission->name, 'checked' => in_array($permission->code, $permissions)];
            }
            $price = array_column($response['permissions'], 'group_name');
            array_multisort($price, SORT_ASC, $response['permissions']);
            return success_web_processor($response, __('messages.msg_item_found', ['attribute' => __('messages.role')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.role')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $validator = UserValidators::rolesUpdateValidation($request, $id);

        if ($validator != '') {
            return $validator;
        }

//        $request->permissions = implode(',', $request->permissions);
//
//        $role = Role::find($id);
//        $role->name = $request->name;
//        $role->permissions = $request->permissions;
//        $role->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.role')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $contact = Role::find($id);
        if (isset($contact)) {
            $users = User::where('role_id', $id)->count();
            if ($users > 0) {
                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.role'), 'attribute1' => __('messages.users')]));
            }
            $contact->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.role')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.role')]));
    }
}
