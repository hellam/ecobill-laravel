<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Models\Role;
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
        return view('user.setup.roles', compact('permission_groups','roles'));
    }

    public function create(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:'.Role::class.',name,NULL,id,client_ref,'.get_user_ref(),
            'permissions' => 'required|array',
        ]);


        if ($validator->fails()) {
            return error_web_processor(__('messages.field_correction'),
                200, validation_error_processor($validator));
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
        $contact = Role::find($id);
        if (isset($contact)) {
            return success_web_processor($contact, __('messages.msg_item_found', ['attribute' => __('messages.role')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.role')]));
    }
}
