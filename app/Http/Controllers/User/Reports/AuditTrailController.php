<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditTrailController extends Controller
{
    public function index(): Factory|View|Application
    {
        $audit_trail_count = AuditTrail::count() ?? 0;
        return view('user.reports.audit_trail', compact('audit_trail_count'));
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = AuditTrail::select('type', 'user', 'api_token', 'created_at', 'description')->orderBy('created_at','desc');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn('id')
            ->editColumn('type', function ($row) {
                return constant('AUD_' . $row->type);
            })->addColumn('request_type', function ($row) {
                return $row->api_token == null ? 'Web' : 'API';
            })->editColumn('user', function ($row) {
                return User::where('id', $row->user)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })->filterColumn('user', function ($query, $keyword) {
                $keywords = trim($keyword);
                $query->whereRaw("CONCAT(f_name, ' ', l_name) like ?", ["%{$keywords}%"]);
            })
            ->make(true);
    }
}
