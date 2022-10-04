"use strict";

// Class definition
const KTGLAccountsAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_gl_account'));
            form = document.querySelector('#kt_modal_add_gl_account_form');
            submitButton = form.querySelector('#kt_modal_add_gl_account_submit');
            discardButton = form.querySelector('#kt_modal_add_gl_account_cancel');
            closeButton = form.querySelector('#kt_modal_add_gl_account_close');

            handleFormSubmit(
                form,
                {
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
                $('#kt_modal_add_gl_account_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_gl_accounts_table'),
                ["account_group"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLAccountsAdd.init();
});
