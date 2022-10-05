<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\PasswordHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = User::where('created_by', '!=', 'system')->count() ?? 0;
        return view('user.setup.users', compact('users_count'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $users = User::select('id', 'username', 'full_name', 'phone', 'email', 'inactive', 'image')
            ->where('created_by', '!=', 'system')
            ->orderBy('created_at', 'desc');
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

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::userCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }
        $password_policy_array = json_decode(get_security_configs()->password_policy, true);

        $password_expiry_date = null;
        if ($password_policy_array[0] != 0)
            $password_expiry_date = Carbon::now()->addDays($password_policy_array[0]);


        $post_data = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'password_expiry_date' => $password_expiry_date,
            'first_time' => $password_policy_array[4],
            'full_name' => $request->full_name,
            'uuid' => get_user_ref(),
            'role_id' => 1,
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $user = User::create($post_data);

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
            'created_by' => auth('user')->id(),
            'last_updated_by' => null,
        ]);


        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_ACCOUNT_MANAGEMENT,
                $request->getClientIp(),
                'Create User Account',
                json_encode($post_data),
                auth('user')->id(),
                $user->id
            );
        }

        return success_web_processor(['id' => $user->id], __('messages.msg_saved_success', ['attribute' => __('messages.user')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (isset($user)) {
            return success_web_processor($user, __('messages.msg_item_found', ['attribute' => __('messages.user')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.user')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::userUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $user = User::find($id);
        $user = set_update_parameters($user, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $user->full_name = $request->full_name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->first_time = 1;

            PasswordHistory::create([
                'user_id' => $user->id,
                'password' => $user->password,
                'created_by' => auth('user')->id(),
                'last_updated_by' => auth('user')->id(),
            ]);
        }

        $user->phone = $request->phone;
        $user->inactive = $request->inactive;
        $user->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.user')]));
    }

}
