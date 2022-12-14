<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\Role;
use App\Models\User;
use App\Scopes\BranchScope;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Yoeunes\Toastr\Facades\Toastr;

class BranchController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $branches_count = count(auth('user')->user()->user_branches) ?? [];
        $fiscal_year = FiscalYear::all();
        $currency = Currency::all();
        $bank_accounts = BankAccount::withoutGlobalScope(BranchScope::class)->get();
        return view('user.setup.branches', compact('branches_count', 'currency', 'fiscal_year', 'bank_accounts'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $branch = Branch::with('fiscalyear')->orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($branch)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.setup.branches.edit', [$row->id]),
                    "update_url" => route('user.setup.branches.update', [$row->id]),
                    "delete_url" => route('user.setup.branches.delete', [$row->id])];
            })->editColumn('name', function ($row) {
                return $row->name . ($row->is_main ? '<br/><div class="badge badge-sm badge-light-dark">Main</div>' : '');
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
            'default_currency' => get_company_default_currency(),
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
                'Create Branch',
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
//        $branch->default_currency = $request->default_currency;
        $branch->default_bank_account = $request->default_bank_account;
        $branch->fiscal_year = $request->fiscal_year;
        $branch->timezone = $request->timezone;
        $branch->address = $request->address;
        $branch->bcc_email = $request->bcc_email;
        $branch->inactive = $branch->is_main ? 0 : $request->inactive;
        $branch->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.branch')]));
    }

    public function switch_branch($branch) {
        Session::put('branch', $branch);
        Toastr::success(trans('messages.msg_branch_switched_success'), trans('messages.welcome') . '!', ["positionClass" => "toast-top-right"]);
        return redirect()->route('user.dashboard');
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
