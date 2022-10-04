<?php

namespace App\Http\Controllers\User\Products;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function App\CentralLogics\error_web_processor;
use function App\CentralLogics\get_user_ref;
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $products = Product::count() ?? 0;
        $tax = Tax::all();
        $categories = Category::all();
        return view('user.products.products', compact('products', 'tax', 'categories'));
    }




    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = Category::orderBy('name');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.products.categories.edit', [$row->id]),
                    "update_url" => route('user.products.categories.update', [$row->id]),
                    "delete_url" => route('user.products.categories.delete', [$row->id])];
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y/m/d H:i:s');
            })
            ->make(true);
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
        $validator = UserValidators::productCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'barcode' => $request->barcode,
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'price' => $request->price,
            'cost' => $request->cost,
            'order' => $request->order,
            'category_id' => $request->category_id,
            'tax_id' => $request->tax_id,
            'type' => $request->type,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $product = Product::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_PRODUCT_SETUP,
                $request->getClientIp(),
                'Create Product',
                json_encode($post_data),
                auth('user')->id(),
                $product->id
            );
        }

        return success_web_processor(['id' => $product->id], __('messages.msg_saved_success', ['attribute' => __('messages.product')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if (isset($category)) {
            return success_web_processor($category, __('messages.msg_item_found', ['attribute' => __('messages.category')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.category')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::categoryUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $category = Category::find($id);
        $category = set_update_parameters($category, $created_at, $created_by, $supervised_by, $supervised_at);

        $category->name = $request->name;
        $category->description = $request->description;
        $category->default_tax_id = $request->default_tax_id;
        $category->inactive = $request->inactive;
        $category->update();

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.category')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (isset($category)) {
            $products = Product::where('category_id', $id)->count();
            if ($products > 0) {
                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.category'), 'attribute1' => __('messages.products')]));
            }
            $category->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.category')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.category')]));
    }
}
