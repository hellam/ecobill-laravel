@extends('auth.layout.app')
@section('title', 'Password Reset')
@section('content')
    <!--begin::Body-->
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <!--begin::Wrapper-->
        <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10">
            <!--begin::Content-->
            <div class="w-md-400px">
                <!--begin::Form-->
                <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form"
                      data-kt-redirect-url="{{route('login')}}"
                      data-kt-email-url="{{ route('password.email') }}"
                      action="#">
                    <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark fw-bolder mb-3">Forgot Password ?</h1>
                        <!--end::Title-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-6">Enter your email to reset your password.</div>
                        <!--end::Link-->
                    </div>
                    <!--begin::Heading-->
                    <!--begin::Input group=-->
                    <div class="fv-row mb-8">
                        <!--begin::Email-->
                        <input type="text" placeholder="Email" name="email" autocomplete="off"
                               class="form-control bg-transparent"/>
                        <!--end::Email-->
                    </div>
                    <!--begin::Actions-->
                    <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                        <button type="button" id="kt_password_reset_submit" class="btn btn-primary me-4">
                            <!--begin::Indicator label-->
                            <span class="indicator-label">Submit</span>
                            <!--end::Indicator label-->
                            <!--begin::Indicator progress-->
                            <span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            <!--end::Indicator progress-->
                        </button>
                        <a href="{{route('login')}}" class="btn btn-light">Cancel</a>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Body-->
@stop
@push('custom_scripts')
    <!--begin::Custom Javascript(used by this page)-->
    <script src="{{asset('assets/js/custom/authentication/reset-password/reset-password.js')}}"></script>
    <!--end::Custom Javascript-->
@endpush
