"use strict";

// Class definition
const KTGLClassesAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_gl_class'));
            form = document.querySelector('#kt_modal_add_gl_class_form');
            submitButton = form.querySelector('#kt_modal_add_gl_class_submit');
            discardButton = form.querySelector('#kt_modal_add_gl_class_cancel');
            closeButton = form.querySelector('#kt_modal_add_gl_class_close');

            handleFormSubmit(
                form,
                {
                    class_name: {
                        validators: {
                            notEmpty: {
                                message: 'Class name is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_gl_class_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_gl_classes_table'),
                null,
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLClassesAdd.init();
});
