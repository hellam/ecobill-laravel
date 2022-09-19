<?php

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\Controller;
use App\Models\MakerCheckerRule;
use App\Models\MakerCheckerTrx;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MakerCheckerRulesController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('user.setup.maker_checker');
    }
    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = MakerCheckerRule::orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->editColumn('maker_type', function ($row) {
                return $row->status==0 ? 'Single Maker Checker' : 'Double Maker Checker';
            })->editColumn('permission_code', function ($row) {
                return Permission::where('code', $row->permission_code)->first()->name;
            })->editColumn('created_by', function ($row) {
                return User::where('id', $row->created_by)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }
}
