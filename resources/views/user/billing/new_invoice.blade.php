@extends('layout.user.app')
@section('title', 'New Invoice')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.new_invoice')}}</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{route('user.dashboard')}}" class="text-muted text-hover-primary">{{__('messages.home')}}</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">{{__('messages.billing')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.new_invoice')}}</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
@stop
@section('content')
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Toolbar-->
        <div class="toolbar d-flex flex-stack flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column py-1">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center my-1">
                    <span class="text-dark fw-bolder fs-1">{{__('messages.new_invoice')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <!--begin::Card-->
                <div class="card shadow">
                    <!--begin::Card body-->
                    <div class="card-body p-12" id="kt_block_ui_1_target">
                        <!--begin::Form-->
                        <form action="" id="kt_invoice_form" data-kt-date-format="{{get_js_date_format()}}">
                            <!--begin::Wrapper-->
                            <div class="row">
                                <div class="col-md-6">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-equal fw-row">
                                        <span class="fs-2x fw-bold text-gray-800">{{__('messages.invoice')}} #</span>
                                        <input type="text" name="reference"
                                               class="form-control form-control-flush fw-bold text-muted fs-3 w-125px"
                                               data-kt-src="{{route('user.ref_gen', ST_INVOICE)}}"
                                               placeholder="..." data-bs-toggle="tooltip" data-bs-trigger="hover"
                                               title="Enter invoice number"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Date-->
                                        <div class="fs-6 fw-bold text-gray-700 text-nowrap me-3">Date:</div>
                                        <!--end::Date-->
                                        <!--begin::Input-->
                                        <!--begin::Datepicker-->
                                        <input class="form-control form-control-sm form-control-solid fw-bold w-auto"
                                               placeholder="Select date" name="invoice_date"
                                               data-bs-toggle="tooltip" data-bs-trigger="hover"
                                               title="Specify invoice date"/>
                                        <!--end::Datepicker-->
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-6 text-end">
                                    <img src="{{Auth::user()->logo()}}" alt=""
                                         onerror="this.src = '{{asset('assets/media/avatars/logo.png')}}'"
                                         class="w-25"/>
                                </div>
                            </div>
                            <!--end::Top-->
                            <!--begin::Wrapper-->
                            <div class="mb-0">
                                <!--begin::Row-->
                                <div class="row gx-10 mb-5">
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Bill From</label>
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <input type="text" class="form-control form-control-solid"
                                                   placeholder="Name" value="{{get_company_setting('company_name')}}"/>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            @php
                                                $company = unserialize(session('branch_obj'))
                                            @endphp
                                            <textarea name="notes" class="form-control form-control-solid" rows="3"
                                                      placeholder="Company details">{{ $company->email.",\n". $company->phone.",\n". $company->address}}</textarea>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Bill To</label>
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <!--begin::Input-->
                                            <select name="customer"
                                                    id="customer"
                                                    aria-label="Select Customer"
                                                    data-kt-src="{{route('user.customers.branch.select_api')}}"
                                                    data-control="select2"
                                                    data-placeholder="Select Customer"
                                                    data-kt-fx-url="{{route('user.banking_gl.fx.rate')}}"
                                                    class="form-select form-select-sm form-select-solid fw-bolder select_customer">
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <textarea name="customer_details" class="form-control form-control-solid"
                                                      rows="3" id="customer_details"
                                                      placeholder="Customer details"></textarea>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-5"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <!--begin::Input-->
                                        <div class="fv-row">
                                            <!--begin::Date-->
                                            <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Payment
                                                Terms:
                                            </div>
                                            <select name="pay_terms"
                                                    aria-label="Select Payment Terms"
                                                    data-control="select2"
                                                    data-kt-select-url="{{route('user.banking_gl.banking.accounts.select_api')}}"
                                                    data-placeholder="Select Payment Terms"
                                                    data-kt-date-format="{{get_js_date_format()}}"
                                                    class="form-select form-select-sm form-select-solid fw-bolder select_terms">
                                                <option></option>
                                                @foreach($payment_terms as $pay_terms)
                                                    <option
                                                        value="{{$pay_terms->id}}" data-kt-days="{{$pay_terms->days}}"
                                                        data-kt-type="{{$pay_terms->type}}">{{$pay_terms->terms}}</option>
                                                @endforeach
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Input group-->
                                        <div class="fv-row">
                                            <!--begin::Date-->
                                            <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Due Date:
                                            </div>
                                            <!--end::Date-->
                                            <!--begin::Input-->
                                            <!--begin::Datepicker-->
                                            <input
                                                class="form-control form-control-sm form-control-solid fw-bold w-auto"
                                                placeholder="Select date" name="invoice_due_date"
                                                data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                title="Specify invoice due date"/>
                                            <!--end::Datepicker-->
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 fv-row" id="fx_area"></div>
                                            <div class="col-md-6 fv-row" id="bank_area"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mt-5 mb-10"></div>
                                <!--end::Separator-->
                                <!--begin::Table wrapper-->
                                <div class="table-responsive mb-10">
                                    <!--begin::Table-->
                                    <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700 repeater_items">
                                        <!--begin::Table head-->
                                        <thead>
                                        <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                            <th class="min-w-300px w-475px">Item</th>
                                            <th class="min-w-100px w-100px">QTY</th>
                                            <th class="min-w-150px w-150px">Price</th>
                                            <th class="min-w-100px w-150px text-end">Total</th>
                                            <th class="min-w-75px w-75px text-end">Action</th>
                                        </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody data-repeater-list="invoice_items">
                                        <tr data-repeater-item class="border-bottom border-bottom-dashed">
                                            <td class="pe-7">
                                                <select name="product"
                                                        aria-label="Select Product"
                                                        data-kt-src="{{route('user.products.select_api', 1)}}"
                                                        data-placeholder="Select Product"
                                                        data-kt-add-product="product_select"
                                                        class="form-select form-select-sm form-select-solid fw-bolder select_api select_product">
                                                </select>
                                                {{--                                                <div class="inner-repeater">--}}
                                                {{--                                                    <div data-repeater-list class="mb-5">--}}
                                                {{--                                                        <div data-repeater-item style="display:none;">--}}
                                                {{--                                                            <label class="form-label">Description</label>--}}
                                                {{--                                                            <div class="input-group has-validation pb-3">--}}
                                                {{--                                                                <textarea type="text" class="form-control"--}}
                                                {{--                                                                          placeholder="Enter description" name="description"></textarea>--}}
                                                {{--                                                                <button--}}
                                                {{--                                                                    class="btn btn-sm btn-icon btn-light-danger align-self-center ms-3"--}}
                                                {{--                                                                    data-repeater-delete type="button" style="border-top-left-radius: 0.425rem; border-bottom-left-radius: 0.425rem;">--}}
                                                {{--                                                                    <i class="fa fa-times"></i>--}}
                                                {{--                                                                </button>--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <button class="btn btn-sm btn-light-primary" data-repeater-create--}}
                                                {{--                                                            type="button">--}}
                                                {{--                                                        <i class="la la-plus"></i> Add description--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </div>--}}
                                            </td>
                                            <td class="ps-0">
                                                <input class="form-control form-control-solid quantity" type="number"
                                                       min="1"
                                                       name="quantity" placeholder="1" value="1"/>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control form-control-solid text-end amount"
                                                       name="price" placeholder="0.00" value="0.00"/>
                                            </td>
                                            <td class="pt-8 text-end text-nowrap">
                                                <span class="total">$0.00</span></td>
                                            <td class="pt-5 text-end">
                                                <button type="button" data-repeater-delete=""
                                                        class="btn btn-sm btn-icon btn-light-danger delete_row"><i
                                                        class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                        <!--begin::Table foot-->
                                        <tfoot>
                                        <tr class="border-top border-top-dashed align-top fs-6 fw-bold text-gray-700">
                                            <th class="text-primary">
                                                <button type="button" data-repeater-create
                                                        class="btn btn-sm btn-primary" id="add_row">
                                                    <i class="fa fa-add"></i>Add Item
                                                </button>
                                            </th>
                                            <th colspan="2" class="border-bottom border-bottom-dashed ps-0">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div class="fs-5">Subtotal</div>
                                                    <button class="btn btn-link py-1" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" title="Coming soon">Add tax
                                                    </button>
                                                    <button class="btn btn-link py-1" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" title="Coming soon">Add discount
                                                    </button>
                                                </div>
                                            </th>
                                            <th colspan="2" class="border-bottom border-bottom-dashed text-end">
                                                <span id="sub-total">$0.00</span></th>
                                        </tr>
                                        <tr class="align-top fw-bold text-gray-700">
                                            <th></th>
                                            <th colspan="2" class="fs-4 ps-0">Total</th>
                                            <th colspan="2" class="text-end fs-4 text-nowrap">$
                                                <span data-kt-element="grand-total">0.00</span></th>
                                        </tr>
                                        </tfoot>
                                        <!--end::Table foot-->
                                    </table>
                                </div>
                                <!--end::Table-->
                                <!--begin::Item template-->
                                <table class="table d-none" data-kt-element="item-template">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7">
                                            <input type="text" class="form-control form-control-solid mb-2"
                                                   name="name[]" placeholder="Item name"/>
                                            <input type="text" class="form-control form-control-solid"
                                                   name="description[]" placeholder="Description"/>
                                        </td>
                                        <td class="ps-0">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   name="quantity[]" placeholder="1" data-kt-element="quantity"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-solid text-end"
                                                   name="price[]" placeholder="0.00" data-kt-element="price"/>
                                        </td>
                                        <td class="pt-8 text-end">$
                                            <span data-kt-element="total">0.00</span></td>
                                        <td class="pt-5 text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    data-kt-element="remove-item">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                <span class="svg-icon svg-icon-3">
                                                    <svg width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                            fill="currentColor"/>
                                                        <path opacity="0.5"
                                                              d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                              fill="currentColor"/>
                                                        <path opacity="0.5"
                                                              d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                              fill="currentColor"/>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table d-none" data-kt-element="empty-template">
                                    <tr data-kt-element="empty">
                                        <th colspan="5" class="text-muted text-center py-10">No items</th>
                                    </tr>
                                </table>
                                <!--end::Item template-->
                                <!--begin::Notes-->
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bold text-gray-700">Notes</label>
                                    <textarea name="notes" class="form-control form-control-solid" rows="3"
                                              placeholder="Thanks for your business"></textarea>
                                </div>
                                <!--end::Notes-->
                            </div>
                            <!--end::Wrapper-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Layout-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/billing/invoices/add.js') }}"></script>
    <script src="{{ asset('assets/js/pages/billing/invoices/functions.js') }}"></script>
@endpush
