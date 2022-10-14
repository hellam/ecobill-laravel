"use strict";

// Class definition
const KTInvoiceAdd = function () {
    let form = $('#kt_invoice_form'),
        pay_terms = $('[name="pay_terms"]'),
        customer_select = $('[name="customer"]'),
        sb_total = 0;

    function handleInvoice() {
        $('[name="invoice_due_date"], [name="invoice_date"]').daterangepicker({
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
                invoice_due_date = $('[name="invoice_due_date"]'),
                date_format = '' + pay_terms.attr('data-kt-date-format') + ''

            if (type == 1) {
                // type 1 number of days
                if (invoice_date.val()) {
                    invoice_due_date.val(moment(invoice_date.val(), date_format).add(no_days, 'days').format(date_format))
                    invoice_date.on('change', function () {
                        invoice_due_date.val(moment(invoice_date.val(), date_format).add(no_days, 'days').format(date_format))
                    })
                } else {
                    invoice_due_date.val(moment().format(date_format))
                }
            } else if (type == 2) {
                // type 2 day in the following month
                if (invoice_date.val()) {
                    invoice_due_date.val(moment(invoice_date.val(), date_format).endOf('month').add(no_days, 'days').format(date_format))
                } else {
                    invoice_due_date.val(moment().format(date_format))
                }
            } else {
                if (invoice_date.val()) {
                    invoice_due_date.val(invoice_date.val())
                } else {
                    invoice_due_date.val(moment().format(date_format))
                }
            }
        })

        $('[name="invoice_date"]').on('change', function () {
            if (!pay_terms.val()) {
                $('[name="invoice_due_date"]').val($('[name="invoice_date"]').val())
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
                handleRowQuotient()
                let desc_add_btn = $(this).parents(".inner-repeater").find("button[data-repeater-create]")
                let description_count = $(this).parents(".inner-repeater").find("div[data-repeater-item]").length;
                if (description_count <= 1) {
                    desc_add_btn.hide()
                }
            },
            hide: function (setIndexes) {
                setIndexes();
                handleRowQuotient()
                sb_total = handleSubtotal()
            },
        });
    }

    function handleRowQuotient() {
        $('.amount').each(function () {
            $(this).keyup(function () {
                let quotient = 0;
                let quantity = $(this).closest('tr').find('.quantity').val()
                if (!isNaN(this.value) && this.value.length !== 0 && quantity.length !== 0 && !isNaN(quantity)) {
                    quotient = parseFloat(this.value) * parseFloat(quantity);
                }
                let row_total = $(this).closest('tr').find('.total')
                row_total.html(new Intl.NumberFormat('ja-JP', {style: 'currency', currency: "USD"}).format(quotient))
                sb_total = handleSubtotal()
            });
        });

        $('.quantity').each(function () {
            $(this).keyup(function () {
                let quotient = 0;
                let amount = $(this).closest('tr').find('.amount').val()
                if (!isNaN(this.value) && this.value.length !== 0 && amount.length !== 0 && !isNaN(amount)) {
                    quotient = parseFloat(this.value) * parseFloat(amount);
                }
                let row_total = $(this).closest('tr').find('.total')
                row_total.html(new Intl.NumberFormat('ja-JP', {style: 'currency', currency: "USD"}).format(quotient))
                sb_total = handleSubtotal()
            });
        });
    }

    function handleSubtotal() {
        let sum = 0
        $('tr').each(function () {
            let quantity = $(this).find('.quantity').val()
            let amount = $(this).find('.amount').val()
            if (!isNaN(amount) && amount.length !== 0 && quantity.length !== 0 && !isNaN(quantity)) {
                sum += parseFloat(amount) * parseFloat(quantity);
            }
        })
        $('#sub-total').html(new Intl.NumberFormat('ja-JP', {style: 'currency', currency: "USD"}).format(sum))
        return sum
    }

    return {
        init: function () {
            handleInvoice()
            refGen($('[name="reference"]').attr('data-kt-src'))
            handleCustomerAPISelect('#kt_invoice_form', null, ['#customer_details'])
            initializeRepeater()
            handleRowQuotient()
            handleSubtotal()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTInvoiceAdd.init();
});
