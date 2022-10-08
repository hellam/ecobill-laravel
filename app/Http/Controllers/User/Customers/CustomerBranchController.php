<?php

namespace App\Http\Controllers\User\Customers;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monarobase\CountryList\CountryListFacade;
use Yajra\DataTables\DataTables;

class CustomerBranchController extends Controller
{
    public function index(): Factory|View|Application
    {
        $customer_branch = CustomerBranch::count() ?? 0;
        $currency = Currency::all();
        return view('user.customers.branch', compact('customer_branch','currency'));
    }

    //Data table API
    public function dt_api(Request $request)
    {
        $customers = CustomerBranch::with('customer:id,f_name,l_name')->orderBy('f_name');
        return (new DataTables)->eloquent($customers)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.customers.branch.edit', [$row->id]),
                    "update_url" => route('user.customers.branch.update', [$row->id]),
                    "delete_url" => route('user.customers.branch.delete', [$row->id])];
            })->editColumn('country', function ($row) {
                return CountryListFacade::getOne($row->country);
            })->addColumn('customer', function ($row) {
                return $row->customer->f_name . ' ' . $row->customer->l_name;
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d');
            })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::customerBranchCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'customer_id' => $request->customer_id,
            'f_name' => $request->first_name,
            'l_name' => $request->last_name,
            'short_name' => $request->short_name,
            'branch' => $request->branch,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'currency' => $request->currency,
            'address' => $request->address,
            'client_ref' => get_user_ref(),
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));


        $customer_branch = CustomerBranch::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_SUBSCRIPTION_SETUP,
                $request->getClientIp(),
                'Create Customer Branch',
                json_encode($post_data),
                auth('user')->id(),
                $customer_branch->id
            );
        }

        return success_web_processor(['id' => $customer_branch->id], __('messages.msg_saved_success', ['attribute' => __('messages.customer_branch')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id): JsonResponse
    {
        $customer_branch = CustomerBranch::find($id);
        if (isset($customer_branch)) {
            $customer_branch = CustomerBranch::with('customer:id,f_name,l_name,short_name')->find($id);

            return success_web_processor($customer_branch, __('messages.msg_item_found', ['attribute' => __('messages.customer_branch')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.customer_branch')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $customer = CustomerBranch::select('f_name', 'l_name', 'branch', 'short_name', 'id')
            ->where('inactive', 0)
            ->orderBy('f_name')->orderBy('l_name')
            ->limit(10)
            ->get();
        if ($request->filled('search'))
            $customer = CustomerBranch::select('f_name', 'l_name', 'branch', 'short_name', 'id')
                ->where('inactive', 0)
                ->where('f_name', 'like', '%' . $request->search . '%')
                ->orWhere('l_name', 'like', '%' . $request->search . '%')
                ->orWhere('branch', 'like', '%' . $request->search . '%')
                ->orWhere('short_name', $request->search . '%')
                ->orderBy('f_name')->orderBy('l_name')
                ->limit(10)
                ->get();

        //push edit url to array
//        foreach ($customer as $key => $item) {
//            $customer[$key]['edit_url'] = route('user.messaging.debtor.edit', ['id' => $item->id]);
//        }

        return response()->json($customer, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $validator = UserValidators::customerBranchUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $customer_branch = CustomerBranch::find($id);
        if ($customer_branch) {
                $customer_branch->f_name = $request->first_name;
                $customer_branch->l_name = $request->last_name;
                $customer_branch->address = $request->address;
                $customer_branch->branch = $request->branch;
                $customer_branch->country = $request->country;
                $customer_branch->email = $request->email;
                $customer_branch->phone = $request->phone;
                $customer_branch->inactive = $request->inactive;
                $customer_branch->update();
            return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.customer')]));
        }

        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $customer_branch = CustomerBranch::find($id);
        if ($customer_branch) {
            //TODO: check if customer_branch has transactions
//            $customer_branch = CustomerBranch::where('customer_id', $customer->id)->count();
//            if ($customer_branch > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.customer'), 'attribute1' => __('messages.customer_branch')]));
//            }
            $customer_branch->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.customer_branch')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.customer_branch')]));
    }
}
