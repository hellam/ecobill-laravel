<?php

namespace App\Http\Controllers\User\Roles;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class RolesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $permission_groups = PermissionGroup::with('permissions')->get();
        return view('user.roles.index', compact('permission_groups'));
    }

    public function create(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required|array',
        ]);


        if ($validator->fails()) {
            return error_web_processor(__('messages.field_correction'),
                200, validation_error_processor($validator));
        }

        Transactions::create([
            'owner_id' => $request->owner_id,
            'trx_id' => generateTrxId("SM"),
            'units' => doubleval(calculateUnits($request->amount)),
            'trx_type' => "sms_bundle",
            'trx_desc' => "Payment for SMS Bundle",
            'amount' => $request->amount,
            'pay_mode' => $request->pay_mode,
            'status' => "paid",
            'received_by' => auth('admin')->user()->username,
        ]);


        return success_web_processor(null, __('messages.msg_saved_success', ['attribute' => __('messages.transaction')]));
    }
}
