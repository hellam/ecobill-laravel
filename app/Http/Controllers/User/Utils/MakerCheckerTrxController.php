<?php

namespace App\Http\Controllers\User\Utils;

use App\Http\Controllers\Controller;
use App\Models\MakerCheckerTrx;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\success_web_processor;

class MakerCheckerTrxController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('user.utils.maker_checker');
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = MakerCheckerTrx::orderBy('created_at', 'desc');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->editColumn('trx_type', function ($row) {
                return constant('AUD_' . $row->trx_type);
            })->addColumn('status', function ($row) {
                if ($row->status == 'pending')
                    return '<div class="badge badge-light-warning">Pending Approval</div>';
                else
                    return '<div class="badge badge-light-danger">' . $row->status . '</div>';
            })->editColumn('maker', function ($row) {
                return User::where('id', $row->maker)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
    }

    public static function create(Request $request, $mc_type)
    {
        MakerCheckerTrx::create([
            'mc_type' => $mc_type,
            'trx_type' => '',
            'status' => 'pending',
            'trx_data' => json_encode($request->all()),
            'file_data' => $request->getMethod(),
            'url' => url()->full(),
            'description' => '',
            'maker' => auth('user')->id(),
            'client_ref' => get_user_ref()
        ]);

        return success_web_processor(null, "Data forwarded successfully for Approval");
    }
}
