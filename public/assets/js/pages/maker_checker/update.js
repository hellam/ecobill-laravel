"use strict";

// Class definition
const KTMakerCheckerRulesUpdate = function () {
    // Shared variables
    let submitButton;
    let cancelButton;
    let closeButton;
    let validator;
    let form;
    let modal;

    //handle form
    const handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    action: {
                        validators: {
                            notEmpty: {
                                message: "Permission is required"
                            }
                        }
                    },
                    maker_type: {
                        validators: {
                            notEmpty: {
                                message: "Type is required"
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
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

                        const str = $('#kt_modal_update_rule_form').serialize();
                        console.log(str)
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'PUT',
                            url: form.getAttribute("data-kt-action"),
                            data: str,
                            success: function (json) {
                                var response = JSON.parse(JSON.stringify(json));
                                if (response.status !== true) {
                                    var errors = response.data;
                                    for (const [key, value] of Object.entries(errors)) {
                                        $('#err_' + value.field).remove();
                                        if ("select[name='" + value.field + "']") {
                                            $("#action")
                                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                                .on('change', function (e) {
                                                    $('#err_' + value.field).remove();
                                                })
                                        }
                                        if (value.field === 'maker_type') {
                                            $('#maker_type2').after('<small style="color: red;" id="err_maker_type">' + value.error + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_maker_type').remove();
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
                                            // Hide modal
                                            modal.hide();
                                            $("#permissions_select").val(null).trigger('change');
                                            $("#maker_type1").prop("checked", true);

                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                            if ($('#kt_maker_checker_rules_table').length) {
                                                $("#kt_maker_checker_rules_table").DataTable().ajax.reload();
                                                return;
                                            }
                                            // Redirect to Taxes list page
                                            window.location = form.getAttribute("data-kt-redirect");
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

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide(); // Hide modal
                }
            });
        });

        closeButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide(); // Hide modal
                }
            });
        })
    }

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_rule'));

            form = document.querySelector('#kt_modal_update_rule_form');
            cancelButton = form.querySelector('#kt_modal_update_rule_cancel');
            submitButton = form.querySelector('#kt_modal_update_rule_submit');
            closeButton = document.querySelector('#kt_modal_update_rule_close');

            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTMakerCheckerRulesUpdate.init();
});
