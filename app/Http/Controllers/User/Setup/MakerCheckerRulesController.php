<?php

namespace App\Http\Controllers\User\Setup;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\MakerCheckerRule;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class MakerCheckerRulesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $maker_checker_rules_count = MakerCheckerRule::count();
        $permissions = Permission::where('requires_maker_checker', true)->get();
        $users = User::all();
        return view('user.setup.maker_checker_rules', compact('maker_checker_rules_count', 'permissions', 'users'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = MakerCheckerRule::orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.setup.maker_checker_rules.edit', [$row->id]),
                    "update_url" => route('user.setup.maker_checker_rules.update', [$row->id]),
                    "delete_url" => route('user.setup.maker_checker_rules.delete', [$row->id])];
            })->editColumn('maker_type', function ($row) {
                return $row->maker_type == 0 ? 'Single Maker Checker' : 'Double Maker Checker';
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->editColumn('permission_code', function ($row) {
                return Permission::where('code', $row->permission_code)->first()->name;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::makerCheckerRuleCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }
        $post_data = [
            'maker_type' => $request->maker_type,
            'permission_code' => $request->action,
            'client_ref' => get_user_ref()
        ];
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $rule = MakerCheckerRule::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_MAKER_CHECKER_RULE_SETUP,
                $request->getClientIp(),
                'Create Role',
                json_encode($post_data),
                auth('user')->id(),
                $rule->id
            );
        }

        return success_web_processor(['id' => $rule->id], __('messages.msg_saved_success', ['attribute' => __('messages.maker_checker_rule')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $rule = MakerCheckerRule::find($id);
        if (isset($rule)) {
            return success_web_processor($rule, __('messages.msg_item_found', ['attribute' => __('messages.maker_checker_rule')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.maker_checker_rule')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $validator = UserValidators::makerCheckerRuleUpdateValidation($request, $id, $created_at = null, $created_by = null,
            $supervised_by = null, $supervised_at = null);

        if ($validator != '') {
            return $validator;
        }

        $rule = MakerCheckerRule::find($id);

        //set parameters
        $rule = set_update_parameters($rule, $created_at, $created_by,
            $supervised_by, $supervised_at);

        $rule->maker_type = $request->maker_type;
        $rule->permission_code = $request->action;
        $rule->inactive = $request->inactive;
        $rule->update();

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.maker_checker_rule')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $rule = MakerCheckerRule::find($id);
        if (isset($rule)) {
            $rule->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.maker_checker_rule')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.maker_checker_rule')]));
    }


}
