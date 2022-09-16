"use strict";
const KTAuthNewPassword = function () {
    let form, submitButton, validation, num_characters, redirect;

    return {
        init: function () {
            form = document.querySelector("#kt_new_password__form")
            submitButton = document.querySelector("#kt_new_password_submit")
            redirect = form.getAttribute("data-kt-redirect-url")
            num_characters = form.getAttribute('data-kt-num-characters')
            validation = FormValidation.formValidation(form, {
                fields: {
                    old_password: {
                        validators: {
                            notEmpty: {message: "Old password is required"},
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {message: "The password is required"},
                            stringLength: {
                                min: num_characters,
                                message: 'Password must be more than ' + num_characters + ' characters long',
                            },
                        }
                    },
                    confirm_password: {
                        validators: {
                            notEmpty: {message: "The password confirmation is required"},
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value
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
            })

            submitButton.addEventListener("click", (function (a) {
                a.preventDefault()
                validation.revalidateField("password")
                validation.validate().then((function (r) {
                    "Valid" == validation ? (
                            submitButton.setAttribute("data-kt-indicator", "on"),
                                submitButton.disabled = !0,
                                setTimeout((function () {
                                    submitButton.removeAttribute("data-kt-indicator")
                                    submitButton.disabled = !1
                                    Swal.fire({
                                        text: "You have successfully reset your password!",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {confirmButton: "btn btn-primary"}
                                    }).then((function (e) {
                                        if (e.isConfirmed) {
                                            form.querySelector('[name="old_password"]').value = ""
                                            form.querySelector('[name="password"]').value = ""
                                            form.querySelector('[name="confirm_password"]').value = "";
                                            location.href = redirect
                                        }
                                    }))
                                }), 1500))
                        :
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                }))
            }))
            form.querySelector('input[name="password"]').addEventListener("input", (function () {
                this.value.length > 0 && validation.updateFieldStatus("password", "NotValidated")
            }))
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTAuthNewPassword.init()
}));
