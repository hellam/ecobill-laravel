"use strict";

// Class definition
const KTCustomerBranchAdd = function () {
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer_branch'));
            form = document.querySelector('#kt_modal_add_customer_branch_form');
            submitButton = form.querySelector('#kt_modal_add_customer_branch_submit');
            cancelButton = form.querySelector('#kt_modal_add_customer_branch_cancel');
            closeButton = form.querySelector('#kt_modal_add_customer_branch_close');

            handleFormSubmit(
                form,
                {
                    customer_id: {
                        validators: {
                            notEmpty: {
                                message: 'Customer is required'
                            }
                        }
                    },
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'First Name is required'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'Last Name is required'
                            }
                        }
                    },
                    short_name: {
                        validators: {
                            notEmpty: {
                                message: 'Short Name is required'
                            }
                        }
                    },
                    country: {
                        validators: {
                            notEmpty: {
                                message: 'Country is required'
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: 'Phone is required'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_customer_branch_form'),
                cancelButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_customer_branches_table'),
                ["customer_id", "country"]
            );

            handleCustomerAPISelect('#kt_modal_add_customer_branch')
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomerBranchAdd.init();
});
