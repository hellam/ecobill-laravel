"use strict";

// Class definition
const KTBranchesAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, validator, form, modal;

    const handleForm = function () {
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
                    $("select[name='tax_period']").val(null).trigger('change');
                    $("select[name='default_currency']").val(null).trigger('change');
                    $("select[name='fiscal_year']").val(null).trigger('change');
                    $("select[name='timezone']").val(null).trigger('change');
                    modal.hide(); // Hide modal
                }
            });
        });
    }

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_branch'));
            form = document.querySelector('#kt_modal_add_branch_form');
            submitButton = form.querySelector('#kt_modal_add_branch_submit');
            discardButton = form.querySelector('#kt_modal_add_branch_cancel');
            closeButton = form.querySelector('#kt_modal_add_branch_close');
            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBranchesAdd.init();
});
