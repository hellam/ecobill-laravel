<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\success_web_processor;

class UsersController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users_count = User::count() ?? 0;
        return view('user.setup.users', compact('users_count'));
    }
    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::userCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'full_name' => $request->full_name,
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $branch = User::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_BRANCH_SETUP,
                $request->getClientIp(),
                'Create Role',
                json_encode($post_data),
                auth('user')->id(),
                $branch->id
            );
        }

        return success_web_processor(['id' => $branch->id], __('messages.msg_saved_success', ['attribute' => __('messages.branch')]));
    }

}
