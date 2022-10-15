"use strict";

// Class definition
const KTInvoiceAdd = function () {
    let form = $('#kt_invoice_form'),
        pay_terms = $('[name="pay_terms"]'),
        customer_select = $('[name="customer"]'),
        sb_total = 0;

    function handleInvoice() {
        $('[name="due_date"], [name="invoice_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            locale: {
                format: '' + form.attr('data-kt-date-format') + ''
            },
            autoApply: true,
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function (start, end, label) {
        });

        pay_terms.on('change', function () {
            let no_days = $(this).find(":selected").attr('data-kt-days'),
                type = $(this).find(":selected").attr('data-kt-type'),
                invoice_date = $('[name="invoice_date"]'),
                due_date = $('[name="due_date"]'),
                date_format = '' + pay_terms.attr('data-kt-date-format') + ''

            if (type == 1) {
                // type 1 number of days
                if (invoice_date.val()) {
                    due_date.val(moment(invoice_date.val(), date_format).add(no_days, 'days').format(date_format))
                    invoice_date.on('change', function () {
                        due_date.val(moment(invoice_date.val(), date_format).add(no_days, 'days').format(date_format))
                    })
                } else {
                    due_date.val(moment().format(date_format))
                }
            } else if (type == 2) {
                // type 2 day in the following month
                if (invoice_date.val()) {
                    due_date.val(moment(invoice_date.val(), date_format).endOf('month').add(no_days, 'days').format(date_format))
                } else {
                    due_date.val(moment().format(date_format))
                }
            } else {
                if (invoice_date.val()) {
                    due_date.val(invoice_date.val())
                } else {
                    due_date.val(moment().format(date_format))
                }
            }
        })

        $('[name="invoice_date"]').on('change', function () {
            if (!pay_terms.val() || pay_terms.find(":selected").attr('data-kt-type') == 0) {
                $('[name="due_date"]').val($('[name="invoice_date"]').val())
            }
        })

    }

    function initializeRepeater() {
        let repeater = $('.repeater_items').repeater({
            repeaters: [{
                selector: '.inner-repeater',
                initEmpty: true,
                show: function () {
                    let desc_add_btn = $(this).parents(".inner-repeater").find("button[data-repeater-create]")
                    let description_count = $(this).parents(".inner-repeater").find("div[data-repeater-item]").length;
                    if (description_count <= 1) {
                        $(this).slideDown();
                        desc_add_btn.hide()
                    } else {
                        $(this).remove();
                    }
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    let desc_add_btn = $(this).parents(".inner-repeater").find("button[data-repeater-create]")
                    $(this).slideUp(deleteElement);
                    desc_add_btn.show()
                }
            }],
            show: function () {
                $(this).slideDown();
                let desc_add_btn = $(this).parents(".inner-repeater").find("button[data-repeater-create]")
                let description_count = $(this).parents(".inner-repeater").find("div[data-repeater-item]").length;
                if (description_count <= 1) {
                    desc_add_btn.hide()
                }
                $(this).find('[data-kt-repeater="select2"]').select2()
                $(this).find('.quantity').val(1)
                $(this).find('[data-kt-product="product_select"]').select2({
                    minimumInputLength: 0,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    ajax: {
                        url: $(this).find('[data-kt-product="product_select"]').attr("data-kt-src"),
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
                                        text: item.barcode + ' | ' + item.name,
                                        id: item.id,
                                        cost: item.cost,
                                        price: item.price,
                                        tax_id: item.tax_id,
                                    }
                                })
                            }
                        }
                    }
                }).on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).children('[value="' + data['id'] + '"]').attr(
                        {
                            'data-kt-cost': data["cost"],
                            'data-kt-price': data["price"],
                            'data-kt-tax': data["tax_id"],
                        }
                    )
                });

                handleRowQuotient()
                handleSelectProduct()
                handleTaxChange()
            },
            hide: function (setIndexes) {
                setIndexes();
                handleRowQuotient()
                sb_total = handleSubtotal()
                handleTaxChange()
                handleTotal()
            },
            ready: function () {
                // Init select2
                $('[data-kt-repeater="select2"]').select2();
            }
        });
    }

    return {
        init: function () {
            handleInvoice()
            refGen($('[name="reference"]').attr('data-kt-src'))
            handleCustomerAPISelect('#kt_invoice_form', null, ['#customer_details'])
            handleCustomerSelect()
            initializeRepeater()
            handleRowQuotient()
            handleSubtotal()
            addFxField()
            addBankField()
            handleProductsAPISelect('#kt_invoice_form')
            handleSelectProduct()
            handleTaxTotal()
            handleTaxChange()
            handleHomeCurrencyTotal()
            handleDiscount()
            handleSubmit()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTInvoiceAdd.init();
});
