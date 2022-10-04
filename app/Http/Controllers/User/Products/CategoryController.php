<?php

namespace App\Http\Controllers\User\Products;

use App\CentralLogics\UserValidators;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MakerCheckerRule;
use App\Models\PaymentTerm;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tax;
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
use function App\CentralLogics\log_activity;
use function App\CentralLogics\set_create_parameters;
use function App\CentralLogics\set_update_parameters;
use function App\CentralLogics\success_web_processor;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $taxes = Tax::all();
        $categories_count = Category::count();
        return view('user.products.categories', compact('taxes', 'categories_count'));
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
     * Show the form for editing the specified resource.
     *
     */
    public function select_api(Request $request): JsonResponse
    {
        $category = Category::select('name', 'id', 'default_tax_id')
            ->orderBy('name')
            ->limit(10)
            ->get();
        if ($request->has('search'))
            $category = Category::select('name', 'id', 'default_tax_id')
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orderBy('name')
                ->limit(10)
                ->get();

        return response()->json($category, 200);
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
        $validator = UserValidators::categoryCreateValidation($request);

        if ($validator != '') {
            return $validator;
        }

        $post_data = [
            'name' => $request->name,
            'description' => $request->description,
            'default_tax_id' => $request->default_tax_id,
            'client_ref' => get_user_ref()
        ];

        //set_create_parameters($created_at, $created_by, ...)
        $post_data = array_merge($post_data, set_create_parameters($created_at, $created_by, $supervised_by, $supervised_at));

        $category = Category::create($post_data);

        if ($created_at == null) {
            //if not supervised, log data from create request
            //Creator log
            log_activity(
                ST_CATEGORY_SETUP,
                $request->getClientIp(),
                'Create Category',
                json_encode($post_data),
                auth('user')->id(),
                $category->id
            );
        }

        return success_web_processor(['id' => $category->id], __('messages.msg_saved_success', ['attribute' => __('messages.category')]));
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
