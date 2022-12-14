<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\ChartGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GLGroupsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $chart_class = ChartGroup::with('classes')->orderBy('name');
        return (new DataTables)->eloquent($chart_class)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id,
                    "edit_url" => route('user.banking_gl.gl_groups.edit', [$row->id]),
                    "update_url" => route('user.banking_gl.gl_groups.update', [$row->id]),
                    "delete_url" => route('user.banking_gl.gl_groups.delete', [$row->id])
                ];
            })
            ->addColumn('class_name', function ($row) {
                return $row->classes->class_name;
            })
            ->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->make(true);
    }

    /**
     * @param Request $request
     * @param $created_at
     * @param $created_by
     * @param $supervised_by
     * @param $supervised_at
     * @return JsonResponse
     */

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {

        $validator = UserValidators::glGroupCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'name' => $request->name,
            'class_id' => $request->class_id,
            'client_ref' => get_user_ref()
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $chart_group = ChartGroup::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_GL_ACCOUNT_SETUP,
                $request->getClientIp(),
                'Create Chart Group',
                json_encode($post_data),
                auth('user')->id(),
                $chart_group->id
            );
        }

        return success_web_processor(['id' => $chart_group->id], __('messages.msg_saved_success', ['attribute' => __('messages.new_gl_group')]));
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $chart_group = ChartGroup::find($id);
        if (isset($chart_group)) {
            return success_web_processor($chart_group, __('messages.msg_item_found', ['attribute' => __('messages.gl_group')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.gl_group')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::glGroupUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $chart_group = ChartGroup::find($id);
        $chart_group = set_update_parameters($chart_group, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $chart_group->name = $request->name;
        $chart_group->class_id = $request->class_id;
        $chart_group->inactive = $request->inactive;
        $chart_group->update();
//
        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.gl_group')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $chart_class = ChartGroup::find($id);
        if (isset($chart_class)) {
            $chart_account = ChartAccount::where('account_group', $id)->count();
            if ($chart_account > 0) {
                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.gl_group'), 'attribute1' => __('messages.gl_account')]));
            }
            $chart_class->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.gl_group')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.gl_group')]));
    }
}
