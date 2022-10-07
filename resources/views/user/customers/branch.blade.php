@extends('layout.user.app')
@section('title', 'Customers')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.customers')}}</h1>
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
            <li class="breadcrumb-item text-muted">{{__('messages.customers')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.customer').' '.__('messages.branches')}}</li>
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
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <!--begin::Nav item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6"
                           href="{{route('user.customers.all')}}">{{__('messages.customers')}}</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 active"
                           href="{{route('user.customers.branch.all')}}">{{__('messages.customer').' '.__('messages.branches')}}</a>
                    </li>
                    <!--end::Nav item-->
                </ul>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Row-->
        <!--begin::Card-->
        <div class="card shadow">
            <!--begin::Products Wrapper-->
            @if($customer_branch)
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                          height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                          fill="currentColor"/>
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-customer-branch-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="{{__('messages.search',['attribute'=>__('messages.customer').' '.__('messages.branch')])}}"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Add product-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_customer_branch">{{__('messages.add').' '.__('messages.new')}}
                        </button>
                        <!--end::Add product-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customer_branches_table"
                           data-kt-dt_api="{{route('user.customers.branch.dt_api')}}">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th>{{__('messages.customer').' '.__('messages.branch')}}</th>
                            <th>{{__('messages.short').' '.__('messages.name')}}</th>
                            <th>{{__('messages.country')}}</th>
                            <th>{{__('messages.company').' '.__('messages.branch')}}</th>
                            <th>{{__('messages.status')}}</th>
                            <th class="text-end">{{__('messages.actions')}}</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600"></tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                    <!--end::Card body-->
                </div>
                <!--end::Products Wrapper-->
            @else
                <!--begin::No Customer Branches Wrapper-->
                <div class="card-px text-center py-20 my-10">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.customer').' '.__('messages.branch')])}}</h2>
                    <!--end::Title-->

                    <!--begin::Description-->
                    <p class="text-gray-400 fs-4 fw-bold mb-10">
                        {{__('messages.not_found',['attribute'=>__('messages.customers')])}}
                    </p>
                    <!--end::Description-->

                    <!--begin::Action-->
                    <button data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_customer_branch"
                            class="btn btn-primary">{{__('messages.add',['attribute'=>__('messages.customer').' '.__('messages.branch')])}}
                    </button>
                    <!--end::Action-->

                    <!--begin::Illustration-->
                    <div class="text-center px-4">
                        <img class="mw-100 mh-300px" alt=""
                             src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::No Customer Branches Wrapper-->
            @endif
        </div>
        <!--end::Card-->
        <!--end::Row-->

        <!--begin::Modals-->
        <!--begin::Modal - Customer Branch - Add-->
        <div class="modal fade" id="kt_modal_add_customer_branch" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_customer_branch_form"
                          data-kt-action=""
                          data-kt-redirect="">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_customer_branch_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new').' '.__('messages.customer').' '.__('messages.branch')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_customer_branch_close"
                                 class="btn btn-icon btn-sm btn-active-icon-primary">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16"
                                              height="2" rx="1"
                                              transform="rotate(-45 6 17.3137)"
                                              fill="currentColor"/>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                              transform="rotate(45 7.41422 6)"
                                              fill="currentColor"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body py-10 px-lg-17">
                            <!--begin::Scroll-->
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_branch_scroll"
                                 data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_customer_branch_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_customer_branch_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.customer')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="customer_id"
                                                aria-label="{{__('messages.select').' '.__('messages.customer')}}"
                                                data-control="select2"
                                                data-kt-src="{{route('user.customers.select_api')}}"
                                                data-placeholder="{{__('messages.select').' '.__('messages.customer')}}"
                                                data-dropdown-parent="#kt_modal_add_customer_branch"
                                                class="form-select form-select-solid fw-bolder select_customer">
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.first').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.first').' '.__('messages.name')}}"
                                               name="first_name"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.last').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.last').' '.__('messages.name')}}"
                                               name="last_name"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.short').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.short').' '.__('messages.name')}}"
                                               name="short_name"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="fs-6 fw-bold mb-2">{{__('messages.company').' '.__('messages.branch')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.company').' '.__('messages.branch')}}"
                                               name="branch"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span class="required">{{__('messages.country')}}</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                               title="{{__('messages.country')}} 0f origin"></i>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="country" aria-label="{{__('messages.select_country')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select_country')}}..."
                                                data-dropdown-parent="#kt_modal_add_customer_branch"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                            @foreach(\Monarobase\CountryList\CountryListFacade::getList() as $key => $country)
                                                <option value="{{$key}}">{{$country}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.phone_number')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="tel" class="form-control form-control-solid"
                                               placeholder="{{__('messages.phone_number')}}"
                                               name="phone"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.email')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid"
                                               placeholder="{{__('messages.email')}}"
                                               name="email"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span>{{__('messages.address')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.address')}}"
                                               name="address"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_add_customer_branch_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_customer_branch_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
														<span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - Customer Branch - Add-->

        <!--begin::Modal - Customer Branch - Update-->
        <div class="modal fade" id="kt_modal_update_customer" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <div class="loader_container">
                        <div class="loader_wrapper">
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                            <div class="loader">
                                <div class="dot"></div>
                            </div>
                        </div>
                        <div class="text">
                            Please wait
                        </div>
                    </div>
                    <!--begin::Form-->

                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - Customer Branch - Update-->

        <!--end::Modals-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{asset('assets/js/pages/customers/branch/add.js')}}"></script>
    <script src="{{asset('assets/js/pages/customers/branch/list.js')}}"></script>
    <script src="{{asset('assets/js/pages/customers/branch/update.js')}}"></script>
@endpush
