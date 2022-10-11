"use strict";

// Class definition
const KTInvoiceAdd = function () {
    let form = $('#kt_invoice_form');

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


    }

    return {
        init: function () {
            handleInvoice()
            refGen($('[name="reference"]').attr('data-kt-src'))
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTInvoiceAdd.init();
});
