@extends('layout.app')
@section('title', 'Taxes - EcoBill')
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
                <a href="{{route('dashboard')}}" class="text-muted text-hover-primary">{{__('messages.home')}}</a>
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
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"
                           href="#kt_tab_pane_1">{{__('messages.products')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">{{__('messages.categories')}}
                        </a>
                    </li>
                </ul>
                <!--begin::Row-->
                <!--begin::Card-->
                <div class="card tab-content">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
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
                                        data-bs-target="#kt_modal_add_tax"
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
                    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                        <!--begin::Categories Wrapper-->
                        @if($categories)
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
                                        <input type="text" data-kt-category-table-filter="search"
                                               class="form-control form-control-solid w-250px ps-15"
                                               placeholder="{{__('messages.search').' '.__('messages.products')}}"/>
                                    </div>
                                    <!--end::Search-->
                                </div>
                                <!--begin::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Toolbar-->
                                    <div class="d-flex justify-content-end" data-kt-category-table-toolbar="base">
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
                                                            data-kt-category-table-filter="month"
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
                                                    <label class="form-label fs-5 fw-bold mb-3">Payment
                                                        Type:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Options-->
                                                    <div class="d-flex flex-column flex-wrap fw-bold"
                                                         data-kt-category-table-filter="payment_type">
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
                                                            data-kt-category-table-filter="reset">
                                                        Reset
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-category-table-filter="filter">Apply
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                        <!--end::Menu 1-->
                                        <!--end::Filter-->
                                        <!--begin::Add customer-->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_add_category">
                                            {{__('messages.add').' '.__('messages.new')}}
                                        </button>
                                        <!--end::Add customer-->
                                    </div>
                                    <!--end::Toolbar-->
                                    <!--begin::Group actions-->
                                    <div class="d-flex justify-content-end align-items-center d-none"
                                         data-kt-category-table-toolbar="selected">
                                        <div class="fw-bolder me-5">
                                            <span class="me-2" data-kt-group-table-select="selected_count"></span>Selected
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                                data-kt-category-table-select="delete_selected">Delete Selected
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
                                <table class="table align-middle table-row-dashed fs-6 gy-5"
                                       id="kt_categories_table" data-kt-dt_api="#">
                                    <!--begin::Table head-->
                                    <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                       data-kt-check-target="#kt_categories_table .form-check-input"
                                                       value="1"/>
                                            </div>
                                        </th>
                                        <th class="min-w-125px">{{__('messages.category').' '.__('messages.name')}}</th>
                                        <th class="min-w-125px">{{__('messages.description')}}</th>
                                        <th class="min-w-125px">{{__('messages.default').' '.__('messages.tax')}}</th>
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
                            <!--end::Categories Wrapper-->
                        @else
                            <!--begin::No Categories Wrapper-->
                            <div class="card-px text-center py-20 my-10">
                                <!--begin::Title-->
                                <h2 class="fs-2x fw-bolder mb-10">{{__('messages.welcome_to_module',['attribute'=>__('messages.categories')])}}</h2>
                                <!--end::Title-->

                                <!--begin::Description-->
                                <p class="text-gray-400 fs-4 fw-bold mb-10">
                                    {{__('messages.not_found',['attribute'=>__('messages.categories')])}}
                                </p>
                                <!--end::Description-->

                                <!--begin::Action-->
                                <button data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_tax"
                                        class="btn btn-primary">{{__('messages.add',['attribute'=>__('messages.category')])}}
                                </button>
                                <!--end::Action-->

                                <!--begin::Illustration-->
                                <div class="text-center px-4">
                                    <img class="mw-100 mh-300px" alt=""
                                         src="{{asset('assets/media/illustrations/sketchy-1/2_.png')}}"/>
                                </div>
                                <!--end::Illustration-->
                            </div>
                            <!--end::No Categories Wrapper-->
                        @endif
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
@stop
