"use strict";

// Class definition
const KTCustomersUpdate = function () {
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_customer'));
            form = document.querySelector('#kt_modal_update_customer_form');
            submitButton = form.querySelector('#kt_modal_update_customer_submit');
            cancelButton = form.querySelector('#kt_modal_update_customer_cancel');
            closeButton = form.querySelector('#kt_modal_update_customer_close');

            handleFormSubmit(
                form,
                {
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
                    address: {
                        validators: {}
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
                    },
                    currency: {
                        validators: {
                            notEmpty: {
                                message: 'Currency is required'
                            }
                        }
                    },
                    pay_terms: {
                        validators: {
                            notEmpty: {
                                message: 'Payment terms required'
                            }
                        }
                    },
                    tax: {
                        validators: {
                            notEmpty: {
                                message: 'Tax is required'
                            }
                        }
                    },
                    sales_type: {
                        validators: {
                            notEmpty: {
                                message: 'Sales type is required'
                            }
                        }
                    },
                    credit_status: {
                        validators: {
                            notEmpty: {
                                message: 'Credit status type is required'
                            }
                        }
                    },
                    credit_limit: {
                        validators: {
                            notEmpty: {
                                message: 'Credit limit type is required'
                            }
                        }
                    },
                    language: {
                        validators: {
                            notEmpty: {
                                message: 'Language is required'
                            }
                        }
                    },
                    discount: {
                        validators: {
                            notEmpty: {
                                message: 'Discount is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_customer_form'),
                cancelButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_customers_table'),
                ["language", "credit_status", "sales_type", "tax", "pay_terms", "currency", "country"]
            );
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersUpdate.init();
});
