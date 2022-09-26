<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class BranchController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $branches_count = count(auth('user')->user()->user_branches) ?? [];
        $fiscal_year =FiscalYear::all();
        $currency =Currency::all();
        return view('user.setup.branches', compact('branches_count', 'currency', 'fiscal_year'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $branch = Branch::with('fiscalyear')->orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($branch)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.setup.maker_checker_rules.edit', [$row->id]),
                    "update_url" => route('user.setup.maker_checker_rules.update', [$row->id]),
                    "delete_url" => route('user.setup.maker_checker_rules.delete', [$row->id])];
            })->editColumn('fiscal_year', function ($row) {
                return format_date($row->fiscalyear->begin).' - '.format_date($row->fiscalyear->end);
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }

    public function create(Request $request,$created_at = null, $created_by = null,
                                            $supervised_by = null, $supervised_at = null): JsonResponse
    {

        $validator = UserValidators::branchCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'tax_no' => $request->tax_no,
            'tax_period' => $request->tax_period,
            'default_currency' => $request->default_currency,
            'default_bank_account' => $request->default_bank_account,
            'fiscal_year' => $request->fiscal_year,
            'timezone' => $request->timezone,
            'address' => $request->address,
            'bcc_email' => $request->bcc_email,
            'client_ref' => get_user_ref()
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $branch = Branch::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_BRANCH_SETUP,
                $request->getClientIp(),
                'Create Role',
                json_encode($post_data),
                auth('user')->id(),
                $branch->id
            );
        }

        return success_web_processor(['id' => $branch->id], __('messages.msg_saved_success', ['attribute' => __('messages.branch')]));
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
        $branch->update();
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
