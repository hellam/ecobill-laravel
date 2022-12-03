<?php

namespace App\Http\Controllers\User\Billing;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CustomerBranch;
use App\Models\CustomerTrx;
use App\Models\CustomerTrxDetail;
use App\Models\GlTrx;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\SalesTrx;
use App\Models\SalesTrxDetail;
use App\Models\Tax;
use App\Models\TaxTrx;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currency = Currency::all();
        $tax = Tax::all();
        $payment_terms = PaymentTerm::orderBy('terms', 'desc')->get();
        return view('user.billing.new_invoice', compact('currency', 'payment_terms', 'tax'));
    }

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::newInvoiceCreateValidation($request);
        if ($validator != '') {
            return $validator;
        }

        $customer_branch = CustomerBranch::with('customer:id,tax_id')->find($request->customer);

        $total = 0;
        $total_cost = 0;
        $total_tax = 0;
        $taxes_with_totals = [];
        $fx_rate = $request->filled('fx_rate') ? $request->fx_rate : 1;
        $discount = 0;

        try {
            DB::beginTransaction();

            $trans_no = generate_reff_no(ST_INVOICE, true, $request->reference);
            $active_branch = get_active_branch();
            $client_ref = get_user_ref();

            $post_data = [
                'trans_no' => $trans_no,
                'trx_type' => ST_INVOICE,
                'trx_date' => $request->invoice_date,
                'branch_id' => $active_branch,
                'client_ref' => $client_ref,
            ];
            $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

            ## Start of Record INVOICE
            //Record Sale (customer_trx, customer_trx_details, sales_trx, sales_trx_details)
            foreach ($request->invoice_items as $key => $val) {
                $bar_code = $val['product'];
                $qty = $val['quantity'];
                $price = $val['price'];
                $tax_id = $val['tax'];
                $description = $val['description'] ?? null;

                $tax = Tax::find($tax_id);
                $product = Product::where('barcode', $bar_code)->first();
                $total += ($price * $qty);
                $tax_subtotal = calculate_tax(($price * $qty), $tax->rate);
                $total_tax += $tax_subtotal;
                $unit_cost = convert_currency_to_first_currency($product->cost, $fx_rate);
                $total_cost += $unit_cost;
                $taxes_with_totals[$tax_id] = $taxes_with_totals[$tax_id] ?? +$tax_subtotal;

                CustomerTrxDetail::create([
                    'trans_no' => $trans_no,
                    'trx_type' => ST_INVOICE,
                    'barcode' => $bar_code,
                    'stock_id' => $product->id,
                    'description' => $product->name,
                    'long_description' => $description,
                    'unit_price' => $price,
                    'unit_tax' => $tax->rate,
                    'qty' => $qty,
                    'cost' => $unit_cost,
                    'qty_done' => $qty,
                    'branch_id' => $active_branch,
                    'client_ref' => $client_ref,
                ]);

                SalesTrxDetail::create([
                    'trans_no' => $trans_no,
                    'trx_type' => ST_INVOICE,
                    'barcode' => $bar_code,
                    'description' => $product->name,
                    'long_description' => $description,
                    'qty_sent' => $qty,
                    'unit_price' => $price,
                    'unit_tax' => $tax->rate,
                    'qty' => $qty,
                    'branch_id' => $active_branch,
                    'client_ref' => $client_ref,
                ]);

            }

            //if customer has tax, use that
            if ($customer_branch->tax_id != null) {
                $tax = Tax::find($customer_branch->tax_id);
                $taxes_with_totals = [
                    $customer_branch->tax_id => calculate_tax($total, $tax->rate),
                ];

                $total_tax = calculate_tax($total, $tax->rate);
            }

            $discount = get_discount($request, $total);
            $total_sale = $total - $discount;

            CustomerTrx::create(array_merge($post_data, [
                'customer_id' => $customer_branch->customer_id,
                'customer_branch_id' => $customer_branch->id,
                'due_date' => $request->due_date,
                'reference' => $trans_no,
                'order_id' => $trans_no,
                'amount' => $total_sale,
                'discount' => $discount,
                'alloc' => 0,//Go below Customer Payment
                'rate' => $fx_rate,
                'payment_terms' => $request->pay_terms,
                'is_tax_included' => get_company_setting('tax_inclusive'),
            ]));

            SalesTrx::create(array_merge($post_data, [
                'customer_id' => $customer_branch->customer_id,
                'customer_branch_id' => $customer_branch->id,
                'due_date' => $request->due_date,
                'reference' => $trans_no,
                'comments' => $request->notes,
                'delivery_address' => $request->address,
                'contact_phone' => $request->phone,
                'contact_email' => $request->email,
                'delivery_to' => $request->address,
                'payment_terms' => $request->pay_terms,
                'amount' => $total_sale,
                'alloc' => 0,//Go below Customer Payment
                'is_tax_included' => get_company_setting('tax_inclusive'),
            ]));

            //Tax trx
            var_dump($taxes_with_totals);
//            TaxTrx::create([
//                'trx_type' => ST_INVOICE,
//                'trx_no' => $trans_no,
//                'trx_date' => $request->invoice_date,
//                'included_in_price' => get_company_setting('tax_inclusive'),
//                'net_amount' => $total,
//                'customer_branch_id' => $customer_branch->id,
//                'due_date' => $request->due_date,
//                'reference' => $trans_no,
//                'comments' => $request->notes,
//                'delivery_address' => $request->address,
//                'contact_phone' => $request->phone,
//                'contact_email' => $request->email,
//                'delivery_to' => $request->address,
//                'payment_terms' => $request->pay_terms,
//                'amount' => $total_sale,
//                'branch_id' => $active_branch,
//                'client_ref' => $client_ref,
//            ]);
            //GL trx
            #Receivable  | Debit
            GlTrx::create(array_merge($post_data, [
                'chart_code' => $customer_branch->sales_account ?? 4010,
                'narration' => '',
                'amount' => convert_currency_to_second_currency($total_sale, $fx_rate),
            ]));
            #Sales Discount if > 0 | Debit
            if ($discount > 0)
                GlTrx::create(array_merge($post_data, [
                    'chart_code' => $customer_branch->sales_discount_account ?? 4510,
                    'narration' => '',
                    'amount' => convert_currency_to_second_currency($discount, $fx_rate),
                ]));

            #Sale  | Credit
            $total_sale_exclusive = $total_sale - $total_tax;
            GlTrx::create(array_merge($post_data, [
                'chart_code' => $customer_branch->sales_account ?? 4010,
                'narration' => '',
                'amount' => -convert_currency_to_second_currency($total_sale_exclusive, $fx_rate),
            ]));

            #Sales Tax | Credit
            if ($total_tax > 0)
                GlTrx::create(array_merge($post_data, [
                    'chart_code' => get_all_company_settings()['sales_tax'] ?? 2150,
                    'narration' => '',
                    'amount' => -convert_currency_to_second_currency($total_tax, $fx_rate),
                ]));

            ## End of Record INVOICE

            ## Start of Record PAYMENT
            //save payment transaction if it's a cash sale
//            if ($request->filled('into_bank')) {
//                $payment_trans_no = generate_reff_no(ST_CUSTOMER_PAYMENT, true);
//                $bank = BankAccount::find($request->into_bank);
//
//                //Record Bank Account GL Transaction
//                $bank_gl_trx_post = array_merge($post_data, [
//                    'chart_code' => $bank->chart_code,
//                    'narration' => $request->filled('customer_branch_id') ? $customer_branch->f_name . ' ' . $customer_branch->l_name . ' [' . $customer_branch->id . ']' : $request->misc ?? '',
//                    'amount' => convert_currency_to_second_currency($total, $fx_rate)
//                ]);
//                GlTrx::create($bank_gl_trx_post);
//
//                //Record Bank Transaction
//                $bank_trx_post_data = array_merge($post_data, [
//                    'reference' => $trans_no,
//                    'bank_id' => $request->into_bank,
//                    'amount' => $total,
//                ]);
//                BankTrx::create($bank_trx_post_data);
//
//                $customer_trx_post_data = array_merge($post_data, [
//                    'customer_id' => $customer_branch->customer_id,
//                    'customer_branch_id' => $request->customer_branch_id,
//                    'rate' => $fx_rate,
//                    'amount' => $total,
//                    'reference' => $trans_no,
//                ]);
//                CustomerTrx::create($customer_trx_post_data);
//            }

            ## End of Record PAYMENT

            //Comments
//            if ($request->filled('comments'))
//                Comment::create([
//                    'trx_type' => ST_ACCOUNT_DEPOSIT,
//                    'trx_no' => $trans_no,
//                    'trx_date' => $request->date,
//                    'comment' => $request->comments,
//                    'branch_id' => $active_branch,
//                    'client_ref' => $client_ref,
//                ]);
            DB::commit();
        } catch (\Exception $e) {
            return error_web_processor($e);
        }
        return success_web_processor(['id' => $trans_no], __('messages.msg_saved_success', ['attribute' => __('messages.invoice')]));
    }
}
