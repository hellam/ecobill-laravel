"use strict";

// Class definition
const KTUsersAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_user'));
            form = document.querySelector('#kt_modal_add_user_form');
            submitButton = form.querySelector('#kt_modal_add_user_submit');
            discardButton = form.querySelector('#kt_modal_add_user_cancel');
            closeButton = form.querySelector('#kt_modal_add_user_close');

            handleFormSubmit(
                form,
                {
                    full_name: {
                        validators: {
                            notEmpty: {
                                message: 'Full Name is required'
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
                    }, username: {
                        validators: {
                            notEmpty: {
                                message: 'Username is required'
                            }
                        }
                    }, password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_user_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_users_table'),
                null,
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAdd.init();
});
