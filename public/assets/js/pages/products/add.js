"use strict";

// Class definition
const KTProductsAdd = function () {
    let submitButton, cancelButton, closeButton, form, modal;

    document.querySelector('#kt_generate_product_barcode').addEventListener('click', function (e) {
        e.preventDefault();
        $('#barcode').val(Math.floor(Math.random() * 100000000))
    })

    const handleAPISelect = function () {
        const element = document.querySelector('.select_cat');
        $('.select_cat').select2({
            placeholder: 'Select Category',
            minimumInputLength: 0,
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: element.getAttribute("data-kt-src"),
                dataType: 'json',
                type: 'GET',
                contentType: 'application/json',
                delay: 50,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                                'data-default-tax-id': item.default_tax_id,
                            }
                        })
                    }
                }
            }
        }).on('select2:select', function (e) {
            let data = e.params.data;
            $(this).children('[value="' + data['id'] + '"]').attr(
                {
                    'data-default-tax-id': data["data-default-tax-id"], //dynamic value from data array
                }
            );
            $('.tax_id').val($('.select_cat').find(':selected').data('default-tax-id')).trigger('change')
        }).val(0).trigger('change');
    }

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

            handleAPISelect()
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTProductsAdd.init();
});
