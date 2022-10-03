@extends('layout.user.app')
@section('title', 'Exchange Rates')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.fx')}}</h1>
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
            <li class="breadcrumb-item text-dark">{{__('messages.fx')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.fx')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Card-->
        <div class="card shadow">
            <!--begin::Card body-->
            <div class="card-body pt-0 tab-content">
                @if($fx_count<=0)
                    <!--begin::No FX Wrapper-->
                    <div class="card-px text-center py-20 my-10">
                        <!--begin::Title-->
                        <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.fx')])}}</h2>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <p class="text-gray-400 fs-4 fw-bold mb-10">{{__('messages.not_found',['attribute'=>__('messages.fx')])}}</p>
                        <!--end::Description-->
                        <!--begin::Action-->
                        <a href="#" data-bs-toggle="modal"
                           data-bs-target="#kt_modal_add_fx"
                           class="btn btn-primary">{{__('messages.add_new')}}</a>
                        <!--end::Action-->

                        <!--begin::Illustration-->
                        <div class="text-center px-4">
                            <img class="mw-100 mh-300px" alt=""
                                 src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                        </div>
                        <!--end::Illustration-->
                    </div>
                    <!--end::No FX Wrapper-->
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
                                <input type="text" data-kt-fx-table-filter="search"
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
                                        data-bs-target="#kt_modal_add_fx">{{__('messages.add_new')}}
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_fx_table"
                               data-kt-dt_api="#">
                            <!--begin::Table head-->
                            <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th>{{__('messages.currency')}}</th>
                                <th>{{__('messages.buy_rate')}}</th>
                                <th>{{__('messages.sell_rate')}}</th>
                                <th>{{__('messages.date')}}</th>
                                <th class="text-end">{{__('messages.actions')}}</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                            @foreach($exchangeRates as $fx)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$fx->curr->name}}</td>
                                    <td>{{$fx->buy_rate}}</td>
                                    <td>{{$fx->sell_rate}}</td>
                                    <td>{{format_date($fx->date, true)}}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                           data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                           data-kt-menu-flip="top-end">
                                            Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                            <path
                                                                d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                                fill="#000000" fill-rule="nonzero"
                                                                transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div
                                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 test"
                                                   data-kt-fx-table-actions="edit_row"
                                                   data-kt-fx-edit-url="{{route('user.banking_gl.fx.edit', $fx->id)}}"
                                                   data-kt-fx-update-url="{{route('user.banking_gl.fx.update', $fx->id)}}">
                                                    Edit
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 test"
                                                   data-kt-fx-table-actions="delete_row"
                                                   data-kt-fx-delete-url="{{route('user.banking_gl.fx.delete', $fx->id)}}">
                                                    Delete
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <!--begin::Modal - FX - Add-->
        <div class="modal fade" id="kt_modal_add_fx" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_fx_form"
                          data-kt-action="{{route('user.banking_gl.fx.create')}}"
                          data-kt-redirect="{{route('user.banking_gl.fx.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_fx_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.new').' '.__('messages.currency')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_fx_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_fx_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_fx_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_fx_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span
                                                class="required">{{__('messages.currency')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="currency"
                                                aria-label="Select Currency"
                                                data-control="select2"
                                                data-kt-src="#"
                                                data-placeholder="Select Currency"
                                                data-dropdown-parent="#kt_modal_add_fx"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                            @foreach($currency as $curr)
                                                <option
                                                    value="{{$curr->abbreviation}}">{{$curr->abbreviation.' - '.$curr->name}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="buy_rate">
                                        <span
                                            class="required">{{__('messages.buy_rate')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid"
                                           placeholder="{{__('messages.buy_rate')}}"
                                           name="buy_rate"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="sell_rate">
                                        <span
                                            class="required">{{__('messages.sell_rate')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid"
                                           placeholder="{{__('messages.sell_rate')}}"
                                           name="sell_rate"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="date_from">
                                        <span
                                            class="required">{{__('messages.date_from')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid"
                                           placeholder="{{__('messages.select').' '.__('messages.date_from')}}"
                                           id="kt_date_from"/>
                                    <input type="hidden" name="date" id="date">
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
                            <button type="reset" id="kt_modal_add_fx_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_fx_submit" class="btn btn-primary">
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
        <!--end::Modal - FX - Add-->

        <!--begin::Modal - FX - Update-->
        <div class="modal fade" id="kt_modal_update_fx" tabindex="-1">
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
                    <form class="form" action="#" id="kt_modal_update_fx_form"
                          data-kt-action="#"
                          data-kt-redirect="{{route('user.banking_gl.fx.all')}}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_fx_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.update').' '.__('messages.fx')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_update_fx_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_update_fx_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_update_fx_header"
                                 data-kt-scroll-wrappers="#kt_modal_update_fx_scroll"
                                 data-kt-scroll-offset="300px">

                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span
                                                class="required">{{__('messages.currency')}}</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="currency"
                                                aria-label="Select Currency"
                                                data-control="select2"
                                                data-kt-src="#"
                                                data-placeholder="Select Currency"
                                                data-dropdown-parent="#kt_modal_update_fx"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                            @foreach($currency as $curr)
                                                <option
                                                    value="{{$curr->abbreviation}}">{{$curr->abbreviation.' - '.$curr->name}}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="buy_rate">
                                        <span
                                            class="required">{{__('messages.buy_rate')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid"
                                           placeholder="{{__('messages.buy_rate')}}"
                                           name="buy_rate"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="sell_rate">
                                        <span
                                            class="required">{{__('messages.sell_rate')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid"
                                           placeholder="{{__('messages.sell_rate')}}"
                                           name="sell_rate"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2" for="date_from">
                                        <span
                                            class="required">{{__('messages.date_from')}}</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid"
                                           placeholder="{{__('messages.select').' '.__('messages.date_from')}}"
                                           id="kt_update_date_from"/>
                                    <input type="hidden" name="date" id="update_date">
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
                            <button type="reset" id="kt_modal_update_fx_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_update_fx_submit" class="btn btn-primary">
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
        <!--end::Modal - FX - Update-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{ asset('assets/js/pages/fx/add.js') }}"></script>
    <script src="{{ asset('assets/js/pages/fx/list.js') }}"></script>
    <script src="{{ asset('assets/js/pages/fx/update.js') }}"></script>
@endpush
