"use strict";

// Class definition
const KTPayTermsUpdate = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_pay_terms'));
            form = document.querySelector('#kt_modal_update_pay_terms_form');
            submitButton = form.querySelector('#kt_modal_update_pay_terms_submit');
            discardButton = form.querySelector('#kt_modal_update_pay_terms_cancel');
            closeButton = form.querySelector('#kt_modal_update_pay_terms_close');

            handleFormSubmit(
                form,
                {
                    type: {
                        validators: {
                            notEmpty: {
                                message: 'Payment Type is required'
                            }
                        }
                    },
                    terms: {
                        validators: {
                            notEmpty: {
                                message: 'Payment Terms is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_pay_terms_form'),
                discardButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                null,
                ['type'],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPayTermsUpdate.init();
});
