"use strict";

// Class definition
const KTFXAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    $("#date").val(moment().format('DD/MM/YYYY H:mm:ss'))

    $("#kt_date_from").daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            drops: 'up',
            startDate: moment(),
            showDropdowns: true,
            maxYear: parseInt(moment().format("YYYY"), 10),
            timePicker24Hour: true,
            locale: {
                format: 'DD/MM/YYYY HH:mm',
            },
        }, function (start, end, label) {
            $("#date").val(start.format('DD/MM/YYYY H:mm:ss'))
        }
    );

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_fx'));
            form = document.querySelector('#kt_modal_add_fx_form');
            submitButton = form.querySelector('#kt_modal_add_fx_submit');
            discardButton = form.querySelector('#kt_modal_add_fx_cancel');
            closeButton = form.querySelector('#kt_modal_add_fx_close');

            handleFormSubmit(
                form,
                {
                    currency: {
                        validators: {
                            notEmpty: {
                                message: 'Currency is required'
                            }
                        }
                    },
                    buy_rate: {
                        validators: {
                            notEmpty: {
                                message: 'Buy rate is required'
                            }
                        }
                    },
                    sell_rate: {
                        validators: {
                            notEmpty: {
                                message: 'Sell rate is required'
                            }
                        }
                    },
                    date: {
                        validators: {
                            notEmpty: {
                                message: 'Date from is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_fx_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                null,
                ["currency"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFXAdd.init();
});
