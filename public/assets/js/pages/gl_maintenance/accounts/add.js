"use strict";

// Class definition
const KTGLAccountsAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, validator, form, modal;

    const handleForm = function () {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    account_code: {
                        validators: {
                            notEmpty: {
                                message: 'Account Code is required'
                            }
                        }
                    },
                    account_name: {
                        validators: {
                            notEmpty: {
                                message: 'Account Name is required'
                            }
                        }
                    },
                    account_group: {
                        validators: {
                            notEmpty: {
                                message: 'Group is required'
                            }
                        }
                    },
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

        // //revalidate account_type select
        $(form.querySelector('[name="account_group"]')).on('change', function () {
            // Revalidate the field when an option is chosen
            validator.revalidateField("account_group");
        })

        // Action buttons
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        let str = $('#kt_modal_add_gl_account_form').serialize();
                        submitData(str);
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
                })
            }
        });

        discardButton.addEventListener('click', function (e) {
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
                    $("select[name='account_type']").val(null).trigger('change');
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
                    $("select[name='account_type']").val(null).trigger('change');
                    modal.hide(); // Hide modal
                }
            });
        });
    }

    function submitData(str) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: form.getAttribute("data-kt-action"),
            data: str,
            success: function (json) {
                var response = JSON.parse(JSON.stringify(json));
                if (response.status !== true) {
                    var errors = response.data;
                    for (const [key, value] of Object.entries(errors)) {
                        $('#err_' + value.field).remove();
                        if ($("input[name='" + value.field + "']").length) {
                            $("input[name='" + value.field + "']")
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('keyup', function (e) {
                                    $('#err_' + value.field).remove();
                                })
                        }

                        if ($("select[name='" + value.field + "']").length) {
                            $("select[name='" + value.field + "']")
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
                            // Hide modal
                            modal.hide();
                            // Enable submit button after loading
                            submitButton.disabled = false;
                            form.reset(); // Reset form

                            $("select[name='account_type']").val(null).trigger('change');

                            if ($('#kt_gl_accounts_table').length) {
                                $("#kt_gl_accounts_table").DataTable().ajax.reload();
                                return;
                            }
                            // Redirect to customers list page
                            window.location = form.getAttribute("data-kt-redirect");
                        }
                    });
                }
                submitButton.removeAttribute('data-kt-indicator');

                // Enable submit button after loading
                submitButton.disabled = false;

            },
            statusCode: {
                203: function () {
                    modal.hide()//hide modal
                    Swal.fire({
                        text: "Please provide remarks",
                        icon: "info",
                        input: 'textarea',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        allowOutsideClick: false,
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Submit",
                        cancelButtonText: "Cancel",
                        // showLoaderOnConfirm: true,
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        // delete row data from server and re-draw datatable
                        if (result.isConfirmed) {
                            modal.show()//show modal
                            str = str + "&remarks=" + result.value
                            submitData(str)
                        } else {
                            form.reset(); // Reset form
                            $("select[name='account_type']").val(null).trigger('change');
                        }
                    });
                }
            },
            error: function () {
                Swal.fire({
                    text: 'A network error occurred. Please consult your network administrator.',
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
    }

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_gl_account'));
            form = document.querySelector('#kt_modal_add_gl_account_form');
            submitButton = form.querySelector('#kt_modal_add_gl_account_submit');
            discardButton = form.querySelector('#kt_modal_add_gl_account_cancel');
            closeButton = form.querySelector('#kt_modal_add_gl_account_close');
            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLAccountsAdd.init();
});
