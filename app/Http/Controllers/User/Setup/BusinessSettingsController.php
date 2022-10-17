<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\ChartAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BusinessSettingsController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.setup.business_settings');
    }

    public function view($tab)
    {
        $output = div_start('view_data');
        $output .= div_start('card-body border-top p-9');
        $output .= @csrf_field();
        switch ($tab) {
            case 'general':
                $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()->value, true);
                $output .= image_view(
                    'actual_imageInput',
                    'actual_imageInput',
                    'assets/media/avatars/logo.png',
                    route('user.files',
                        [
                            'folder' => 'users',
                            'fileName' => $general_settings['logo'] ?? 'null'
                        ]
                    )
                );
                $output .= input_field('company_name', 'Company Name', $general_settings['company_name'], true);
                $output .= input_field('inv_footer', 'Invoice Footer', $general_settings['inv_footer'], true);
                $output .= select('tax_inclusive', 'Prices Inclusive of Tax', [1 => 'Yes', 0 => 'No'], '', $general_settings['tax_inclusive'] ?? 1);
                $output .= isset($general_settings['default_currency']) ? view_field('Default Currency', $general_settings['default_currency']) : select('default_currency', 'Select Currency', CURRENCY, 'This cannot be changes in future');
                $output .= select('date_format', 'Select Date Format', DATE_FORMAT, '', $general_settings['date_format'] ?? null);
                $output .= input_field('date_sep', 'Date Separator', $general_settings['date_sep'] ?? '/', true);
                $output .= input_field('tho_sep', 'Thousand Separator', $general_settings['tho_sep'] ?? ',', true);
                $output .= input_field('dec_sep', 'Decimal Separator', $general_settings['dec_sep'] ?? '.', true);
                $output .= input_field('price_dec', 'Price Decimals', $general_settings['price_dec'] ?? '2', true, 'number');
                $output .= input_field('qty_dec', 'Quantity Decimals', $general_settings['qty_dec'] ?? '2', true, 'number');
                $output .= input_field('rates_dec', 'Rates Decimals', $general_settings['rates_dec'] ?? '2', true, 'number');
                $output .= input_field('transaction_days', 'Transaction Days', $general_settings['transaction_days'] ?? '30', true, 'number');
                $output .= input_field('def_print_destination', 'Default Print Destination', $general_settings['def_print_destination'] ?? 'office', true);
                break;
            case 'sms':
                $gl_accounts = ChartAccount::select('account_code', 'account_name')->get();
                $data = array();
                foreach ($gl_accounts as $account)
                    $data[$account->account_code]  = $account->account_code;
//                dd($data);
                $accounts_settings = json_decode(BusinessSetting::where('key', 'accounts_setup')->first()?->value, true);
                $output .= select('sales_account', 'Sales Account', $data, '', $accounts_settings['sales_account'] ?? null);
                $output .= select('receivable_account', 'Receivable Account', $data, '', $accounts_settings['receivable_account'] ?? null);
                $output .= select('sales_discount_account', 'Sales Discount Account', $data, '', $accounts_settings['sales_discount_account'] ?? null);
                $output .= select('payment_discount_account', 'Payment Discount Account', $data, '', $accounts_settings['payment_discount_account'] ?? null);
                break;
            case 'email':
                $output .= 'Email Settings';
                break;
        }
        $output .= div_end();
        $output .= div_start('card-footer d-flex justify-content-end py-6 px-9');
        $output .= submit_button('Save Changes', 'btn_save');
        $output .= div_end();
        $output .= div_end();
        return $output;
    }

    public function update(Request $request, $tab)
    {
        $validator = UserValidators::generalSettingsUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }
        switch ($tab) {
            case 'general':
                $business_settings = BusinessSetting::where('key', 'general_settings')->firstOrFail();

                $array = json_decode($business_settings->value, true);
                $fileName = $array['logo'] ?? '';
                if ($request->has('logo')) {
                    $requestImage = $request->logo; //your base64 encoded
                    try {
                        $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/users');
                    } catch (\Exception $exception) {
                        return error_web_processor('Invalid image file',
                            200, ['field' => 'logo', 'error' => 'Invalid Image file']);
                    }
                }

                $business_settings->key = 'general_settings';
                $business_settings->value = json_encode(
                    [
                        'logo' => $fileName,
                        'company_name' => $request->company_name,
                        'inv_footer' => $request->inv_footer,
                        'tax_inclusive' => $request->tax_inclusive,
                        'default_currency' => $array['default_currency'] ?? $request->default_currency,
                        'date_format' => $request->date_format,
                        'date_sep' => $request->date_sep,
                        'tho_sep' => $request->tho_sep,
                        'dec_sep' => $request->dec_sep,
                        'price_dec' => $request->price_dec,
                        'qty_dec' => $request->qty_dec,
                        'rates_dec' => $request->rates_dec,
                        'transaction_days' => $request->transaction_days,
                        'def_print_destination' => $request->def_print_destination,
                    ]
                );
                $business_settings->save();
                break;
            case 'sms':
                break;
            case 'email':
                break;
        }

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.business_settings')]));
    }
}
