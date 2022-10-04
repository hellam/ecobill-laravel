"use strict";

// Class definition
const KTBranchesUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_branch'));
            form = document.querySelector('#kt_modal_update_branch_form');
            cancelButton = form.querySelector('#kt_modal_update_branch_cancel');
            submitButton = form.querySelector('#kt_modal_update_branch_submit');
            closeButton = document.querySelector('#kt_modal_update_branch_close');

            handleFormSubmit(
                form,
                {
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
                },
                $('#kt_modal_update_branch_form'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_branches_table'),
                ["default_bank_account", "timezone", "tax_period", "default_currency", "fiscal_year"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBranchesUpdate.init();
});
