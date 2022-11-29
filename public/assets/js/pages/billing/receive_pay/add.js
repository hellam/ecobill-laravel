"use strict";

// Class definition
const KTReceivePayment = function () {
    let form = $('#kt_invoice_form'),
        pay_terms = $('[name="pay_terms"]'),
        customer_select = $('[name="customer"]'),
        sb_total = 0;

    return {
        init: function () {
            refGen($('[name="reference"]').attr('data-kt-src'))
            handleCustomerAPISelect('#kt_receive_pay_form', null)
            handleCustomerSelect()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTReceivePayment.init();
});
