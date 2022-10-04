"use strict";

// Class definition
const KTCurrencyUpdate = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, validator, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_currency'));
            form = document.querySelector('#kt_modal_update_currency_form');
            submitButton = form.querySelector('#kt_modal_update_currency_submit');
            discardButton = form.querySelector('#kt_modal_update_currency_cancel');
            closeButton = form.querySelector('#kt_modal_update_currency_close');

            handleFormSubmit(
                form,
                {
                    abbreviation: {
                        validators: {
                            notEmpty: {
                                message: 'Abbreviation is required'
                            }
                        }
                    },
                    symbol: {
                        validators: {
                            notEmpty: {
                                message: 'Symbol is required'
                            }
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Currency name is required'
                            }
                        }
                    },
                    hundredths_name: {
                        validators: {
                            notEmpty: {
                                message: 'Hundredths name is required'
                            }
                        }
                    },
                    country: {
                        validators: {
                            notEmpty: {
                                message: 'Country is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_currency_form'),
                discardButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                null,
                ["country"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCurrencyUpdate.init();
});
