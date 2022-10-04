"use strict";

// Class definition
const KTPayTermsAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, validator, form, modal;

    //revalidate all select boxes
    $(document.querySelector(`[name="type"]`)).on('change', function () {
        // Revalidate the field when an option is chosen
        let label = $(this).val() == 1 ? 'Number of days' : 'Day in the next month';

        if ($(this).val() == 1 || $(this).val() == 2) {
            $('#days').removeClass('d-none')
            $('#day_label').html(label)
            $('input[name="days"]').attr('placeholder', label).attr('disabled', false)
        } else {
            $('#days').addClass('d-none')
            $('#day_label').html(label)
            $('input[name="days"]').attr('placeholder', label).attr('disabled', 'disabled')
        }
    });

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_pay_terms'));
            form = document.querySelector('#kt_modal_add_pay_terms_form');
            submitButton = form.querySelector('#kt_modal_add_pay_terms_submit');
            discardButton = form.querySelector('#kt_modal_add_pay_terms_cancel');
            closeButton = form.querySelector('#kt_modal_add_pay_terms_close');

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
                $('#kt_modal_add_pay_terms_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                null,
                ['type'],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPayTermsAdd.init();
});
