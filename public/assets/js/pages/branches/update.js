"use strict";

// Class definition
const KTBranchesUpdate = function () {
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
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Branch Name is required'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email is required'
                            }
                        }
                    }, phone: {
                        validators: {
                            notEmpty: {
                                message: 'Phone number is required'
                            }
                        }
                    }, tax_no: {
                        validators: {
                            notEmpty: {
                                message: 'Tax number is required'
                            }
                        }
                    },
                    tax_period: {
                        validators: {
                            notEmpty: {
                                message: 'Tax period is required'
                            }
                        }
                    },
                    default_currency: {
                        validators: {
                            notEmpty: {
                                message: 'Default currency is required'
                            }
                        }
                    },
                    fiscal_year: {
                        validators: {
                            notEmpty: {
                                message: 'Fiscal year is required'
                            }
                        }
                    },
                    timezone: {
                        validators: {
                            notEmpty: {
                                message: 'Timezone is required'
                            }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'Address is required'
                            }
                        }
                    },
                    default_bank_account: {
                        validators: {
                            notEmpty: {
                                message: 'Bank account is required'
                            }
                        }
                    }
                },plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        //revalidate all select boxes
        let select_fields = ["timezone", "tax_period", "default_currency", "fiscal_year"];
        select_fields.forEach(select => {
            $(form.querySelector(`[name=${select}]`)).on('change', function () {
                // Revalidate the field when an option is chosen
                validator.revalidateField(`${select}`);
            });
        })

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

                        const str = $('#kt_modal_update_branch_form').serialize()
                        handleSubmit(str)
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

    function handleSubmit(str) {
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
                        if ("input[name='" + value.field + "']") {
                            $("select[name='" + value.field + "']")
                                .input('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('change', function (e) {
                                    $('#err_' + value.field).remove();
                                })
                        }
                        if ("select[name='" + value.field + "']") {
                            $("select[name='" + value.field + "']")
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('change', function (e) {
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
                            if ($('#kt_branches_table').length) {
                                $("#kt_branches_table").DataTable().ajax.reload();
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
                        showLoaderOnConfirm: true,
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        // delete row data from server and re-draw datatable
                        if (result.isConfirmed) {
                            modal.show()//show modal
                            str = str + "&remarks=" + result.value
                            handleSubmit(str)
                        } else {
                            form.reset(); // Reset form
                        }
                    });
                }
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
    }

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_branch'));

            form = document.querySelector('#kt_modal_update_branch_form');
            cancelButton = form.querySelector('#kt_modal_update_branch_cancel');
            submitButton = form.querySelector('#kt_modal_update_branch_submit');
            closeButton = document.querySelector('#kt_modal_update_branch_close');

            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBranchesUpdate.init();
});
