@extends('layout.user.app')
@section('title', 'Products')
@section('page_title')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{__('messages.products')}}</h1>
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
            <li class="breadcrumb-item text-muted">{{__('messages.products')}}</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{__('messages.products')}}</li>
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
                    <span class="text-dark fw-bolder fs-1">{{__('messages.products')}}</span>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Row-->
        <!--begin::Card-->
        <div class="card shadow">
            <!--begin::Products Wrapper-->
            @if($products)
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
                            <input type="text" data-kt-product-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="{{__('messages.search',['attribute'=>__('messages.product')])}}"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="invisible btn btn-light-primary me-3"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                <span class="svg-icon svg-icon-2">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<path
                                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                            fill="currentColor"/>
													</svg>
												</span>
                                <!--end::Svg Icon-->Filter
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                 data-kt-menu="true"
                                 id="kt-toolbar-filter">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-4 text-dark fw-bolder">Filter Options</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-bold mb-3">Month:</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bolder"
                                                data-kt-select2="true"
                                                data-placeholder="Select option" data-allow-clear="true"
                                                data-kt-product-table-filter="month"
                                                data-dropdown-parent="#kt-toolbar-filter">
                                            <option></option>
                                            <option value="aug">August</option>
                                            <option value="sep">September</option>
                                            <option value="oct">October</option>
                                            <option value="nov">November</option>
                                            <option value="dec">December</option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-bold mb-3">Payment Type:</label>
                                        <!--end::Label-->
                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-bold"
                                             data-kt-product-table-filter="payment_type">
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input class="form-check-input" type="radio"
                                                       name="payment_type"
                                                       value="all" checked="checked"/>
                                                <span class="form-check-label text-gray-600">All</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input class="form-check-input" type="radio"
                                                       name="payment_type"
                                                       value="visa"/>
                                                <span class="form-check-label text-gray-600">Visa</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                <input class="form-check-input" type="radio"
                                                       name="payment_type"
                                                       value="mastercard"/>
                                                <span
                                                    class="form-check-label text-gray-600">Mastercard</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio"
                                                       name="payment_type"
                                                       value="american_express"/>
                                                <span
                                                    class="form-check-label text-gray-600">American Express</span>
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset"
                                                class="btn btn-light btn-active-light-primary me-2"
                                                data-kt-menu-dismiss="true"
                                                data-kt-product-table-filter="reset">
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary"
                                                data-kt-menu-dismiss="true"
                                                data-kt-product-table-filter="filter">Apply
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                            <!--begin::Add product-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_product">{{__('messages.add').' '.__('messages.new')}}
                            </button>
                            <!--end::Add product-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                             data-kt-product-table-toolbar="selected">
                            <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-product-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger"
                                    data-kt-product-table-select="delete_selected">Delete Selected
                            </button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_products_table"
                           data-kt-dt_api="#">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div
                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                           data-kt-check-target="#kt_products_table .form-check-input"
                                           value="1"/>
                                </div>
                            </th>
                            <th class="min-w-125px">{{__('messages.product').' '.__('messages.name')}}</th>
                            <th class="min-w-125px">{{__('messages.barcode')}}</th>
                            <th class="min-w-125px">{{__('messages.price')}}</th>
                            <th class="min-w-125px">{{__('messages.cost')}}</th>
                            <th class="min-w-125px">{{__('messages.category')}}</th>
                            <th class="min-w-125px">{{__('messages.tax')}}</th>
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
                    <!--end::Card body-->
                </div>
                <!--end::Products Wrapper-->
            @else
                <!--begin::No Products Wrapper-->
                <div class="card-px text-center py-20 my-10">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.products')])}}</h2>
                    <!--end::Title-->

                    <!--begin::Description-->
                    <p class="text-gray-400 fs-4 fw-bold mb-10">
                        {{__('messages.not_found',['attribute'=>__('messages.products')])}}
                    </p>
                    <!--end::Description-->

                    <!--begin::Action-->
                    <button data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_product"
                            class="btn btn-primary">{{__('messages.add',['attribute'=>__('messages.product')])}}
                    </button>
                    <!--end::Action-->

                    <!--begin::Illustration-->
                    <div class="text-center px-4">
                        <img class="mw-100 mh-300px" alt=""
                             src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::No Products Wrapper-->
            @endif
        </div>
        <!--end::Card-->
        <!--end::Row-->

        <!--begin::Modals-->
        <!--begin::Modal - Product - Add-->
        <div class="modal fade" id="kt_modal_add_product" tabindex="-1">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_add_product_form"
                          data-kt-action="#"
                          data-kt-redirect="#">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_product_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.add').' '.__('messages.product')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_add_product_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_product_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_add_product_header"
                                 data-kt-scroll-wrappers="#kt_modal_add_product_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7 align-items-end">
                                    <!--begin::Col-->
                                    <div class="col-md-12 fv-row">
                                        {!! image_view('image', 'image',asset('assets/media/avatars/placeholder.jpg'),'', false) !!}
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7 align-items-end">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.product').' '.__('messages.barcode')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               name="barcode" id="barcode"
                                               placeholder="{{__('messages.product').' '.__('messages.barcode')}}"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row mb-3">
                                        <!--begin::Button-->
                                        <button type="button" id="kt_generate_product_barcode"
                                                class="btn btn-primary me-3">
                                            Generate code
                                        </button>
                                        <!--end::Button-->
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.product').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.product').' '.__('messages.name')}}"
                                               name="name"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.cost')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.cost')}}"
                                               name="cost"/>
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
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.price')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.price')}}"
                                               name="price"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span>{{__('messages.order')}}</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                               data-bs-toggle="tooltip"
                                               title="Order of product"></i>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="order"
                                                aria-label="{{__('messages.select').' '.__('messages.order')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select').' '.__('messages.order')}}"
                                                data-dropdown-parent="#kt_modal_add_product"
                                                class="form-select form-select-solid fw-bolder">
                                            <option value="1" selected>1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
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
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.category')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="category_id"
                                                aria-label="{{__('messages.select').' '.__('messages.category')}}"
                                                data-control="select2"
                                                data-kt-src="{{route('user.products.categories.select_api')}}"
                                                data-placeholder="{{__('messages.select').' '.__('messages.category')}}"
                                                data-dropdown-parent="#kt_modal_add_product"
                                                class="form-select form-select-solid fw-bolder select_cat">
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.tax')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="tax_id"
                                                aria-label="{{__('messages.select').' '.__('messages.tax')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select').' '.__('messages.tax')}}"
                                                data-dropdown-parent="#kt_modal_add_product"
                                                class="form-select form-select-solid fw-bolder tax_id">
                                            <option></option>
                                            @foreach($tax as $tx)
                                                <option value="{{$tx->id}}">{{$tx->name}}</option>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.product').' '.__('messages.type')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="type"
                                                aria-label="{{__('messages.select').' '.__('messages.product').' '.__('messages.type')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select').' '.__('messages.product').' '.__('messages.type')}}"
                                                data-dropdown-parent="#kt_modal_add_product"
                                                class="form-select form-select-solid fw-bolder">
                                            <option></option>
                                            <option value="0">Product</option>
                                            <option value="1">Subscription</option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2"
                                           for="description">{{__('messages.product').' '.__('messages.description')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea id="description" class="form-control form-control-solid"
                                              placeholder="{{__('messages.product').' '.__('messages.description')}}"
                                              name="description"></textarea>
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
                            <button type="reset" id="kt_modal_add_product_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_add_product_submit" class="btn btn-primary">
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
        <!--end::Modal - Product - Add-->

        <!--begin::Modal - Product - Edit-->
        <div class="modal fade" id="kt_modal_update_product" tabindex="-1">
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
                    <form class="form" action="#" id="kt_modal_update_product_form"
                          data-kt-redirect="#">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_product_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">{{__('messages.edit').' '.__('messages.product')}}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_modal_update_product_close"
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
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_update_product_scroll"
                                 data-kt-scroll="true"
                                 data-kt-scroll-activate="{default: false, lg: true}"
                                 data-kt-scroll-max-height="auto"
                                 data-kt-scroll-dependencies="#kt_modal_update_product_header"
                                 data-kt-scroll-wrappers="#kt_modal_update_product_scroll"
                                 data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-12 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="required fs-6 fw-bold mb-2">{{__('messages.product').' '.__('messages.barcode')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               name="barcode" id="barcode" readonly
                                               placeholder="{{__('messages.product').' '.__('messages.barcode')}}"/>
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
                                            class="required fs-6 fw-bold mb-2">{{__('messages.product').' '.__('messages.name')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.product').' '.__('messages.name')}}"
                                               name="name"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.cost')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.cost')}}"
                                               name="cost"/>
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
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.price')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid"
                                               placeholder="{{__('messages.price')}}"
                                               name="price"/>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-bold mb-2">
                                            <span>{{__('messages.order')}}</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                               data-bs-toggle="tooltip"
                                               title="Order of product"></i>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="order"
                                                aria-label="{{__('messages.select').' '.__('messages.order')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select').' '.__('messages.order')}}"
                                                data-dropdown-parent="#kt_modal_update_product"
                                                class="form-select form-select-solid fw-bolder">
                                            <option value="1" selected>1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
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
                                        <label class="required fs-6 fw-bold mb-2"
                                               text="{{__('messages.category')}}"></label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="categoryId"
                                                aria-label="{{__('messages.select').' '.__('messages.category')}}"
                                                data-control="select2"
                                                data-kt-src="#"
                                                data-placeholder="{{__('messages.select').' '.__('messages.category')}}"
                                                data-dropdown-parent="#kt_modal_update_product"
                                                class="form-select form-select-solid fw-bolder select_category">
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-bold mb-2">{{__('messages.tax')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="taxId"
                                                aria-label="{{__('messages.select').' '.__('messages.tax')}}"
                                                data-control="select2"
                                                data-placeholder="{{__('messages.select').' '.__('messages.tax')}}"
                                                data-dropdown-parent="#kt_modal_update_product"
                                                class="form-select form-select-solid fw-bolder">
                                            {{--                                                <option th:each="tax: ${taxes}" th:value="${tax.id}"--}}
                                            {{--                                                        th:text="${tax.name}"></option>--}}
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2"
                                           for="description">{{__('messages.product').' '.__('messages.description')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea id="description1" class="form-control form-control-solid"
                                              placeholder="{{__('messages.product').' '.__('messages.description')}}"
                                              name="description"></textarea>
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
                            <button type="reset" id="kt_modal_update_product_cancel" class="btn btn-light me-3">
                                Discard
                            </button>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_modal_update_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Update</span>
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
        <!--end::Modal - Product - Edit-->

        <!--end::Modals-->
    </div>
    <!--end::Container-->
@stop

@push('custom_scripts')
    <script src="{{asset('assets/js/pages/products/list.js')}}"></script>
    <script src="{{asset('assets/js/pages/products/add.js')}}"></script>
    <script src="{{asset('assets/js/pages/products/update.js')}}"></script>
@endpush
