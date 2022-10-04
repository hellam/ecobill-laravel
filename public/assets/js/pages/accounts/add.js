"use strict";

// Class definition
const KTBankAccountAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_account'));
            form = document.querySelector('#kt_modal_add_account_form');
            submitButton = form.querySelector('#kt_modal_add_account_submit');
            discardButton = form.querySelector('#kt_modal_add_account_cancel');
            closeButton = form.querySelector('#kt_modal_add_account_close');

            handleFormSubmit(
                form,
                {
                    account_name: {
                        validators: {
                            notEmpty: {
                                message: 'Account name is required'
                            }
                        }
                    },
                    account_number: {
                        validators: {
                            notEmpty: {
                                message: 'Account number is required'
                            }
                        }
                    },
                    currency: {
                        validators: {
                            notEmpty: {
                                message: 'Currency is required'
                            }
                        }
                    },
                    chart_code: {
                        validators: {
                            notEmpty: {
                                message: 'Transactions GL account is required'
                            }
                        }
                    },
                    charge_chart_code: {
                        validators: {
                            notEmpty: {
                                message: 'Charges GL account is required'
                            },
                            different: {
                                compare: function () {
                                    return form.querySelector('[name="chart_code"]').value;
                                },
                                message: 'Transactions GL account and Charges GL account cannot be the same',
                            },
                        }
                    },
                    branch_id: {
                        validators: {
                            notEmpty: {
                                message: 'Branch is required'
                            }
                        }
                    },
                },
                $('#kt_modal_add_account_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                null,
                ["chart_code", "charge_chart_code", "branch_id", "currency"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBankAccountAdd.init();
});
