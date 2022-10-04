<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\store_base64_image;
use function App\CentralLogics\success_web_processor;

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
                $output .= select('default_currency','Select Currency', CURRENCY);
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
                        $fileName = store_base64_image($requestImage, $fileName, get_user_ref().'/users');
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
