<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BusinessSettingsController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.setup.business_settings');
    }

    public function view($tab)
    {
        $output = '<div class="view_data">';
        switch ($tab) {
            case 'general':
                $output = $this->general_settings();
                break;
            case 'sms':
                $output = 'SMS Settings';
                break;
            case 'email':
                $output = 'Email Settings';
        }
        $output .= '</div>';
        return $output;
    }

    public function general_settings()
    {
        $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()->value, true);

        $output = input_field('company_name', 'Company Name', $general_settings['company_name'], true);
        $output .= input_field('inv_footer', 'Invoice Footer', $general_settings['inv_footer'], true);

        return $output;
    }
}
