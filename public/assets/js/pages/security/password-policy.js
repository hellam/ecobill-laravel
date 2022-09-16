"use strict";

// Class definition
const KTPasswordPolicy = function () {
    let submitButton;
    let validator;
    let form;

    // Init form inputs
    const handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'pass_expiry': {
                        validators: {
                            notEmpty: {
                                message: 'Password expiry is required'
                            }
                        }
                    },
                    'min_length': {
                        validators: {
                            notEmpty: {
                                message: 'Minimum password length is required'
                            }
                        }
                    },
                    'pass_history': {
                        validators: {
                            notEmpty: {
                                message: 'Password history is required'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Action buttons
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        const str = $('#kt_password_policy_form').serialize();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: form.getAttribute("data-kt-action"),
                            data: str,
                            success: function (json) {
                                let response = JSON.parse(JSON.stringify(json));
                                if (response.status !== true) {
                                    let errors = response.data;
                                    for (const [key, value] of Object.entries(errors)) {
                                        $('#err_' + value.field).remove();
                                        if ($("input[name='" + value.field + "']")) {
                                            $("input[name='" + value.field + "']")
                                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_' + value.field).remove();
                                                })
                                        }
                                        if (value.field === 'combination') {
                                            $('#combination_span')
                                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_' + value.field).remove();
                                                })
                                        }
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
                                            window.location.reload();
                                        }
                                    });
                                }
                                submitButton.removeAttribute('data-kt-indicator');

                                // Enable submit button after loading
                                submitButton.disabled = false;

                            },
                            error: function (xhr, desc, err) {
                                console.log(xhr)
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
                        console.log(str)
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });

        $("#kt_password_policy_form input[id='first_time_login']").on('change', function () {
            if ($(this).is(':checked'))
                $("#kt_password_policy_form input[name='first_time']").val(1)
            else {
                $("#kt_password_policy_form input[name='first_time']").val(0)
            }
        })
    };

    return {
        // Public functions
        init: function () {
            form = document.querySelector('#kt_password_policy_form');
            submitButton = form.querySelector('#kt_password_policy_submit');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPasswordPolicy.init();
});
