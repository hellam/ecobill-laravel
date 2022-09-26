"use strict";

const KTAuthNewPassword = function () {
    let form, submitButton, validator, num_characters, redirect;
    let handleForm = function () {
        validator = FormValidation.formValidation(form, {
            fields: {
                old_password: {
                    validators: {
                        notEmpty: {message: "Old password is required"},
                    }
                },
                new_password: {
                    validators: {
                        notEmpty: {message: "The password is required"},
                        stringLength: {
                            min: num_characters,
                            message: 'Password must be more than ' + num_characters + ' characters long',
                        },
                    }
                },
                new_password_confirmation: {
                    validators: {
                        notEmpty: {message: "The password confirmation is required"},
                        identical: {
                            compare: function () {
                                return form.querySelector('[name="new_password"]').value
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
            validator.revalidateField("new_password")
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    submitButton.setAttribute("data-kt-indicator", "on")
                    submitButton.disabled = !0

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: form.getAttribute("data-kt-action"),
                        data: {
                            old_password: btoa(form.querySelector('[name="old_password"]').value),
                            new_password: btoa(form.querySelector('[name="new_password"]').value),
                            new_password_confirmation: btoa(form.querySelector('[name="new_password_confirmation"]').value),
                        },
                        success: function (json) {
                            let response = JSON.parse(JSON.stringify(json));
                            if (response.status !== true) {
                                let errors = response.data;
                                for (const [key, value] of Object.entries(errors)) {
                                    $('#err_' + value.field).remove();
                                    $("input[name='" + value.field + "']")
                                        .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                        .on('keyup', function (e) {
                                            $('#err_' + value.field).remove();
                                        })
                                }

                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });

                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        // Reload
                                        window.location.href = form.getAttribute('data-kt-redirect-url');
                                    }
                                });
                            }
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable submit button after loading
                            submitButton.disabled = false;

                        },
                        error: function (xhr, desc, err) {
                            Swal.fire({
                                text: 'A network error occured. Please consult your network administrator.',
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });

                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable submit button after loading
                            submitButton.disabled = false;

                        }
                    });
                } else {
                    Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {confirmButton: "btn btn-primary"}
                    })
                }
            });
        }))
        form.querySelector('input[name="new_password"]').addEventListener("input", (function () {
            this.value.length > 0 && validator.updateFieldStatus("new_password", "NotValidated")
        }))
    }


    return {
        init: function () {
            form = document.querySelector("#kt_new_password__form")
            submitButton = document.querySelector("#kt_new_password_submit")
            redirect = form.getAttribute("data-kt-redirect-url")
            num_characters = form.getAttribute('data-kt-num-characters')

            handleForm();
        }
    }

}();
KTUtil.onDOMContentLoaded((function () {
    KTAuthNewPassword.init()
}));
