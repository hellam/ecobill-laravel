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
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\success_web_processor;

class MakerCheckerTrxController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('user.utils.unsupervised_data');
    }

    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        if (env('APP_ENV') == 'production')
            $audit_trail = MakerCheckerTrx::where('maker', '!=', auth('user')->id())
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
                return '';
            })->editColumn('trx_type', function ($row) {
                return ["trx_type" => $row->trx_type == '' ? '' : constant($row->trx_type),
                    "html_data" => "Test HTML Data"];
            })
            ->editColumn('maker', function ($row) {
                return User::where('id', $row->maker)->first()->username;
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })->make(true);
    }

    public static function create(Request $request, $mc_type, $module, $trx_type)
    {
        $maker_trx = MakerCheckerTrx::where([
            'txt_data' => json_encode($request->all()),
            'url' => url()->full(),
            'method' => $request->getMethod(),
        ])->first();

        if ($maker_trx) {
            return error_web_processor(__('messages.msg_similar_data_exists'));
        }

        MakerCheckerTrx::create([
            'mc_type' => $mc_type,
            'trx_type' => $trx_type,
            'status' => 'pending',
            'txt_data' => json_encode($request->except(['remarks'])),
            'method' => $request->getMethod(),
            'module' => $module,
            'url' => url()->full(),
            'description' => $request->remarks,
            'maker' => auth('user')->id(),
            'client_ref' => get_user_ref()
        ]);

        return success_web_processor(null, __('messages.msg_data_submitted_4_supervision'));
    }
}
