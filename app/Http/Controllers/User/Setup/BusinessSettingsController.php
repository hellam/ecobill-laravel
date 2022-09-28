<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Request;

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
        $output.= @csrf_field();
        switch ($tab) {
            case 'general':
                $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()->value, true);
                $output .= input_field('company_name', 'Company Name', $general_settings['company_name'], true);
                $output .= input_field('inv_footer', 'Invoice Footer', $general_settings['inv_footer'], true);
                break;
            case 'sms':
                $output .= 'SMS Settings';
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
        switch ($tab) {
            case 'general':
                $business_settings = BusinessSetting::where('key','general_settings')->firstOrFail();
                $business_settings->key = 'general_settings';
                $business_settings->value = json_encode(
                    [
                        'company_name' => $request->company_name,
                        'inv_footer' => $request->inv_footer,
                    ]
                );
                $business_settings->save();
                break;
            case 'sms':

                break;
            case 'email':

        }
    }
}
