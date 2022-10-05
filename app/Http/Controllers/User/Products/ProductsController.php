<?php

namespace App\Http\Controllers\User\Products;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
        return view('user.products.products', compact('products', 'tax'));
    }


    //Data table API
    public function dt_api(Request $request): JsonResponse
    {
        $audit_trail = Product::with('category', 'tax')->orderBy('name');
        return (new DataTables)->eloquent($audit_trail)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return ["id" => $row->id, "edit_url" => route('user.products.edit', [$row->id]),
                    "update_url" => route('user.products.update', [$row->id]),
                    "delete_url" => route('user.products.delete', [$row->id])];
            })->editColumn('inactive', function ($row) {
                return $row->inactive == 0 ? '<div class="badge badge-sm badge-light-success">Active</div>' : '<div class="badge badge-sm badge-light-danger">Inactive</div>';
            })->addColumn('category', function ($row) {
                return $row->category->name;
            })->addColumn('tax', function ($row) {
                return $row->tax->name.': '.$row->tax->rate.'%';
            })->editColumn('type', function ($row) {
                return $row->type_name();
            })->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $product = Product::select('name', 'id', 'default_tax_id')
            ->orderBy('name')
            ->limit(10)
            ->get();
        if ($request->has('search'))
            $product = Category::select('name', 'id', 'default_tax_id')
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
        $validator = UserValidators::productCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $fileName = '';
        if ($request->filled('image')) {
            $requestImage = $request->image; //your base64 encoded
            try {
                $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/products');
            } catch (\Exception $exception) {
                return error_web_processor('Invalid image file',
                    200, ['field' => 'image', 'error' => 'Invalid Image file']);
            }
        }

        $post_data = [
            'barcode' => $request->barcode,
            'name' => $request->name,
            'image' => $fileName,
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
        $product = Product::with('category:id,name,id')->find($id);
        $product->image = get_file_url('products',$product->image);
        if (isset($product)) {
            return success_web_processor($product, __('messages.msg_item_found', ['attribute' => __('messages.product')]));
        }
        return error_web_processor(trans('messages.msg_item_not_found', ['attribute' => __('messages.product')]));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id, $created_at = null, $created_by = null,
                                   $supervised_by = null, $supervised_at = null): JsonResponse|string
    {
        $validator = UserValidators::productUpdateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $products = Product::find($id);
        $products = set_update_parameters($products, $created_at, $created_by, $supervised_by, $supervised_at);

        $fileName = $products->image;
        if ($request->filled('image')) {
            $requestImage = $request->image; //your base64 encoded
            try {
                $fileName = store_base64_image($requestImage, $fileName, get_user_ref() . '/products');
            } catch (\Exception $exception) {
                return error_web_processor('Invalid image file',
                    200, ['field' => 'image', 'error' => 'Invalid Image file']);
            }
        }
        $products->name = $request->name;
        $products->image = $fileName;
        $products->description = $request->description;
        $products->price = $request->price;
        $products->cost = $request->cost;
        $products->order = $request->order;
        $products->category_id = $request->category_id;
        $products->tax_id = $request->tax_id;
        $products->inactive = $request->inactive;
        $products->update();

        return success_web_processor(null, __('messages.msg_updated_success', ['attribute' => __('messages.product')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $products = Product::find($id);
        if (isset($products)) {
            //TODO: check if product has transactions
//            $products = Product::where('category_id', $id)->count();
//            if ($products > 0) {
//                return error_web_processor(__('messages.msg_delete_not_allowed', ['attribute' => __('messages.category'), 'attribute1' => __('messages.products')]));
//            }
            $products->delete();
            return success_web_processor(null, __('messages.msg_deleted_success', ['attribute' => __('messages.product')]));
        }
        return error_web_processor(__('messages.msg_item_not_found', ['attribute' => __('messages.product')]));
    }
}
