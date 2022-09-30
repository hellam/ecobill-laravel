<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\ChartClass;
use App\Models\ChartGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\decode_form_data;

class GLAccountsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $gl_classes = ChartClass::select('class_name','id')->get();
        $gl_accounts_count = ChartAccount::count() ?? 0;
        $gl_groups_count = ChartGroup::count() ?? 0;
        $gl_classes_count = ChartClass::count() ?? 0;
        return view('user.banking_gl.gl_maintenance', compact('gl_accounts_count', 'gl_groups_count', 'gl_classes_count','gl_classes'));
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

}
