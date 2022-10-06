"use strict";

// Class definition
const KTSubscriptionPackagesAdd = function () {
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_package'));
            form = document.querySelector('#kt_modal_add_package_form');
            submitButton = form.querySelector('#kt_modal_add_package_submit');
            cancelButton = form.querySelector('#kt_modal_add_package_cancel');
            closeButton = form.querySelector('#kt_modal_add_package_close');

            handleFormSubmit(
                form,
                {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Name is required'
                            }
                        }
                    },
                    features: {
                        validators: {}
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Description is required'
                            }
                        }
                    },
                    validity: {
                        validators: {
                            notEmpty: {
                                message: 'Validity is required'
                            }
                        }
                    },
                    product_id: {
                        validators: {
                            notEmpty: {
                                message: 'Product is required'
                            }
                        }
                    },
                    order: {
                        validators: {}
                    }
                },
                $('#kt_modal_add_package_form'),
                cancelButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_packages_table'),
                ["product_id", "order"],
                'features_add'
            );

            handleProductsAPISelect('#kt_modal_add_package')

            createCKEditor('features_add')
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSubscriptionPackagesAdd.init();
});
