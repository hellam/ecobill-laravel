"use strict";

// Class definition
const KTProductsAdd = function () {
    let submitButton, cancelButton, closeButton, form, modal;

    document.querySelector('#kt_generate_product_barcode').addEventListener('click', function (e) {
        e.preventDefault();
        $('#barcode').val(Math.floor(Math.random() * 100000000))
    })


    const handleDTSelect = function () {
        const element = document.querySelector('.select_category');
        $('.select_category').select2({
            placeholder: 'Select Category',
            minimumInputLength: 0,
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: element.getAttribute("data-kt-src"),
                type: 'GET',
                delay: 50,
                // processData: false,
                data: function (params) {
                    // Query parameters will be ?name=[term]&description=public
                    return {
                        name: params.term || "",
                        description: params.term || ""
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                            }
                        })
                    }
                }
            }
        })
    };

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_product'));

            form = document.querySelector('#kt_modal_add_product_form');
            submitButton = form.querySelector('#kt_modal_add_product_submit');
            cancelButton = form.querySelector('#kt_modal_add_product_cancel');
            closeButton = form.querySelector('#kt_modal_add_product_close');

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
                                message: 'Tax is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_product_form'),
                cancelButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_products_table'),
                ["tax_id", "category_id", "order", "type"],
            );

            handleDTSelect()
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTProductsAdd.init();
});
