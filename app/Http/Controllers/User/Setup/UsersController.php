<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\generateUniqueId;
use function App\CentralLogics\get_security_configs;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\success_web_processor;

class UsersController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = User::where('created_by','!=','system')->count() ?? 0;
        return view('user.setup.users', compact('users_count'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $users = User::where('created_by','!=','system')->orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($users)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.setup.users.edit', [$row->id]),
                    "update_url" => route('user.setup.users.update', [$row->id]),
//                    "delete_url" => route('user.setup.users.delete', [$row->id])
                ];
            })->editColumn('fiscal_year', function ($row) {
                return format_date($row->fiscalyear->begin) . ' - ' . format_date($row->fiscalyear->end);
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
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

        return success_web_processor(['id' => $user->id], __('messages.msg_saved_success', ['attribute' => __('messages.branch')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $branch = Branch::with('fiscalyear')->find($id);
        if (isset($branch)) {
            return success_web_processor($branch, __('messages.msg_item_found', ['attribute' => __('messages.branch')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.branch')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::branchUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $branch = Branch::find($id);
        $branch = set_update_parameters($branch, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->tax_no = $request->tax_no;
        $branch->tax_period = $request->tax_period;
        $branch->default_currency = $request->default_currency;
        $branch->default_bank_account = $request->default_bank_account;
        $branch->fiscal_year = $request->fiscal_year;
        $branch->timezone = $request->timezone;
        $branch->address = $request->address;
        $branch->bcc_email = $request->bcc_email;
        $branch->inactive = $request->inactive;
        $branch->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.branch')]));
    }

}
