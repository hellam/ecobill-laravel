<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditTrailController extends Controller
{
    public function index(): Factory|View|Application
    {
        $audit_trail_count = AuditTrail::count();
        return view('user.reports.audit_trail', compact('audit_trail_count'));
    }

    //Data table API
    public function dt_api(Request $request)
    {
        $audit_trail = AuditTrail::orderBy('created_at');
        return (new DataTables)->eloquent($audit_trail)
            // ->addIndexColumn()
            ->editColumn('type', function ($row) {
                return ${'AUD_'.$row->type};
            })->addColumn('request_type', function ($row) {
                return $row->api==null ? 'Web' : 'API';
            })->editColumn('user', function ($row) {
                return User::where('id', $row->user)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }
}
