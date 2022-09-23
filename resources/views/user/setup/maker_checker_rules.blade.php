@extends('layout.user.app')
@section('title', 'Maker Checker Rules')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.maker_checker_rules')}}</h1>
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
            <li class="breadcrumb-item text-dark">{{__('messages.maker_checker_rules')}}</li>
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
        <div class="toolbar d-lg-flex flex-end flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-1">
                <!--begin::Button-->
                <a href="#" class="btn btn-flex btn-sm btn-primary fw-bolder border-0 fs-6 h-40px"
                   data-bs-toggle="modal" data-bs-target="#kt_modal_add_rule"
                   id="kt_toolbar_primary_button">{{__('messages.add').' '.__('messages.new')}}</a>
                <!--end::Button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Card-->
        <div class="card">
            @if($maker_checker_rules_count)
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6" id="kt_card">
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid gap-5">
                        <div class="w-100 mw-200px">
                            <!--begin::Input-->
                            <input class="form-control form-control-solid" id="kt_date_range_picker"
                                   placeholder="Select Date Range"/>
                            <!--end::Input-->
                        </div>
                        <div class="w-100 mw-200px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid"
                                    data-kt-select2="true"
                                    data-placeholder="Select Event"
                                    data-kt-ecommerce-product-filter="status">
                                <option></option>
                                @foreach($permissions as $permission)
                                    <option value="{{$permission->code}}">{{$permission->name}}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                        </div>
                        <div class="w-100 mw-150px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid"
                                    data-kt-select2="true"
                                    data-placeholder="Select User"
                                    data-kt-ecommerce-product-filter="status">
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->username}}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                        </div>
                        <div class="w-50 mw-150px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid"
                                    data-kt-select2="true"
                                    data-placeholder="Select Type"
                                    data-kt-ecommerce-product-filter="status">
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->username}}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                        </div>
                        <!--begin::Filter-->
                        <button class="btn btn-primary" id="kt_filter_btn">Filter</button>
                        <!--end::Filter-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_maker_checker_rules_table"
                           data-kt-dt_api="{{route('user.setup.maker_checker_rules.dt_api')}}">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">#</th>
                            <th class="min-w-125px">{{__('messages.type')}}</th>
                            <th class="min-w-125px">{{__('messages.event')}}</th>
                            <th class="min-w-125px">{{__('messages.created_by')}}</th>
                            <th class="min-w-60px">{{__('messages.status')}}</th>
                            <th class="min-w-70px">{{__('messages.date')}}</th>
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
                <!--begin::No Maker Checker Rules Wrapper-->
                <div class="card-px text-center py-20 my-10">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.maker_checker_rules')])}}</h2>
                    <!--end::Title-->

                    <!--begin::Description-->
                    <p class="text-gray-400 fs-4 fw-bold mb-10">
                        {{__('messages.not_found',['attribute'=>__('messages.maker_checker_rules')])}}
                    </p>
                    <!--end::Description-->

                    <!--begin::Action-->
                    <button data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_rule"
                            class="btn btn-primary">{{__('messages.add',['attribute'=>__('messages.rule')])}}
                    </button>
                    <!--end::Action-->

                    <!--begin::Illustration-->
                    <div class="text-center px-4">
                        <img class="mw-100 mh-300px" alt=""
                             src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::No Maker Checker Rules Wrapper-->
            @endif
        </div>
        <!--end::Card-->

        <!--begin::Modals-->
        <!--begin::Modal Rule - Add-->
        <div class="modal fade" id="kt_modal_add_rule" tabindex="-1" aria-hidden="true" data-backdrop='static'
             data-keyboard='false'>
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_rule_form"
                          data-kt-action="{{route('user.setup.maker_checker_rules.create')}}"
                          data-kt-redirect="{{route('user.setup.maker_checker_rules.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_rule_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new').' '.__('messages.maker_checker_rule')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_rule_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_rule_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_rule_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_rule_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.activity')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                name="action"
                                                id="permissions_select"
                                                data-placeholder="Select Permission"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_rule">
                                            <option></option>
                                            @foreach($permissions as $permission)
                                                <option value="{{$permission->code}}">{{$permission->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="action"></span>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row" id="maker_type2">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.type')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Options-->
                                        <div class="d-flex align-items-center mt-3">
                                            <!--begin::Option-->
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="maker_type" type="radio"
                                                       value="0" checked id="maker_type1"/>
                                                <span class="fw-semibold ps-2 fs-6">{{__('messages.single')}}</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="maker_type" type="radio"
                                                       value="1"/>
                                                <span class="fw-semibold ps-2 fs-6">{{__('messages.double')}}</span>
                                            </label>
                                            <!--end::Option-->
                                        </div>
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
                            <button type="reset" id="kt_modal_add_rule_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_rule_submit" class="btn btn-primary">
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
        <!--end::Modal Rule - Add-->
        <!--begin::Modal Rule - Edit-->
        <div class="modal fade" id="kt_modal_update_rule" tabindex="-1" aria-hidden="true" data-backdrop='static'
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
                    <form class="form" action="#" id="kt_modal_update_rule_form"
                          data-kt-action="{{route('user.setup.maker_checker_rules.create')}}"
                          data-kt-redirect="{{route('user.setup.maker_checker_rules.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_rule_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.edit').' '.__('messages.maker_checker_rule')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_update_rule_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_update_rule_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_update_rule_header"
                                 data-kt-scroll-wrappers="#kt_modal_update_rule_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.activity')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                name="action"
                                                id="permissions_edit_select"
                                                data-placeholder="Select Permission"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_update_rule">
                                            <option></option>
                                            @foreach($permissions as $permission)
                                                <option value="{{$permission->code}}">{{$permission->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="action_update"></span>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row" id="maker_type">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.type')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Options-->
                                        <div class="d-flex align-items-center mt-3">
                                            <!--begin::Option-->
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="maker_type" type="radio"
                                                       value="0"/>
                                                <span class="fw-semibold ps-2 fs-6">{{__('messages.single')}}</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="maker_type" type="radio"
                                                       value="1"/>
                                                <span
                                                    class="fw-semibold ps-2 fs-6 required">{{__('messages.double')}}</span>
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <span class="fw-semibold ps-2 fs-6">Active</span>
                                    <input class="form-check-input mx-4" type="checkbox" id="inactive"/>
                                    <input type="hidden" name="inactive"/>
                                </label>
                                <!--begin::Input group-->
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Modal body-->
                        <!--begin::Modal footer-->
                        <div class="modal-footer flex-center">
                            <!--begin::Button-->
                            <button type="reset" id="kt_modal_update_rule_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_update_rule_submit" class="btn btn-primary">
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
        <!--end::Modal Rule - Edit-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{ asset('assets/js/pages/maker_checker/list.js') }}"></script>
    <script src="{{ asset('assets/js/pages/maker_checker/add.js') }}"></script>
    <script src="{{ asset('assets/js/pages/maker_checker/update.js') }}"></script>
@endpush
