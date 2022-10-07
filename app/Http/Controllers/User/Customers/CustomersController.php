<?php

namespace App\Http\Controllers\User\Customers;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerBranch;
use App\Models\PaymentTerm;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CustomersController extends Controller
{

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $customers = Customer::count() ?? 0;
        $currency = Currency::all();
        $payment_terms = PaymentTerm::all();
        $tax = Tax::all();

        return view('user.customers.customers', compact('customers',
            'currency', 'payment_terms', 'tax'));
    }

    //Data table API
    public function dt_api(Request $request)
    {
        $debtors = Customer::with('customer_branch:id,phone,email,id')->orderBy('f_name');
        return (new DataTables)->eloquent($debtors)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.messaging.debtor.edit', [$row->id]),
                    "update_url" => route('user.messaging.debtor.update', [$row->id]),
                    "delete_url" => route('user.messaging.debtor.delete', [$row->id])];
            })
//            ->editColumn('contact', function ($row) {
//                $row->contact ? $outp = '<small>' . $row->contact->email . '</small><br/>' . $row->contact->phone : $outp = "< Contact Deleted >";
//                return $outp;
//            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d');
            })->filterColumn('name', function ($query, $keyword) {
                $keywords = trim($keyword);
                $query->whereRaw("CONCAT(debtor_master.f_name, ' ', debtor_master.l_name) like ?", ["%{$keywords}%"]);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::customerCreateValidation($request);

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
            'discount' => $request->sales_type,
            'language' => $request->sales_type,
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
            return error_web_processor($e,
                200);
        }

        return success_web_processor(['id' => $customer->id], __('messages.msg_saved_success', ['attribute' => __('messages.customer')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id): JsonResponse
    {
        $customer = DebtorMaster::find($id);
        if (isset($customer)) {
            $debtor = DebtorMaster::with('contacts')->where(['inactive' => 0, 'id' => $id])->first();

            return Helpers::success_web_processor($debtor, __('messages.msg_item_found', ['attribute' => __('messages.customer')]));
        }
        return Helpers::error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $customer = DebtorMaster::select('f_name', 'l_name', 'company', 'debtor_ref', 'id')
            ->orderBy('f_name')->orderBy('l_name')
            ->limit(10)
            ->get();
        if ($request->has('search'))
            $customer = DebtorMaster::select('f_name', 'l_name', 'company', 'debtor_ref', 'id')
                ->where('f_name', 'like', '%' . $request->search . '%')
                ->orWhere('l_name', 'like', '%' . $request->search . '%')
                ->orWhere('company', 'like', '%' . $request->search . '%')
                ->orWhere('debtor_ref', $request->search . '%')
                ->orderBy('f_name')->orderBy('l_name')
                ->limit(10)
                ->get();

        //push edit url to array
        foreach ($customer as $key => $item) {
            $customer[$key]['edit_url'] = route('user.messaging.debtor.edit', ['id' => $item->id]);
        }

        return response()->json($customer, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $rules = [];
        foreach ($input['email'] as $key => $val) {
            $rules['email.' . $key] = 'required|email:rfc,dns,spoof|unique:' . Contacts::class . ',email,' . $request->contact[$key] . ',client_ref,' . Helpers::get_user_ref();
            $rules['phone.' . $key] = 'required|min:13|max:13|unique:' . Contacts::class . ',phone,' . $request->contact[$key] . ',client_ref,' . Helpers::get_user_ref();
        }
        $rules['f_name'] = 'required';
        $rules['l_name'] = 'required';
        $rules['address'] = 'required';
        $rules['country'] = 'required';
        $rules['email'] = 'required|array|min:1';
        $rules['phone'] = 'required|array|min:1';

        $validator = Validator::make($input, $rules, [
            'debtor_ref.required' => __('validation.required', ['attribute' => 'short name']),
            'phone.*.required' => __('validation.required', ['attribute' => 'phone']),
            'phone.*.unique' => __('validation.unique', ['attribute' => 'phone']),
            'phone.*.min' => __('validation.min', ['attribute' => 'phone']),
            'phone.*.max' => __('validation.max', ['attribute' => 'phone']),
            'email.*.required' => __('validation.required', ['attribute' => 'email']),
            'email.*.email' => __('validation.email', ['attribute' => 'email']),
            'email.*.unique' => __('validation.unique', ['attribute' => 'email']),
            'debtor_ref.unique' => __('validation.unique', ['attribute' => 'short name']),
            'f_name.required' => __('validation.required', ['attribute' => 'first name']),
            'l_name.required' => __('validation.required', ['attribute' => 'last name']),
        ]);

        if ($validator->fails()) {
            return Helpers::error_web_processor(__('messages.field_correction'),
                200, Helpers::validation_error_processor($validator));
        }

        $debtor = DebtorMaster::find($id);
        if ($debtor) {
            $debtor->f_name = $request->f_name;
            $debtor->l_name = $request->l_name;
            $debtor->address = $request->address;
            $debtor->company = $request->company;
            $debtor->country = $request->country;
            $debtor->update();

            foreach ($input['email'] as $key => $val) {
                $contact = Contacts::find($request->contact[$key]);
                if ($contact) {
                    $contact->email = $request->email[$key];
                    $contact->phone = $request->phone[$key];
                    $contact->update();
                }
            }
            return Helpers::success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.customer')]));
        }

        return Helpers::error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $debtor = DebtorMaster::find($id);
        if (isset($debtor)) {
            $contacts = Contacts::where('debtor_id', $debtor->id)->count();
            if ($contacts > 0) {
                return Helpers::error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.customer'), 'attribute1' => __('messages.contacts')]));
            }
            $debtor->delete();
            return Helpers::success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.customer')]));
        }
        return Helpers::error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.customer')]));
    }
}
