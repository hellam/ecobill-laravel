<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\ChartClass;
use App\Models\ChartGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class GLAccountsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $gl_classes = ChartClass::select('class_name','id')->get();
        $gl_groups = ChartGroup::select('name','id')->get();
        $gl_accounts_count = ChartAccount::count() ?? 0;
        $gl_groups_count = ChartGroup::count() ?? 0;
        $gl_classes_count = ChartClass::count() ?? 0;
        return view('user.banking_gl.gl_maintenance', compact('gl_accounts_count', 'gl_groups_count', 'gl_classes_count','gl_classes', 'gl_groups'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = ChartAccount::with('group')->orderBy('account_name');

        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id,
                    "edit_url" => route('user.banking_gl.gl_accounts.edit', [$row->id]),
                    "update_url" => route('user.banking_gl.gl_accounts.update', [$row->id]),
                    "delete_url" => route('user.banking_gl.gl_accounts.delete', [$row->id])
                ];
            })
            ->editColumn('account_group', function ($row) {
                return $row->group->name ?? "";
            })
            ->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->make(true);
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param $created_at
     * @param $created_by
     * @param $supervised_by
     * @param $supervised_at
     * @return JsonResponse
     */

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {

        $validator = UserValidators::glAccountsCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_group' => $request->account_group,
            'client_ref' => get_user_ref()
        ];
        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $chart_account = ChartAccount::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_GL_ACCOUNT_SETUP,
                $request->getClientIp(),
                'Create Chart Account',
                json_encode($post_data),
                auth('user')->id(),
                $chart_account->id
            );
        }

        return success_web_processor(['id' => $chart_account->id], __('messages.msg_saved_success', ['attribute' => __('messages.new_gl_account')]));
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $chart_account = ChartAccount::find($id);
        if (isset($chart_account)) {
            return success_web_processor($chart_account, __('messages.msg_item_found', ['attribute' => __('messages.gl_account')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.gl_account')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null)
    {
        $validator = UserValidators::glAccountsUpdateValidation($request);

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
