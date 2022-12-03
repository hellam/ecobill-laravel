@extends('layout.user.app')
@section('title', 'Receive Payment')
@push('custom_styles')
    <link href="{{asset('assets/plugins/snackbar/snackbar.min.css')}}" rel="stylesheet" type="text/css"
          id="stylesheet"/>
@endpush
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.receive_payment')}}</h1>
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
            <li class="breadcrumb-item text-dark">{{__('messages.receive_payment')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.receive_payment')}}</span>
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
                        <form action="" id="kt_receive_pay_form"
                              data-kt-action="{{route('user.billing.invoice')}}"
                              data-kt-date-format="{{get_js_date_format()}}"
                              data-kt-decimals="{{get_company_setting('price_dec')}}"
                              data-kt-tax-type="{{get_company_setting('tax_inclusive')}}">
                            <!--begin::Wrapper-->
                            <div class="row">
                                <div class="col-md-6">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-equal fv-row">
                                        <span class="fs-2x fw-bold text-gray-800">{{__('messages.receipt_no')}} #</span>
                                        <input type="text" name="reference"
                                               class="form-control form-control-flush fw-bold text-muted fs-3 w-125px"
                                               data-kt-src="{{route('user.ref_gen', ST_CUSTOMER_PAYMENT)}}"
                                               placeholder="..." data-bs-toggle="tooltip" data-bs-trigger="hover"
                                               title="Enter receipt number"/>
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
                                               title="Specify payment date"/>
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
                                        <label
                                            class="form-label fs-6 fw-bold text-gray-700 mb-3">{{__('messages.customer')}}</label>
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
                                                        data-kt-invoices-url="{{route('user.billing.payment.unpaid_invoices_api', ':id')}}"
                                                        class="form-select form-select-sm form-select-solid fw-bolder select_customer">
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <div class="mb-5">
                                            <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">
                                                {{__('messages.into').' '.__('messages.bank')}}
                                            </div>
                                            <div class="fv-row">
                                                <select name="into_bank"
                                                        aria-label="Select Into Bank"
                                                        data-kt-src="{{route('user.banking_gl.banking.accounts.select_api')}}"
                                                        data-placeholder="Select Payment Terms"
                                                        data-kt-date-format="{{get_js_date_format()}}"
                                                        class="form-select form-select-sm form-select-solid fw-bolder select_bank">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-5"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">
                                            {{__('messages.enter').' '.__('messages.amount')}}
                                        </div>
                                        <input type="number"
                                               class="form-control form-control-sm form-control-solid"
                                               placeholder="Enter Amount" name="amount" id="amount">
                                    </div>
                                    <div class="col-md-6 fv-row" id="fx_area"></div>
                                </div>
                                <div class="separator separator-dashed mt-5 mb-10"></div>
                                <!--end::Separator-->
                                <!--begin::Table wrapper-->
                                <div class="table-responsive mb-10 overflow-scroll">
                                    <h4 class="text-center invoice_header"></h4>
                                    <!--begin::Table-->
                                    <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700" id="invoices_table">
                                        <!--begin::Table head-->
                                        <thead>
                                        <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                            <th>#</th>
                                            <th>{{__('messages.amount')}}</th>
                                            <th>{{__('messages.allocated')}}</th>
                                            <th>{{__('messages.this_alloc')}}</th>
                                            <th>{{__('messages.balance')}}</th>
                                        </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody>

                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                </div>
                                <!--end::Table-->
                                <!--begin::No Items-->
                                <table class="table no_items">
                                    <tr data-kt-element="empty">
                                        <th colspan="5" class="text-muted text-center py-10" id="empty">Select Customer
                                            to check
                                            pending invoices
                                        </th>
                                    </tr>
                                </table>
                                <!--begin::No Items-->
                                <!--begin::Other inputs-->
                                <div class="row mb-8 d-none" id="notifications_area">
                                    <div class="col-md-12">
                                        <div class="card border border-1 p-4 mb-4 h-100">
                                            <div class="row">
                                                <!--begin::Input group-->
                                                <!--begin::Label-->
                                                <label
                                                    class="col-lg-4 col-form-label fw-semibold fs-7">Send
                                                    Payment Notifications:</label>
                                                <!--end::Label-->
                                                <!--begin::Label-->
                                                <div class="col-lg-8 d-flex align-items-center">
                                                    <div class="form-check form-check-custom form-check-solid fv-row">
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                            <input class="form-check-input h-20px w-20px"
                                                                   type="checkbox"
                                                                   name="email_notification" value="1" id="sendEmail">
                                                            <input name="email_notification" value="0" type="hidden"
                                                                   id="sendEmailCopy">
                                                            <span
                                                                class="form-check-label fs-8">Email</span>
                                                        </label>

                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 mx-6">
                                                            <input class="form-check-input h-20px w-20px"
                                                                   type="checkbox"
                                                                   name="sms_notification" value="1" id="sendSMS">
                                                            <input name="sms_notification" value="0" type="hidden"
                                                                   id="sendSMSCopy">
                                                            <span
                                                                class="form-check-label fs-8">SMS</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Other inputs-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Button-->
                            <button type="button" id="kt_add_invoice_submit" class="btn btn-primary float-end d-none">
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
    <script src="{{ asset('assets/js/pages/billing/receive_pay/add.js') }}"></script>
    <script src="{{ asset('assets/js/pages/billing/receive_pay/functions.js') }}"></script>
    <script src="{{ asset('assets/plugins/snackbar/snackbar.min.js') }}"></script>
@endpush
