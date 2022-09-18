<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    public function index()
    {
        return view('user.reports.audit_trail');
    }

    //Data table API
    public function dt_api(Request $request)
    {
        $audit_trail = AuditTrail::orderBy('created_at');
        return (new DataTables)->eloquent($audit_trail)
            // ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.messaging.contact.edit', [$row->id]),
                    "update_url" => route('user.messaging.contact.update', [$row->id]),
                    "delete_url" => route('user.messaging.contact.delete', [$row->id])];
            })->addColumn('name', function ($row) {
                return $row->f_name . ' ' . $row->l_name;
            })->addColumn('contacts', function ($row) {
                return '<small>' . $row->email . '</small><br/>' . $row->phone;
            })->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d');
            })->filterColumn('name', function ($query, $keyword) {
                $keywords = trim($keyword);
                $query->whereRaw("CONCAT(f_name, ' ', l_name) like ?", ["%{$keywords}%"]);
            })
            ->make(true);
    }
}
