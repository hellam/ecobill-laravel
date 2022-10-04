"use strict";

// Class definition
const KTGLGroupsAdd = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;

    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_gl_group'));
            form = document.querySelector('#kt_modal_add_gl_group_form');
            submitButton = form.querySelector('#kt_modal_add_gl_group_submit');
            discardButton = form.querySelector('#kt_modal_add_gl_group_cancel');
            closeButton = form.querySelector('#kt_modal_add_gl_group_close');

            handleFormSubmit(
                form,
                {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Group name is required'
                            }
                        }
                    },
                    class_id: {
                        validators: {
                            notEmpty: {
                                message: 'Class is required'
                            }
                        }
                    }
                },
                $('#kt_modal_add_gl_group_form'),
                discardButton,
                closeButton,
                submitButton,
                'POST',
                modal,
                $('#kt_gl_groups_table'),
                ["class_id"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLGroupsAdd.init();
});
