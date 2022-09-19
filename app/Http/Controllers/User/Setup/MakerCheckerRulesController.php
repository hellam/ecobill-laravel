<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\MakerCheckerRule;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\success_web_processor;
use function App\CentralLogics\validation_error_processor;

class MakerCheckerRulesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $maker_checker_count = MakerCheckerRule::count();
        return view('user.setup.maker_checker', compact('maker_checker_count'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = MakerCheckerRule::orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->editColumn('maker_type', function ($row) {
                return $row->status == 0 ? 'Single Maker Checker' : 'Double Maker Checker';
            })->editColumn('permission_code', function ($row) {
                return Permission::where('code', $row->permission_code)->first()->name;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }

    public function create(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'action' => 'required|unique:' . MakerCheckerRule::class . ',permission_code,NULL,id,client_ref,' . get_user_ref(),
            'maker_type' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return error_web_processor(__('messages.field_correction'),
                200, validation_error_processor($validator));
        }

        MakerCheckerRule::create([
            'name' => $request->name,
            'permissions' => $request->permissions,
            'client_ref' => get_user_ref(),
            'created_by' => auth('user')->user()->username,
        ]);


        return success_web_processor(null, __('messages.msg_saved_success', ['attribute' => __('messages.role')]));
    }


}
