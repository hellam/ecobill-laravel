"use strict";

// Class definition
const KTReceivePayment = function () {
    let form = $('#kt_receive_pay_form')

    function handleReceivePayment() {
        $('[name="date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            locale: {
                format: '' + form.attr('data-kt-date-format') + ''
            },
            autoApply: true,
            maxYear: parseInt(moment().format('YYYY'), 10),
            maxDate: new Date()
        }, function (start, end, label) {
        });
    }

    return {
        init: function () {
            refGen($('[name="reference"]').attr('data-kt-src'))
            handleCustomerAPISelect('#kt_receive_pay_form', null)
            handleCustomerSelect()
            handleShareAmount()
            handleReceivePayment()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTReceivePayment.init();
});
