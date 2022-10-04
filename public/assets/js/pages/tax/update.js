"use strict";

// Class definition
const KTTaxUpdate = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_tax'));
            form = document.querySelector('#kt_modal_update_tax_form');
            submitButton = form.querySelector('#kt_modal_update_tax_submit');
            discardButton = form.querySelector('#kt_modal_update_tax_cancel');
            closeButton = form.querySelector('#kt_modal_update_tax_close');

            handleFormSubmit(
                form,
                {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Tax name is required'
                            }
                        }
                    },
                    rate: {
                        validators: {
                            notEmpty: {
                                message: 'Rate is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_tax_form'),
                discardButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                null,
                null,
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTTaxUpdate.init();
});
