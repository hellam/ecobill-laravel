"use strict";

// Class definition
const KTInvoiceAdd = function () {
    let form = $('#kt_invoice_form'), pay_terms = $('[name="pay_terms"]'), customer_select = $('[name="customer"]');

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

    }

    function initializeRepeater() {
        let repeater = $('table.repeater_items').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp();
            }
        });
    }

    return {
        init: function () {
            handleInvoice()
            refGen($('[name="reference"]').attr('data-kt-src'))
            handleCustomerAPISelect('#kt_invoice_form', null, ['#customer_details'])
            initializeRepeater()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTInvoiceAdd.init();
});
