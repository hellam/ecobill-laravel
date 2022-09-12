<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
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

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
//        $validator = Validator::make($request->all(), [
//            'f_name' => 'required',
//            'l_name' => 'required',
//            'address' => 'required',
//            'country' => 'required',
////            'company' => 'required',
//            'email' => 'required|email:rfc,dns,spoof|unique:' . Contacts::class . ',email,' . $id . ',client_ref,' . Helpers::get_user_ref(),
//            'phone' => 'required|min:13|max:13|unique:' . Contacts::class . ',phone,' . $id . ',client_ref,' . Helpers::get_user_ref(),
//        ], [
//            'f_name.required' => __('validation.required', ['attribute' => 'first name']),
//            'l_name.required' => __('validation.required', ['attribute' => 'last name']),
//        ]);
//
//        if ($validator->fails()) {
//            return Helpers::error_web_processor(__('messages.field_correction'),
//                200, Helpers::validation_error_processor($validator));
//        }
//
//        $contact = Contacts::find($id);
//        $contact->f_name = $request->f_name;
//        $contact->l_name = $request->l_name;
//        $contact->address = $request->address;
//        $contact->branch = $request->company;
//        $contact->country = $request->country;
//        $contact->email = $request->email;
//        $contact->phone = $request->phone;
//        $contact->update();
//
//        return Helpers::success_web_processors_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.contact')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $contact = Role::find($id);
        if (isset($contact)) {
            $users= User::where('role_id', $id)->count();
            if ($users > 0) {
                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.role'), 'attribute1' => __('messages.users')]));
            }
            $contact->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.role')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.role')]));
    }
}
