<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\ChartClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\success_web_processor;

class GLClassController extends Controller
{

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $chart_class = ChartClass::orderBy('name');

        return (new DataTables)->eloquent($chart_class)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id,
                    "edit_url" => route('user.banking_gl.gl_class.edit', [$row->id]),
                    "update_url" => route('user.banking_gl.gl_class.update', [$row->id]),
                    "delete_url" => route('user.banking_gl.gl_class.delete', [$row->id])
                ];
            })
            ->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->make(true);
    }

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {

        $validator = UserValidators::glClassCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'class_name' => $request->class_name,
            'client_ref' => get_user_ref()
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $chart_class = ChartClass::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_GL_ACCOUNT_SETUP,
                $request->getClientIp(),
                'Create Chart Class',
                json_encode($post_data),
                auth('user')->id(),
                $chart_class->id
            );
        }

        return success_web_processor(['id' => $chart_class->id], __('messages.msg_saved_success', ['attribute' => __('messages.new_gl_class')]));
    }

}
