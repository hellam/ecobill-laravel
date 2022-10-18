<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\ChartAccount;
use App\Models\ChartGroup;
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
        $output = div_start('view_data', 'data-kt-action="' . route('user.setup.business_settings.update', $tab) . '"');
        $output .= div_start('card-body border-top p-9');
        $output .= @csrf_field();
        switch ($tab) {
            case 'general':
                $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()?->value, true);
                $output .= image_view(
                    'actual_imageInput',
                    'actual_imageInput',
                    'assets/media/avatars/logo.png',
                    route('user.files',
                        [
                            'folder' => 'company',
                            'fileName' => $general_settings['logo'] ?? 'null'
                        ]
                    )
                );
                $output .= input_field('company_name', 'Company Name', $general_settings['company_name'] ?? 'Company Name', true);
                $output .= input_field('inv_footer', 'Invoice Footer', $general_settings['inv_footer'] ?? 'Thank you for your business', true);
                $output .= select('tax_inclusive', 'Prices Inclusive of Tax', [1 => 'Yes', 0 => 'No'], '', $general_settings['tax_inclusive'] ?? 1);
                $output .= isset($general_settings['default_currency']) ? view_field('Default Currency', $general_settings['default_currency']) : select('default_currency', 'Select Currency', CURRENCY, 'This cannot be changes in future', null, true);
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
            case 'gl_setup':
                $gl_accounts = ChartGroup::with('accounts:account_name,account_code,account_group')->get();

                $accounts_settings = json_decode(BusinessSetting::where('key', 'gl_accounts_setup')->first()?->value, true);
                $output .= group_select('sales_account', 'Sales Account', $gl_accounts, '', $accounts_settings['sales_account'] ?? null);
                $output .= group_select('receivable_account', 'Receivable Account', $gl_accounts, '', $accounts_settings['receivable_account'] ?? null);
                $output .= group_select('sales_discount_account', 'Sales Discount Account', $gl_accounts, '', $accounts_settings['sales_discount_account'] ?? null);
                $output .= group_select('payment_discount_account', 'Payment Discount Account', $gl_accounts, '', $accounts_settings['payment_discount_account'] ?? null);
                $output .= group_select('sales_tax', 'Sales Tax Account', $gl_accounts, '', $accounts_settings['sales_tax'] ?? null);
                break;
            case 'sms':
                $output .= 'Coming soon';
                break;
            case 'email':
                $output .= 'Coming soon';
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
                $business_settings = BusinessSetting::where('key', 'general_settings')->first();

                $array = json_decode($business_settings?->value, true);
                $fileName = $array['logo'] ?? null;
                if ($request->has('logo')) {
                    $requestImage = $request->logo; //your base64 encoded
                    try {
                        $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/company');
                    } catch (\Exception $exception) {
                        return error_web_processor('Invalid image file',
                            200, ['field' => 'logo', 'error' => 'Invalid Image file']);
                    }
                }

                $key = 'general_settings';
                $value = json_encode(
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
                self::updateOrCreate($business_settings, $key, $value);
                break;
            case 'gl_setup':
                $business_settings = BusinessSetting::where('key', 'gl_accounts_setup')->first();
                $key = 'gl_accounts_setup';
                $value = json_encode(
                    [
                        'sales_account' => $request->sales_account,
                        'receivable_account' => $request->receivable_account,
                        'sales_discount_account' => $request->sales_discount_account,
                        'payment_discount_account' => $request->payment_discount_account,
                        'sales_tax' => $request->sales_tax,
                    ]
                );
                self::updateOrCreate($business_settings, $key, $value);
                break;
            case 'sms':
                break;
            case 'email':
                break;
        }

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.business_settings')]));
    }

    public function updateOrCreate($obj, $key, $value)
    {
        if ($obj)
            BusinessSetting::where('key', $key)->update([
                'value' => $value,
            ]);
        else
            BusinessSetting::create([
                'key' => $key,
                'value' => $value,
                'client_ref' => get_user_ref(),
            ]);
    }
}
