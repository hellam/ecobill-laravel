@extends('layout.user.app')
@section('title', 'GL Maintenance')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.gl_maintenance')}}</h1>
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
            <li class="breadcrumb-item text-muted">{{__('messages.banking')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.gl_maintenance')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.gl_maintenance')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Card-->
        <div class="card shadow">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6" id="kt_card">
                <!--begin::Card title-->
                <div class="card-title">
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab"
                               href="#gl_accounts">
                                {{__('messages.accounts')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#gl_groups">
                                {{__('messages.groups')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#gl_classes">
                                {{__('messages.classes')}}
                            </a>
                        </li>
                    </ul>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0 tab-content">
                <div class="tab-pane fade show active" id="gl_accounts" role="tabpanel">
                    @if($gl_accounts_count<=0)
                        <!--begin::No GL Accounts Wrapper-->
                        <div class="card-px text-center py-20 my-10">
                            <!--begin::Title-->
                            <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.gl_maintenance')])}}</h2>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <p class="text-gray-400 fs-4 fw-bold mb-10">{{__('messages.not_found',['attribute'=>__('messages.gl').' '.__('messages.accounts')])}}</p>
                            <!--end::Description-->
                            <!--begin::Action-->
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#kt_modal_add_gl_account"
                               class="btn btn-primary">{{__('messages.add_new')}}</a>
                            <!--end::Action-->

                            <!--begin::Illustration-->
                            <div class="text-center px-4">
                                <img class="mw-100 mh-300px" alt=""
                                     src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::No GL Accounts Wrapper-->
                    @else
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
                                    <input type="text" data-kt-contact-table-filter="search"
                                           class="form-control form-control-solid w-250px ps-15"
                                           placeholder="{{__('messages.search_hint',['attribute'=>__('messages.account')])}}"/>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end" data-kt-contact-table-toolbar="base">
                                    <!--begin::Add customer-->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_gl_account">{{__('messages.add_new')}}
                                    </button>
                                    <!--end::Add customer-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_contacts_table"
                                   data-kt-dt_api="#">
                                <!--begin::Table head-->
                                <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">#</th>
                                    <th class="min-w-125px">{{__('messages.acc_code')}}</th>
                                    <th class="min-w-125px">{{__('messages.acc_name')}}</th>
                                    <th class="min-w-125px">{{__('messages.group')}}</th>
                                    <th class="min-w-125px">{{__('messages.status')}}</th>
                                    <th class="text-end min-w-70px">{{__('messages.actions')}}</th>
                                </tr>
                                <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600"></tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    @endif
                </div>
                <div class="tab-pane fade" id="gl_groups" role="tabpanel">
                    @if($gl_groups_count<=0)
                        <!--begin::No GL Groups Wrapper-->
                        <div class="card-px text-center py-20 my-10">
                            <!--begin::Title-->
                            <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.gl_maintenance')])}}</h2>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <p class="text-gray-400 fs-4 fw-bold mb-10">{{__('messages.not_found',['attribute'=>__('messages.gl').' '.__('messages.groups')])}}</p>
                            <!--end::Description-->
                            <!--begin::Action-->
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#kt_modal_add_gl_group"
                               class="btn btn-primary">{{__('messages.add_new')}}</a>
                            <!--end::Action-->

                            <!--begin::Illustration-->
                            <div class="text-center px-4">
                                <img class="mw-100 mh-300px" alt=""
                                     src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::No GL Classes Wrapper-->
                    @else
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
                                    <input type="text" data-kt-contact-table-filter="search"
                                           class="form-control form-control-solid w-250px ps-15"
                                           placeholder="{{__('messages.search')}}"/>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end" data-kt-contact-table-toolbar="base">
                                    <!--begin::Add customer-->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_gl_group">{{__('messages.add_new')}}
                                    </button>
                                    <!--end::Add customer-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_gl_groups_table"
                                   data-kt-dt_api="{{route('user.banking_gl.gl_groups.dt_api')}}">
                                <!--begin::Table head-->
                                <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-125px">{{__('messages.name')}}</th>
                                    <th class="min-w-125px">{{__('messages.class')}}</th>
                                    <th class="min-w-125px">{{__('messages.status')}}</th>
                                    <th class="text-end min-w-70px">{{__('messages.actions')}}</th>
                                </tr>
                                <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600"></tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    @endif
                </div>
                <div class="tab-pane fade" id="gl_classes" role="tabpanel">
                    @if($gl_classes_count<=0)
                        <!--begin::No GL Classes Wrapper-->
                        <div class="card-px text-center py-20 my-10">
                            <!--begin::Title-->
                            <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.gl_maintenance')])}}</h2>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <p class="text-gray-400 fs-4 fw-bold mb-10">{{__('messages.not_found',['attribute'=>__('messages.gl').' '.__('messages.classes')])}}</p>
                            <!--end::Description-->
                            <!--begin::Action-->
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#kt_modal_add_gl_class"
                               class="btn btn-primary">{{__('messages.add_new')}}</a>
                            <!--end::Action-->

                            <!--begin::Illustration-->
                            <div class="text-center px-4">
                                <img class="mw-100 mh-300px" alt=""
                                     src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::No GL Classes Wrapper-->
                    @else
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
                                    <input type="text" data-kt-contact-table-filter="search"
                                           class="form-control form-control-solid w-250px ps-15"
                                           placeholder="{{__('messages.search',['attribute'=>__('messages.classes')])}}"/>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end" data-kt-contact-table-toolbar="base">
                                    <!--begin::Add customer-->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_gl_class">{{__('messages.add_new')}}
                                    </button>
                                    <!--end::Add customer-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_gl_classes_table"
                                   data-kt-dt_api="{{route('user.banking_gl.gl_class.dt_api')}}">
                                <!--begin::Table head-->
                                <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">#</th>
                                    <th class="min-w-100px">{{__('messages.name')}}</th>
                                    <th class="min-w-100px">{{__('messages.status')}}</th>
                                    <th class="text-end min-w-70px">{{__('messages.actions')}}</th>
                                </tr>
                                <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600"></tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    @endif
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <!--begin::Modal - GL Accounts - Add-->
        <div class="modal fade" id="kt_modal_add_gl_account" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_gl_account_form"
                          data-kt-action="#"
                          data-kt-redirect="#">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_gl_account_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new_gl_account')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_gl_account_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_gl_account_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_gl_account_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_gl_account_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="account_code">
                                        <span class="required">{{__('messages.acc_code')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input id="account_code" type="text" class="form-control form-control-solid"
                                           placeholder="{{__('messages.acc_code')}}"
                                           name="account_code"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="account_name">
                                        <span class="required">{{__('messages.acc_name')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input id="account_name" type="text" class="form-control form-control-solid"
                                           placeholder="{{__('messages.acc_name')}}"
                                           name="account_name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span class="required">{{__('messages.group')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="account_type"
                                                aria-label="Select Group"
                                                data-control="select2"
                                                data-kt-src="#"
                                                data-placeholder="Select Group"
                                                data-dropdown-parent="#kt_modal_add_gl_account"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_add_gl_account_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_gl_account_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - GL Accounts - Add-->

        <!--begin::Modal - GL Groups - Add-->
        <div class="modal fade" id="kt_modal_add_gl_group" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_gl_group_form"
                          data-kt-action="{{route('user.banking_gl.gl_groups.create')}}"
                          data-kt-redirect="{{route('user.banking_gl.gl_accounts.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_gl_group_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new_gl_group')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_gl_group_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_gl_group_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_gl_group_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_gl_class_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="name">
                                        <span class="required">{{__('messages.name')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" id="name" class="form-control form-control-solid"
                                           placeholder="{{__('messages.group').' '.__('messages.name')}}"
                                           name="name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span class="required">{{__('messages.group').' '.__('messages.class')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="class_id"
                                                aria-label="Select Class"
                                                data-control="select2"
                                                data-kt-src="#"
                                                data-placeholder="Select Class"
                                                data-dropdown-parent="#kt_modal_add_gl_group"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                            @foreach($gl_classes as $class)
                                                <option value="{{$class->id}}">{{$class->class_name}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_add_gl_group_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_gl_group_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - GL Groups - Add-->

        <!--begin::Modal - GL Classes - Add-->
        <div class="modal fade" id="kt_modal_add_gl_class" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_gl_class_form"
                          data-kt-action="{{route('user.banking_gl.gl_class.create')}}"
                          data-kt-redirect="{{route('user.banking_gl.gl_accounts.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_gl_class_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new_gl_class')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_gl_class_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_gl_class_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_gl_class_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_gl_class_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="class_name">
                                        <span class="required">{{__('messages.name')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid"
                                           placeholder="{{__('messages.class')}} {{__('messages.name')}}"
                                           name="class_name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_add_gl_class_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_gl_class_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - GL Classes - Add-->

        <!--begin::Modal - GL Classes - Update-->
        <div class="modal fade" id="kt_modal_update_gl_class" tabindex="-1">
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
                    <form class="form" action="#" id="kt_modal_update_gl_class_form"
                          data-kt-action="#">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_gl_class_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new_gl_class')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_update_gl_class_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_update_gl_class_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_update_gl_class_header"
                                 data-kt-scroll-wrappers="#kt_modal_update_gl_class_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="class_name">
                                        <span class="required">{{__('messages.name')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid"
                                           placeholder="{{__('messages.class')}} {{__('messages.name')}}"
                                           name="class_name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <input type="hidden" name="inactive">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" id="inactive">
                                    <span class="form-check-label fs-7 fw-bolder text-gray-800">Active</span>
                                </label>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_update_gl_class_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_update_gl_class_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - GL Classes - Update-->

    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
        <script src="{{ asset('assets/js/pages/gl_maintenance/accounts/add.js') }}"></script>
        <script src="{{ asset('assets/js/pages/gl_maintenance/classes/add.js') }}"></script>
        <script src="{{ asset('assets/js/pages/gl_maintenance/classes/list.js') }}"></script>
        <script src="{{ asset('assets/js/pages/gl_maintenance/classes/update.js') }}"></script>
        <script src="{{ asset('assets/js/pages/gl_maintenance/groups/add.js') }}"></script>
        <script src="{{ asset('assets/js/pages/gl_maintenance/groups/list.js') }}"></script>
@endpush
