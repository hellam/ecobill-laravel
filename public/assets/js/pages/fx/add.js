"use strict";

// Class definition
const KTFXAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal,
        time_zone = JSON.parse($('#kt_aside').attr('data-kt-branch-details'));

    $("#date").val(moment().tz('' + time_zone.timezone + '').format('' + $("#kt_date_from").attr("data-kt-date-format") + ' H:mm:ss'))

    $("#kt_date_from").daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            drops: 'up',
            startDate: moment().tz('' + time_zone.timezone + ''),
            showDropdowns: true,
            maxYear: parseInt(moment().format("YYYY"), 10),
            timePicker24Hour: true,
            locale: {
                format: '' + $("#kt_date_from").attr("data-kt-date-format") + ' HH:mm:ss',
            },
        }, function (start, end, label) {
        }
    );

    let sell_rate = $('#sell_rate'),
        conversion_text = $('#default_add_conversion'),
        default_currency = sell_rate.attr('data-kt-default'),
        select_currency = $('#add_currency'),
        to_currency,
        sell_rate_val = sell_rate.val();

    select_currency.on('change', function (e) {
        to_currency = e.target.value
        if (sell_rate_val === "" || sell_rate_val == 0) {
            conversion_text.html("1 " + default_currency + " = 1 " + to_currency)
        } else {
            conversion_text.html(sell_rate_val + " " + default_currency + " = 1 " + to_currency)
        }
    })

    sell_rate.on('keyup', function (e) {
        sell_rate_val = e.target.value
        if (select_currency.val() !== "")
            if (sell_rate_val === "" || sell_rate_val == 0) {
                conversion_text.html("1 " + default_currency + " = 1 " + to_currency)
            } else {
                conversion_text.html(sell_rate_val + " " + default_currency + " = 1 " + to_currency)
            }
    })

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_fx'));
            form = document.querySelector('#kt_modal_add_fx_form');
            submitButton = form.querySelector('#kt_modal_add_fx_submit');
            discardButton = form.querySelector('#kt_modal_add_fx_cancel');
            closeButton = form.querySelector('#kt_modal_add_fx_close');

            $('#kt_modal_add_fx').on('hidden.bs.modal', function () {
                sell_rate_val = 0
                $('#default_add_conversion').text('')
            });

            $('#kt_modal_add_fx').on('hidden.bs.modal', function () {
                if (select_currency.val() !== "")
                    if (sell_rate_val === "" || sell_rate_val == 0) {
                        conversion_text.html("1 " + default_currency + " = 1 " + to_currency)
                    } else {
                        conversion_text.html(sell_rate_val + " " + default_currency + " = 1 " + to_currency)
                    }
            });

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
