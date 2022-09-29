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
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\decode_form_data;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_active_branch;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
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

    public static function create(Request $request, $mc_type, $module, $trx_type)
    {

        $r_body['inputs'] = $request->except('remarks');
        $r_body['parameters'] = $request->route()->parameters();
        $r_body['route'] = $request->route()->getName();
        $r_body['ip_address'] = $request->getClientIp();
        $r_body['action'] = $request->route()->getAction()['controller'];
        $r_body['method'] = $request->route()->getActionMethod();

        $maker_trx = MakerCheckerTrx::where([
            'txt_data' => json_encode($r_body),
            'url' => $request->path(),
            'method' => $request->getMethod(),
        ])->first();

        if ($maker_trx) {
            return error_web_processor(__('messages.msg_similar_data_exists'));
        }

        MakerCheckerTrx::create([
            'mc_type' => $mc_type,
            'trx_type' => $trx_type,
            'status' => 'pending',
            'txt_data' => json_encode($r_body),
            'method' => $request->getMethod(),
            'module' => $module,
            'url' => $request->path(),
            'description' => $request->remarks,
            'maker' => auth('user')->id(),
            'client_ref' => get_user_ref(),
            'branch_id' => get_active_branch()
        ]);

        return success_web_processor(null, __('messages.msg_data_submitted_4_supervision'));
    }

    public static function update(Request $request, $id, $action)
    {
        $maker_checker_trx = MakerCheckerTrx::find($id);

        if (!$maker_checker_trx) {
            return error_web_processor(__('messages.msg_trx_not_found'));
        }

        if ($action == 'reject') {
            $maker_checker_trx->delete();
            return success_web_processor(null, __('messages.msg_data_rejected'));
        }


        if ($maker_checker_trx->mc_type == 0 || $maker_checker_trx->checker1 != null) {//push and delete
            $data = json_decode($maker_checker_trx->txt_data, true);

            $request->merge($data['inputs']);
            foreach ($data['parameters'] as $key => $value)
                $request->route()->setParameter($key, $value);

            //pass extra parameters for logs
            $extra_params = [
                'created_at' => $maker_checker_trx->created_at,
                'created_by' => User::find($maker_checker_trx->maker)->username,
                'supervised_by' => auth('user')->user()->username,
                'supervised_at' => Carbon::now()
            ];
            $params = array_merge($data['parameters'], $extra_params);

            //submit request to controller to create/update/delete data
            $response = app()->call($data['action'], $params);

            //submit request to url
            $response_data = json_decode($response->getContent(), true);

            if ($response_data['status']) {
                //Maker log
                log_activity(
                    $maker_checker_trx->trx_type,
                    $data['ip_address'],
                    $data['method'],
                    json_encode($data['inputs']),
                    $maker_checker_trx->maker,
                    $maker_checker_trx->method == "POST" ? $response_data['data']['id'] :
                        $data['parameters']['id']
                );//Maker log
                log_activity(
                    $maker_checker_trx->trx_type,
                    $data['ip_address'],
                    'Supervised: ' . $data['method'],
                    json_encode($data['inputs']),
                    auth('user')->id(),
                    $maker_checker_trx->method == "POST" ? $response_data['data']['id'] :
                        $data['parameters']['id']
                );

                $maker_checker_trx->delete();
            }


            return $response_data;
        } else {//update checker1 supervision
            $maker_checker_trx->checker1 = auth('user')->id();
            $maker_checker_trx->update();
            return success_web_processor(null, __('messages.msg_data_submitted_4_supervision'));
        }

    }
}
