@extends('user.auth.layout.app')
@section('title', 'Login')
@section('content')
    <!--begin::Body-->
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <!--begin::Wrapper-->
        <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10">
            <!--begin::Content-->
            <div class="w-md-400px">
                <!--begin::Form-->
                <form class="form w-100" action="{{route('user.auth.login')}}" method="post">
                    @csrf
                    <!--begin::Heading-->
                    <div class="text-center mb-11">
                        <!--begin::Title-->
                        <h1 class="text-dark fw-bolder mb-3">Sign In - Admin</h1>
                        <!--end::Title-->
                        <!--begin::Subtitle-->
                        <div class="text-gray-500 fw-semibold fs-6">Welcome Back</div>
                        <!--end::Subtitle=-->
                    </div>
                    <!--begin::Heading-->
                    <!--begin::Input group=-->
                    <div class="fv-row mb-8">
                        <!--begin::Email-->
                        <input type="text" placeholder="Email" name="email"
                               class="form-control bg-transparent" value="{{ old('email') }}" required
                               autocomplete="email" autofocus/>
                        <!--end::Email-->
                    </div>
                    <!--end::Input group=-->
                    <div class="fv-row mb-3">
                        <!--begin::Password-->
                        <input type="password" placeholder="Password" name="password"
                               class="form-control bg-transparent" autocomplete="current-password"/>
                        <!--end::Password-->
                    </div>
                    <!--end::Input group=-->
                    <div class="form-group mt-4 mb-4">
                        <div class="captcha">
                            <span>{!! captcha_img('math') !!}</span>
                            <a type="button" class="btn btn-danger" id="btn_reload">
                                &#x21bb;
                            </a>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                    </div>
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8 mt-8">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <!--begin::Link-->
                        <a class="link-primary" href="javascript:">
                            {{ __('messages.forgot_password') }}
                        </a>
                        <!--end::Link-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Submit button-->
                    <div class="d-grid mb-10">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                            <!--begin::Indicator label-->
                            <span class="indicator-label">Sign In</span>
                            <!--end::Indicator label-->
                            <!--begin::Indicator progress-->
                            <span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            <!--end::Indicator progress-->
                        </button>
                    </div>
                    <!--end::Submit button-->
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
    <script type="text/javascript">
        $('#btn_reload').click(function () {
            const btn_reload = $('#btn_reload');
            const loading = $('#loading1');

            btn_reload.hide();
            loading.show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{route('user.captcha_reload')}}',
                success: function (json) {
                    $(".captcha span").html(json.captcha);

                    btn_reload.show();
                    loading.hide();
                }
            });
        });

    </script>
@endpush
