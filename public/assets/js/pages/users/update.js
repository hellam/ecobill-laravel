"use strict";

// Class definition
const KTUsersUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_user'));

            form = document.querySelector('#kt_modal_update_user_form');
            cancelButton = form.querySelector('#kt_modal_update_user_cancel');
            submitButton = form.querySelector('#kt_modal_update_user_submit');
            closeButton = document.querySelector('#kt_modal_update_user_close');

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
                    }
                },
                $('#kt_modal_update_user_form'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_users_table'),
                null,
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdate.init();
});
