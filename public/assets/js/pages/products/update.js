"use strict";

// Class definition
const KTProductsUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_product'));
            form = document.querySelector('#kt_modal_update_product_form');
            cancelButton = form.querySelector('#kt_modal_update_product_cancel');
            submitButton = form.querySelector('#kt_modal_update_product_submit');
            closeButton = document.querySelector('#kt_modal_update_product_close');

            handleFormSubmit(
                form,
                {
                    barcode: {
                        validators: {
                            notEmpty: {
                                message: 'Barcode is required'
                            }
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Product name is required'
                            }
                        }
                    },
                    cost: {
                        validators: {
                            notEmpty: {
                                message: 'Product cost is required'
                            }
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: 'Product price is required'
                            }
                        }
                    },
                    category_id: {
                        validators: {
                            notEmpty: {
                                message: 'Category is required'
                            }
                        }
                    },
                    order: {
                        validators: {}
                    },
                    tax_id: {
                        validators: {
                            notEmpty: {
                                message: 'Tax is required'
                            }
                        }
                    },
                    type: {
                        validators: {
                            notEmpty: {
                                message: 'Type is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_product_form'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_products_table'),
                ["type", "tax_id", "order", "category_id"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTProductsUpdate.init();
});
