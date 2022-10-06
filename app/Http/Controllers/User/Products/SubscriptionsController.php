<?php

namespace App\Http\Controllers\User\Products;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Tax;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $sub_packages = Subscription::count() ?? 0;
        return view('user.products.subscriptions', compact('sub_packages'));
    }


    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $subscription = Subscription::with('product')->orderBy('name');
        return (new DataTables)->eloquent($subscription)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.products.sub_packages.edit', [$row->id]),
                    "update_url" => route('user.products.sub_packages.update', [$row->id]),
                    "delete_url" => route('user.products.sub_packages.delete', [$row->id])];
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->addColumn('product', function ($row) {
                return $row->product->name;
            })->editColumn('validity', function ($row) {
                return $row->validity .' days';
            })->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $product = Subscription::select('name', 'id')
            ->orderBy('name')
            ->limit(10)
            ->get();
        if ($request->has('search'))
            $product = Subscription::select('name', 'id')
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orderBy('name')
                ->limit(10)
                ->get();

        return response()->json($product, 200);
    }

    /**
     * @param Request $request
     * @param null $created_at
     * @param null $created_by
     * @param null $supervised_by
     * @param null $supervised_at
     * @return JsonResponse
     */

    public function create(Request $request, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse
    {
        $validator = UserValidators::subscriptionCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $fileName = '';
        if ($request->filled('image')) {
            $requestImage = $request->image; //your base64 encoded
            try {
                $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/packages');
            } catch (\Exception $exception) {
                return error_web_processor('Invalid image file',
                    200, ['field' => 'image', 'error' => 'Invalid Image file']);
            }
        }

        $post_data = [
            'product_id' => $request->product_id,
            'name' => $request->name,
            'image' => $fileName,
            'description' => $request->description,
            'features' => $request->features,
            'price' => $request->price,
            'order' => $request->order,
            'cost' => $request->cost,
            'validity' => $request->validity,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $subscription = Subscription::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_SUBSCRIPTION_SETUP,
                $request->getClientIp(),
                'Create Subscription Package',
                json_encode($post_data),
                auth('user')->id(),
                $subscription->id
            );
        }

        return success_web_processor(['id' => $subscription->id], __('messages.msg_saved_success', ['attribute' => __('messages.package')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscription = Subscription::with('product:id,name,barcode,id')->find($id);
        $subscription->image = get_file_url('packages',$subscription->image);
        if (isset($subscription)) {
            return success_web_processor($subscription, __('messages.msg_item_found', ['attribute' => __('messages.package')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.package')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::subscriptionUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $subscription = Subscription::find($id);
        $subscription = set_update_parameters($subscription, $created_at, $created_by, $supervised_by, $supervised_at);

        $fileName = $request->delete == 0 ? $subscription->image : delete_file('packages', $subscription->image);
        if ($request->filled('image')) {
            $requestImage = $request->image; //your base64 encoded
            try {
                $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/packages');
            } catch (\Exception $exception) {
                return error_web_processor('Invalid image file',
                    200, ['field' => 'image', 'error' => 'Invalid Image file']);
            }
        }
        $subscription->product_id = $request->product_id;
        $subscription->name = $request->name;
        $subscription->image = $fileName;
        $subscription->description = $request->description;
        $subscription->features = $request->features;
        $subscription->price = $request->price;
        $subscription->cost = $request->cost;
        $subscription->order = $request->order;
        $subscription->validity = $request->validity;
        $subscription->inactive = $request->inactive;
        $subscription->update();

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.product')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $subscription = Subscription::find($id);
        if (isset($subscription)) {
            //TODO: check if subscription has transactions
//            $products = Product::where('category_id', $id)->count();
//            if ($products > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.category'), 'attribute1' => __('messages.products')]));
//            }
            $subscription->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.package')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.package')]));
    }
}
