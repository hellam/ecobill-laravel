<?php

namespace App\Http\Controllers\User\Customers;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
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
        return view('users.customers.branch', compact('customer_branch'));
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
                return $row->customer->f_name.' '.$row->customer->l_name;
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
            'f_name' => $request->first_name,
            'l_name' => $request->last_name,
            'short_name' => $request->short_name,
            'address' => $request->address,
            'company' => $request->company,
            'country' => $request->country,
            'tax_id' => $request->tax_id == 'null' ? null : $request->tax_id,
            'currency' => $request->currency,
            'payment_terms' => $request->payment_terms,
            'credit_limit' => $request->credit_limit,
            'credit_status' => $request->credit_status,
            'sales_type' => $request->sales_type,
            'discount' => $request->discount,
            'language' => $request->language,
            'client_ref' => get_user_ref(),
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data1 = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        try {
            DB::beginTransaction();
            $customer = Customer::create($post_data1);

            $post_data = [
                'customer_id' => $customer->id,
                'f_name' => $request->first_name,
                'l_name' => $request->last_name,
                'short_name' => $request->short_name,
                'branch' => $request->company,
                'country' => $request->country,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'client_ref' => get_user_ref(),
            ];

            //set_create_parameters($created_at, $created_by, ...)
            $post_data2 = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));


            if ($created_at == null) {
                //if not supervised, log data from create request
                //Creator log
                log_activity(
                    ST_SUBSCRIPTION_SETUP,
                    $request->getClientIp(),
                    'Create Customer and Contacts',
                    json_encode($post_data1),
                    auth('user')->id(),
                    $customer->id
                );
            }
            $contact = CustomerBranch::create($post_data2);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return error_web_processor($e);
        }

        return success_web_processor(['id' => $customer->id], __('messages.msg_saved_success', ['attribute' => __('messages.customer')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id): JsonResponse
    {
        $customer = Customer::find($id);
        if (isset($customer)) {
            $customer = Customer::with('customer_branch')->find($id);

            return success_web_processor($customer, __('messages.msg_item_found', ['attribute' => __('messages.customer')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $customer = Customer::select('f_name', 'l_name', 'company', 'short_name', 'id')
            ->where('inactive', 0)
            ->orderBy('f_name')->orderBy('l_name')
            ->limit(10)
            ->get();
        if ($request->filled('search'))
            $customer = Customer::select('f_name', 'l_name', 'company', 'short_name', 'id')
                ->where('inactive', 0)
                ->where('f_name', 'like', '%' . $request->search . '%')
                ->orWhere('l_name', 'like', '%' . $request->search . '%')
                ->orWhere('company', 'like', '%' . $request->search . '%')
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
        $validator = UserValidators::customerUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $customer = Customer::find($id);
        if ($customer) {
            try {
                DB::beginTransaction();
                $customer->f_name = $request->first_name;
                $customer->l_name = $request->last_name;
                $customer->country = $request->country;
                $customer->tax_id = $request->tax_id == 'null' ? null : $request->tax_id;
                $customer->currency = $request->currency;
                $customer->payment_terms = $request->payment_terms;
                $customer->credit_limit = $request->credit_limit;
                $customer->credit_status = $request->credit_status;
                $customer->sales_type = $request->sales_type;
                $customer->discount = $request->discount;
                $customer->language = $request->language;
                $customer->address = $request->address;
                $customer->company = $request->company;
                $customer->inactive = $request->inactive;
                $customer->update();


                $customer_branch = CustomerBranch::find($request->customer_branch_id);

                $customer_branch->f_name = $request->first_name;
                $customer_branch->l_name = $request->last_name;
                $customer_branch->address = $request->address;
                $customer_branch->branch = $request->company;
                $customer_branch->country = $request->country;
                $customer_branch->email = $request->email;
                $customer_branch->phone = $request->phone;
                $customer_branch->update();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return error_web_processor($e);
            }
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
        $customer = Customer::find($id);
        if ($customer) {
            $customer_branch = CustomerBranch::where('customer_id', $customer->id)->count();
            if ($customer_branch > 0) {
                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.customer'), 'attribute1' => __('messages.customer_branch')]));
            }
            $customer->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.customer')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }
}
