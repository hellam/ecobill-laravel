@extends('layout.user.app')
@section('title', 'Branches')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.branches')}}</h1>
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
            <li class="breadcrumb-item text-muted">{{__('messages.setup')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.branches')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.branches')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-1">
                <!--begin::Button-->
                <a href="#" class="btn btn-flex btn-sm btn-primary fw-bolder border-0 fs-6 h-40px"
                   data-bs-toggle="modal" data-bs-target="#kt_modal_add_branch"
                   id="kt_toolbar_primary_button">{{__('messages.add').' '.__('messages.new')}}</a>
                <!--end::Button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Card-->
        <div class="card">
            @if($branches_count)
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6" id="kt_card">
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
                            <input type="text" data-kt-tax-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="{{__('messages.search')}}"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_branches_table"
                           data-kt-dt_api="{{route('user.setup.branches.dt_api')}}">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">#</th>
                            <th class="min-w-125px">{{__('messages.name')}}</th>
                            <th class="min-w-125px">{{__('messages.address')}}</th>
                            <th class="min-w-125px">{{__('messages.default_currency')}}</th>
                            <th class="min-w-60px">{{__('messages.timezone')}}</th>
                            <th class="min-w-70px">{{__('messages.fiscal_year')}}</th>
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
            @else
                <!--begin::No Branches Wrapper-->
                <div class="card-px text-center py-20 my-10">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.branches')])}}</h2>
                    <!--end::Title-->

                    <!--begin::Description-->
                    <p class="text-gray-400 fs-4 fw-bold mb-10">
                        {{__('messages.not_found',['attribute'=>__('messages.branches')])}}
                    </p>
                    <!--end::Description-->

                    <!--begin::Action-->
                    <button data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_branch"
                            class="btn btn-primary">{{__('messages.add',['attribute'=>__('messages.branch')])}}
                    </button>
                    <!--end::Action-->

                    <!--begin::Illustration-->
                    <div class="text-center px-4">
                        <img class="mw-100 mh-300px" alt=""
                             src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::No Branches Wrapper-->
            @endif
        </div>
        <!--end::Card-->

        <!--begin::Modals-->
        <!--begin::Modal Branch - Add-->
        <div class="modal fade" id="kt_modal_add_branch" tabindex="-1" aria-hidden="true" data-backdrop='static'
             data-keyboard='false'>
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_branch_form"
                          data-kt-action="{{route('user.setup.branches.create')}}"
                          data-kt-redirect="{{route('user.setup.branches.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_branch_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new').' '.__('messages.branch')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_branch_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_branch_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_branch_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_branch_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.branch').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.branch').' '.__('messages.name')}}"
                                               name="name"/>
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
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="fs-6 fw-bold mb-2">{{__('messages.bcc_email')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid"
                                               placeholder="{{__('messages.bcc_email')}}"
                                               name="bcc_email"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.phone')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.phone')}}"
                                               name="phone"/>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.default_bank_account')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Default Bank Account"
                                                name="default_bank_account"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_branch">
                                            <option></option>
                                            @foreach($bank_accounts as $bank_account)
                                                <option value="{{$bank_account->id}}">{{$bank_account->account_name}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.tax_no')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid"
                                               placeholder="{{__('messages.tax_no')}}"
                                               name="tax_no"/>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.tax_period')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                name="tax_period"
                                                data-kt-select2="true"
                                                data-placeholder="Select Tax Period"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_branch">
                                            <option></option>
                                            <option value="1">1 Month</option>
                                            <option value="2">2 Months</option>
                                            <option value="3">3 Months</option>
                                            <option value="4">4 Months</option>
                                            <option value="5">5 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="7">7 Months</option>
                                            <option value="8">8 Months</option>
                                            <option value="9">9 Months</option>
                                            <option value="10">10 Months</option>
                                            <option value="11">11 Months</option>
                                            <option value="12">12 Months</option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.default_currency')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Default Currency"
                                                name="default_currency"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_branch">
                                            <option></option>
                                            @foreach($currency as $curr)
                                                <option value="{{$curr->abbreviation}}">{{$curr->abbreviation}}</option>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.fiscal_year')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Fiscal Year"
                                                data-allow-clear="true"
                                                name="fiscal_year"
                                                data-dropdown-parent="#kt_modal_add_branch">
                                            <option></option>
                                            @foreach($fiscal_year as $year)
                                                <option
                                                    value="{{$year->id}}">{{\Carbon\Carbon::parse($year->begin)->format("d/m/Y")."-".\Carbon\Carbon::parse($year->end)->isoFormat(auth('user')->user()->date_format)}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.timezone')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                name="timezone"
                                                data-kt-select2="true"
                                                data-placeholder="Select Timezone"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_branch">
                                            <option></option>
                                            @foreach(TIME_ZONE as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
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
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.address')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid"
                                                  placeholder="{{__('messages.address')}}" rows="3"
                                                  name="address"></textarea>
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
                            <button type="reset" id="kt_modal_add_branch_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_branch_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal Branch - Add-->
        <!--begin::Modal Branch - Edit-->
        <div class="modal fade" id="kt_modal_update_branch" tabindex="-1" aria-hidden="true" data-backdrop='static'
             data-keyboard='false'>
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
                    <form class="form" action="#" id="kt_modal_update_branch_form"
                          data-kt-action="#">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_branch_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.update').' '.__('messages.branch')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_update_branch_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_update_branch_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_update_branch_header"
                                 data-kt-scroll-wrappers="#kt_modal_update_branch_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.branch').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.branch').' '.__('messages.name')}}"
                                               name="name"/>
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
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="fs-6 fw-bold mb-2">{{__('messages.bcc_email')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid"
                                               placeholder="{{__('messages.bcc_email')}}"
                                               name="bcc_email"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.phone')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.phone')}}"
                                               name="phone"/>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.default_bank_account')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Default Bank Account"
                                                name="default_bank_account"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_update_branch">
                                            <option></option>
                                            @foreach($bank_accounts as $bank_account)
                                                <option value="{{$bank_account->id}}">{{$bank_account->account_name}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.tax_no')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid"
                                               placeholder="{{__('messages.tax_no')}}"
                                               name="tax_no"/>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.tax_period')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                name="tax_period"
                                                data-kt-select2="true"
                                                data-placeholder="Select Tax Period"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_update_branch">
                                            <option></option>
                                            <option value="1">1 Month</option>
                                            <option value="2">2 Months</option>
                                            <option value="3">3 Months</option>
                                            <option value="4">4 Months</option>
                                            <option value="5">5 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="7">7 Months</option>
                                            <option value="8">8 Months</option>
                                            <option value="9">9 Months</option>
                                            <option value="10">10 Months</option>
                                            <option value="11">11 Months</option>
                                            <option value="12">12 Months</option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.default_currency')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Default Currency"
                                                name="default_currency"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_update_branch">
                                            <option></option>
                                            @foreach($currency as $curr)
                                                <option value="{{$curr->abbreviation}}">{{$curr->abbreviation}}</option>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.fiscal_year')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select Fiscal Year"
                                                data-allow-clear="true"
                                                name="fiscal_year"
                                                data-dropdown-parent="#kt_modal_update_branch">
                                            <option></option>
                                            @foreach($fiscal_year as $year)
                                                <option
                                                    value="{{$year->id}}">{{\Carbon\Carbon::parse($year->begin)->format("d/m/Y")."-".\Carbon\Carbon::parse($year->end)->isoFormat(auth('user')->user()->date_format)}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.timezone')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                name="timezone"
                                                data-kt-select2="true"
                                                data-placeholder="Select Timezone"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_update_branch">
                                            <option></option>
                                            @foreach(TIME_ZONE as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
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
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.address')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid"
                                                  placeholder="{{__('messages.address')}}" rows="3"
                                                  name="address"></textarea>
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
                            <button type="reset" id="kt_modal_update_branch_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_update_branch_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal Branch - Edit-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{ asset('assets/js/pages/branches/add.js') }}"></script>
    <script src="{{ asset('assets/js/pages/branches/list.js') }}"></script>
    <script src="{{ asset('assets/js/pages/branches/update.js') }}"></script>
@endpush
