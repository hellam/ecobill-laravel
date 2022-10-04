"use strict";

// Class definition
const KTGLClassesUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_gl_class'));

            form = document.querySelector('#kt_modal_update_gl_class_form');
            cancelButton = form.querySelector('#kt_modal_update_gl_class_cancel');
            submitButton = form.querySelector('#kt_modal_update_gl_class_submit');
            closeButton = document.querySelector('#kt_modal_update_gl_class_close');

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
                $('#kt_modal_update_gl_class_form'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_gl_classes_table'),
                null,
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLClassesUpdate.init();
});
