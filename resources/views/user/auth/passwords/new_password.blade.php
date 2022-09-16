@extends('user.auth.layout.app')
@section('title', 'New Password')
@section('content')
    <!--begin::Body-->
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <!--begin::Wrapper-->
        <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10">
            <!--begin::Content-->
            <div class="w-md-400px">
                <!--begin::Form-->
                <form class="form w-100" action="{{route('user.auth.login')}}" method="post" id="kt_new_password_form"
                      autocomplete="off">
                    @csrf
                    <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark fw-bolder mb-3">Setup New Password</h1>
                        <!--end::Title-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-6">Have you already reset the password ?
                            <a href="#"
                               class="link-primary fw-bold">Sign in</a></div>
                        <!--end::Link-->
                    </div>
                    <!--begin::Heading-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-8" data-kt-password-meter="true">
                        <!--begin::Wrapper-->
                        <div class="mb-1">
                            <!--begin::Input wrapper-->
                            <div class="position-relative mb-3">
                                <input class="form-control bg-transparent" type="password" placeholder="Password"
                                       name="password" autocomplete="off"/>
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                      data-kt-password-meter-control="visibility">
												<i class="bi bi-eye-slash fs-2"></i>
												<i class="bi bi-eye fs-2 d-none"></i>
											</span>
                            </div>
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Hint-->
                        <div class="text-muted">
                            Use {{$security_array[1]}} or more characters with
                            @if(count($security_array[2]) > 1)
                                a mix of
                                @if(\App\CentralLogics\array_equal([1,2], $security_array[2]))
                                    digits & special characters.
                                @elseif(\App\CentralLogics\array_equal([1,3], $security_array[2]))
                                    digits & uppercase letters.
                                @elseif(\App\CentralLogics\array_equal([1,4], $security_array[2]))
                                    digits & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([1,2,3], $security_array[2]))
                                    digits, special characters & uppercase letters.
                                @elseif(\App\CentralLogics\array_equal([1,2,3,4], $security_array[2]))
                                    digits, special characters, uppercase letters & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([1,2,4], $security_array[2]))
                                    digits, special characters & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([1,3,4], $security_array[2]))
                                    digits, uppercase letters & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([2,3], $security_array[2]))
                                    special characters & uppercase letters.
                                @elseif(\App\CentralLogics\array_equal([2,4], $security_array[2]))
                                    special characters & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([2,3,4], $security_array[2]))
                                    special characters, uppercase letters & lowercase letters.
                                @elseif(\App\CentralLogics\array_equal([3,4], $security_array[2]))
                                    uppercase letters & lowercase letters.
                                @endif
                            @else
                                @if(in_array(1, $security_array[2]))
                                    digits
                                @elseif(in_array(2, $security_array[2]))
                                    special characters
                                @elseif(in_array(3, $security_array[2]))
                                    uppercase letters
                                @elseif(in_array(4, $security_array[2]))
                                    lowercase letters
                                @endif
                                only.
                            @endif
                        </div>
                        <!--end::Hint-->
                    </div>
                    <!--end::Input group-->
                    <!--end::Input group-->
                    <div class="fv-row mb-8">
                        <!--begin::Repeat Password-->
                        <input type="password" placeholder="Repeat Password" name="confirm-password" autocomplete="off"
                               class="form-control bg-transparent"/>
                        <!--end::Repeat Password-->
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Action-->
                    <div class="d-grid mb-10">
                        <button type="button" id="kt_new_password_submit" class="btn btn-primary">
                            <!--begin::Indicator label-->
                            <span class="indicator-label">Submit</span>
                            <!--end::Indicator label-->
                            <!--begin::Indicator progress-->
                            <span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            <!--end::Indicator progress-->
                        </button>
                    </div>
                    <!--end::Action-->
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
    <script>
        const KTAuthNewPassword = function () {
            let t, e, r, a;
            return {
                init: function () {
                    t = document.querySelector("#kt_new_password_form"),
                        e = document.querySelector("#kt_new_password_submit"),
                        r = FormValidation.formValidation(t, {
                            fields: {
                                password: {
                                    validators: {
                                        notEmpty: {message: "The password is required"},
                                        stringLength: {
                                            min: {{$security_array[1]}},
                                            message: 'Password must be more than '+{{$security_array[1]}}+' characters long',
                                        },
                                    }
                                },
                                "confirm-password": {
                                    validators: {
                                        notEmpty: {message: "The password confirmation is required"},
                                        identical: {
                                            compare: function () {
                                                return t.querySelector('[name="password"]').value
                                            }, message: "The password and its confirm are not the same"
                                        }
                                    }
                                }
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger({event: {password: !1}}),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
                                })
                            }
                        }), e.addEventListener("click", (function (a) {
                        a.preventDefault(), r.revalidateField("password"), r.validate().then((function (r) {
                            "Valid" == r ? (e.setAttribute("data-kt-indicator", "on"), e.disabled = !0, setTimeout((function () {
                                e.removeAttribute("data-kt-indicator"), e.disabled = !1, Swal.fire({
                                    text: "You have successfully reset your password!",
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {confirmButton: "btn btn-primary"}
                                }).then((function (e) {
                                    if (e.isConfirmed) {
                                        t.querySelector('[name="password"]').value = "", t.querySelector('[name="confirm-password"]').value = "";
                                        var r = t.getAttribute("data-kt-redirect-url");
                                        r && (location.href = r)
                                    }
                                }))
                            }), 1500)) : Swal.fire({
                                text: "Sorry, looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {confirmButton: "btn btn-primary"}
                            })
                        }))
                    })), t.querySelector('input[name="password"]').addEventListener("input", (function () {
                        this.value.length > 0 && r.updateFieldStatus("password", "NotValidated")
                    }))
                }
            }
        }();
        KTUtil.onDOMContentLoaded((function () {
            KTAuthNewPassword.init()
        }));
    </script>
@endpush
