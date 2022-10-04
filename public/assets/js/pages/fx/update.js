"use strict";

// Class definition
const KTFXUpdate = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_fx'));
            form = document.querySelector('#kt_modal_update_fx_form');
            submitButton = form.querySelector('#kt_modal_update_fx_submit');
            discardButton = form.querySelector('#kt_modal_update_fx_cancel');
            closeButton = form.querySelector('#kt_modal_update_fx_close');

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
                $('#kt_modal_update_fx_form'),
                discardButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                null,
                ["currency"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFXUpdate.init();
});
