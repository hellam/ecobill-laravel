@extends('layout.user.app')
@section('title', 'New Invoice')
@push('custom_styles')
    <link href="{{asset('assets/plugins/snackbar/snackbar.min.css')}}" rel="stylesheet" type="text/css"
          id="stylesheet"/>
@endpush
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
        <div class="d-flex flex-column flex-lg-row overflow-hidden">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <!--begin::Card-->
                <div class="card shadow">
                    <!--begin::Card body-->
                    <div class="card-body p-12" id="kt_block_ui_1_target">
                        <!--begin::Form-->
                        <form action="" id="kt_invoice_form"
                              data-kt-action="{{route('user.billing.invoice')}}"
                              data-kt-date-format="{{get_js_date_format()}}"
                              data-kt-decimals="{{get_company_setting('price_dec')}}"
                              data-kt-tax-type="{{get_company_setting('tax_inclusive')}}">
                            <!--begin::Wrapper-->
                            <div class="row">
                                <div class="col-md-6">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-equal fv-row">
                                        <span class="fs-2x fw-bold text-gray-800">{{__('messages.invoice')}} #</span>
                                        <input type="text" name="reference"
                                               class="form-control form-control-flush fw-bold text-muted fs-3 w-125px"
                                               data-kt-src="{{route('user.ref_gen', ST_INVOICE)}}"
                                               placeholder="..." data-bs-toggle="tooltip" data-bs-trigger="hover"
                                               title="Enter invoice number"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex align-items-center fv-row">
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
                                        <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Bill To</label>
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <div class=" fv-row">
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
                                        </div>
                                        <!--end::Input group-->
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <div class="col-md-6 mb-5">
                                                <div class="fv-row">
                                                    <input class="form-control form-control-solid" type="text"
                                                           name="phone" placeholder="Customer Phone"/>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="col-md-6 mb-5">
                                                <div class="fv-row">
                                                    <input class="form-control form-control-solid" type="email"
                                                           name="email" placeholder="Customer Email"/>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <div class="fv-row">
                                                <textarea class="form-control form-control-solid" type="text"
                                                          name="address" placeholder="Customer Address"></textarea>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <div class="mb-5">
                                            <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Payment
                                                Terms:
                                            </div>
                                            <div class=" fv-row">
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
                                                            value="{{$pay_terms->id}}"
                                                            data-kt-days="{{$pay_terms->days}}"
                                                            data-kt-type="{{$pay_terms->type}}">{{$pay_terms->terms}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--end::Input-->
                                        <!--begin::Input group-->
                                        <div class="fv-row">
                                            <!--begin::Date-->
                                            <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Due Date:
                                            </div>
                                            <!--end::Date-->
                                            <!--begin::Input-->
                                            <!--begin::Datepicker-->
                                            <input
                                                class="form-control form-control-sm form-control-solid fw-bold"
                                                placeholder="Select date" name="due_date"
                                                data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                title="Specify invoice due date"/>
                                            <!--end::Datepicker-->
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-5"></div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6 fv-row" id="fx_area"></div>
                                            <div class="col-md-6 fv-row" id="bank_area"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="separator separator-dashed mt-5 mb-10"></div>
                                <!--end::Separator-->
                                <!--begin::Table wrapper-->
                                <div class="table-responsive mb-10 overflow-scroll">
                                    <h4 class="text-center invoice_header"></h4>
                                    <!--begin::Table-->
                                    <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700 repeater_items">
                                        <!--begin::Table head-->
                                        <thead>
                                        <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                            <th class="min-w-300px w-300px">Item</th>
                                            <th class="min-w-80px w-80px">QTY</th>
                                            <th class="min-w-150px w-150px">
                                                Price {{get_company_setting('tax_inclusive') == 1 ? 'After Tax' : 'Before Tax'}}</th>
                                            <th class="min-w-100px w-100px">Tax</th>
                                            <th class="min-w-100px w-150px text-end">Total</th>
                                            <th class="min-w-75px w-75px text-end">Action</th>
                                        </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody data-repeater-list="invoice_items" class="invoice_items">
                                        <tr data-repeater-item class="border-bottom border-bottom-dashed">
                                            <td class="pe-5">
                                                <div class="fv-row">
                                                    <select name="product"
                                                            aria-label="Select Product"
                                                            data-kt-src="{{route('user.products.select_api', 0)}}"
                                                            data-placeholder="Select Product"
                                                            data-kt-product="product_select"
                                                            class="form-select form-select-sm form-select-solid fw-bolder select_api select_product">
                                                    </select>
                                                </div>
                                                <div class="inner-repeater">
                                                    <div data-repeater-list class="mb-5 mt-4">
                                                        <div data-repeater-item style="display:none;">
                                                            <label class="form-label">Description</label>
                                                            <div class="input-group has-validation pb-3">
                                                                <textarea type="text"
                                                                          class="form-control form-control-solid"
                                                                          placeholder="Enter description"
                                                                          name="description"></textarea>
                                                                <button
                                                                    class="btn btn-sm btn-icon btn-light-danger align-self-center ms-3"
                                                                    data-repeater-delete type="button"
                                                                    style="border-top-left-radius: 0.425rem; border-bottom-left-radius: 0.425rem;">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-sm btn-light-primary" data-repeater-create
                                                            type="button">
                                                        <i class="la la-plus"></i> Add description
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <input class="form-control form-control-solid quantity" type="number"
                                                       min="1" name="quantity" placeholder="1" value="1"/>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control form-control-solid text-end amount"
                                                       name="price" placeholder="0.00" value="0.00"/>
                                            </td>
                                            <td>
                                                <div class="fv-row">
                                                    <select name="tax"
                                                            aria-label="Select Tax"
                                                            data-kt-repeater="select2"
                                                            data-placeholder="Select Tax"
                                                            class="form-select form-select-sm form-select-solid fw-bolder tax_select">
                                                        <option></option>
                                                        @foreach($tax as $tx)
                                                            <option value="{{$tx->id}}"
                                                                    data-kt-rate="{{$tx->rate}}">{{$tx->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="pt-8 text-end text-nowrap">
                                                <span class="total">$0.00</span>
                                            </td>
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
                                        </tr>
                                        </tfoot>
                                        <!--end::Table foot-->
                                    </table>
                                </div>
                                <!--end::Table-->
                                <!--begin::No Items-->
                                <table class="table d-none">
                                    <tr data-kt-element="empty">
                                        <th colspan="5" class="text-muted text-center py-10">You have no products</th>
                                    </tr>
                                </table>
                                <!--begin::No Items-->
                                <!--begin::Other inputs-->
                                <div class="row mb-8">
                                    <div class="col-md-6 card shadow p-4 mb-4">
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-4 col-form-label fw-semibold fs-7">Send Reminders:</label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <div class="form-check form-check-custom form-check-solid fv-row">
                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                        <input class="form-check-input h-20px w-20px" type="checkbox"
                                                               name="email_reminder" value="1" id="sendEmail">
                                                        <input name="email_reminder" value="0" type="hidden"
                                                               id="sendEmailCopy">
                                                        <span
                                                            class="form-check-label fs-8">Email</span>
                                                    </label>

                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                        <input class="form-check-input h-20px w-20px" type="checkbox"
                                                               name="sms_reminder" value="1" id="sendSMS">
                                                        <input name="sms_reminder" value="0" type="hidden"
                                                               id="sendSMSCopy">
                                                        <span
                                                            class="form-check-label fs-8">SMS</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-4 col-form-label fw-semibold fs-7">Send Later:</label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <div class="form-check form-check-custom form-check-solid fv-row">
                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                        <input class="form-check-input h-20px w-20px" type="checkbox"
                                                               id="sendLaterCheck">
                                                        <span
                                                            class="form-check-label fs-8">Yes</span>
                                                    </label>
                                                    <div class="fv-row" id="send_later_area">

                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-4 col-form-label fw-semibold fs-7">Late
                                                Penalty(%):</label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <div class="form-check form-check-custom form-check-solid fv-row">
                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                        <input class="form-check-input h-20px w-20px" type="checkbox"
                                                               id="latePenaltyCheck">
                                                        <span
                                                            class="form-check-label fs-8">Yes</span>
                                                    </label>
                                                    <div class="fv-row" id="late_penalty_area"></div>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5 card shadow p-4 mb-4">
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-6 col-md-6 col-sm-6 col-form-label fw-semibold fs-6">SubTotal:</label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 col-md-6 col-sm-6 d-flex align-items-center justify-content-end">
                                                <span id="sub-total">$0.00</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 col-form-label fw-semibold fs-6" id="tax_table_head">
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 d-flex align-items-center justify-content-end">
                                                <div class="py-1" id="tax_table_tax"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <div class="col-lg-12 col-form-label fw-semibold fs-6" id="tax_table_head">
                                                <button type="button" class="btn btn-primary py-1" id="add_discount"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" title="Click to add discount"
                                                        data-kt-discount="{{json_encode(DISCOUNTS)}}"><i class="fa fa-plus"></i> Add
                                                    discount
                                                </button>
                                                <div id="discount_area"></div>
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 d-flex align-items-center justify-content-end">
                                                <div class="py-1" id="tax_table_tax"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-6 col-form-label fw-semibold fs-6">Total:</label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 d-flex align-items-center justify-content-end">
                                                <span id="grand-total">$0.00</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--begin::Input group-->
                                            <!--begin::Label-->
                                            <label
                                                class="col-lg-6 col-form-label fw-semibold fs-6"></label>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="col-lg-6 d-flex align-items-center justify-content-end">
                                                <span id="total_converted"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <button class="btn btn-sm btn-light-primary" type="button" id="add_attachment">
                                        <i class="la la-plus"></i> Add Attachment
                                    </button>
                                    <div id="attachment_area"></div>
                                </div>
                                <!--end::Other inputs-->
                                <!--begin::Notes-->
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bold text-gray-700">Notes</label>
                                    <textarea name="notes" class="form-control form-control-solid" rows="3"
                                              placeholder="Thanks for your business"></textarea>
                                </div>
                                <!--end::Notes-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Button-->
                            <button type="button" id="kt_add_invoice_submit" class="btn btn-primary float-end">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
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
    <script src="{{ asset('assets/plugins/snackbar/snackbar.min.js') }}"></script>
@endpush
