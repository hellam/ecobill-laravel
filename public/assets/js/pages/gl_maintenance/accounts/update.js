"use strict";

// Class definition
const KTGLAccountsUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_gl_account'));
            form = document.querySelector('#kt_modal_update_gl_account_form');
            cancelButton = form.querySelector('#kt_modal_update_gl_account_cancel');
            submitButton = form.querySelector('#kt_modal_update_gl_account_submit');
            closeButton = document.querySelector('#kt_modal_update_gl_account_close');

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
                $('#kt_modal_update_gl_account_form'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_gl_accounts_table'),
                ["account_group"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLAccountsUpdate.init();
});
