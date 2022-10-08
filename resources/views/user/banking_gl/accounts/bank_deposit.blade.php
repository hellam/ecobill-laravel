@extends('layout.user.app')
@section('title', 'Deposit')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.deposit')}}</h1>
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
            <li class="breadcrumb-item text-muted">{{__('messages.banking_gL')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.deposit')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.deposit')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Row-->
        <!--begin::Card-->
        <div class="card shadow">
            <!--begin::Card Header-->
            <div class="card-header pt-6">
                <div class="col-md-4">
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.date')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-sm form-control-solid"
                                   placeholder="{{__('messages.date')}}"
                                   name="date" id="date_picker"/>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.reference')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-sm form-control-solid"
                                   placeholder="{{__('messages.reference')}}"
                                   name="name"/>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-4">
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.from')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Input-->
                            <select name="from"
                                    aria-label="Select From"
                                    data-control="select2"
                                    data-kt-src="{{route('user.customers.branch.select_api')}}"
                                    data-placeholder="Select Country"
                                    class="form-select form-select-sm form-select-solid fw-bolder">
                                <option value="0">Miscellaneous</option>
                                <option value="1">Customer</option>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row" id="changing_div">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.name')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8" id="select_input">
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-sm form-control-solid"
                                   placeholder="{{__('messages.name')}}"
                                   name="name"/>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-4">
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.currency')}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Input-->
                            <select name="into"
                                    aria-label="Select Currency"
                                    data-kt-src=""
                                    data-placeholder="Select Currency"
                                    class="form-select form-select-sm form-select-solid fw-bolder">
                                <option></option>
                                @foreach($currency as $curr)
                                    <option
                                        value="{{$curr->abbreviation}}">{{$curr->abbreviation.'|'.$curr->name}}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6 mx-2 fv-row">
                        <!--begin::Label-->
                        <label
                            class="col-lg-4 col-form-label fw-semibold fs-7">{{__('messages.bank')}} Acc</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Input-->
                            <select name="bank_account"
                                    aria-label="Select Account"
                                    data-kt-src="{{route('user.banking_gl.banking.accounts.select_api')}}"
                                    data-placeholder="Select Account"
                                    class="form-select form-select-sm form-select-solid fw-bolder select_bank">
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <h4 class="text-center mb-7">Deposit Items</h4>
                <!--begin::Input group-->
                <!--begin::Repeater-->
                <div id="kt_deposit_items_row">
                    <!--begin::Form group-->
                    <div class="form-group">
                        <div data-repeater-list="deposit_options" class="d-flex flex-column gap-3">
                            <div data-repeater-item="" class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <!--begin::Select2-->
                                        <div>
                                            <select class="form-select form-select-solid gl_select"
                                                    name="gl_account"
                                                    data-kt-src="{{route('user.banking_gl.gl_accounts.select_api', 'no_bank')}}"
                                                    data-placeholder="Select GL Account"
                                                    data-kt-add-deposit="deposit_option">
                                            </select>
                                        </div>
                                        <!--end::Select2-->
                                    </div>
                                    <!--begin::Input-->
                                    <div class="col-md-4">
                                        <input type="number" class="form-control"
                                               name="amount"
                                               placeholder="Amount"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--begin::Input-->
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                               name="narration"
                                               placeholder="Narration">
                                    </div>
                                    <!--end::Input-->
                                    <div class="col-md-1">
                                        <button type="button" data-repeater-delete=""
                                                class="btn btn-sm btn-icon btn-light-danger"><i
                                                class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group mt-5  d-flex justify-content-center text-center">
                        <button type="button" data-repeater-create="" class="btn btn-sm btn-primary">
                            <i class="fa fa-add"></i>Add Row
                        </button>
                    </div>
                    <!--end::Form group-->
                </div>
                <!--end::Repeater-->
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row my-10">
                    <!--begin::Label-->
                    <label class="fs-6 fw-bold mb-2" for="comments">
                        <span>{{__('messages.comments')}}</span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <textarea type="text" class="form-control form-control-solid"
                              placeholder="{{__('messages.comments')}}"
                              name="comments"></textarea>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Button-->
                <button type="submit" id="kt_modal_update_currency_submit" class="btn btn-primary float-end">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Row-->
        <!--end::Card-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/deposit/deposit.js') }}"></script>
@endpush
