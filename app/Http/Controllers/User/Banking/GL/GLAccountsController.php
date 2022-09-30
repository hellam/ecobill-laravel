<?php

namespace App\Http\Controllers\User\Banking\GL;

use App\Http\Controllers\Controller;
use App\Models\ChartAccount;
use App\Models\ChartClass;
use App\Models\ChartGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;

class GLAccountsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $gl_accounts_count = ChartAccount::count() ?? 0;
        $gl_groups_count = ChartGroup::count() ?? 0;
        $gl_classes_count = ChartClass::count() ?? 0;
        return view('user.banking_gl.gl_maintenance', compact('gl_accounts_count', 'gl_groups_count', 'gl_classes_count'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        if (env('APP_ENV') == 'production')
            $audit_trail = ChartAccount::where('maker', '!=', auth('user')->id())
                ->orderBy('created_at', 'desc');
        else
            $audit_trail = MakerCheckerTrx::orderBy('created_at', 'desc');

        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->editColumn('method', function ($row) {
                if ($row->method == 'POST')
                    return 'Create';
                else if ($row->method == 'PUT')
                    return 'Update';
                else if ($row->method == 'DELETE')
                    return 'Delete';
                return 'UNKNOWN';
            })->editColumn('trx_type', function ($row) {
                return ["trx_type" => $row->trx_type == '' ? '' : constant($row->trx_type),
                    "html_data" => decode_form_data(json_decode($row->txt_data, true), $row->trx_type, $row->method),
                    "approve_url" => route('user.utils.unsupervised_data.update', [$row->id, 'approve']),
                    "reject_url" => route('user.utils.unsupervised_data.update', [$row->id, 'reject']),
                ];
            })->editColumn('maker', function ($row) {
                return User::where('id', $row->maker)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })->make(true);
    }

}
