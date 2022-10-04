"use strict";

// Class definition
const KTGLGroupsUpdate = function () {
    // Shared variables
    let submitButton, cancelButton, closeButton, form, modal;

    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_gl_group'));
            form = document.querySelector('#kt_modal_update_gl_group_form');
            cancelButton = form.querySelector('#kt_modal_update_gl_group_cancel');
            submitButton = form.querySelector('#kt_modal_update_gl_group_submit');
            closeButton = document.querySelector('#kt_modal_update_gl_group_close');

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
                $('#kt_modal_update_gl_group_cancel'),
                cancelButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_gl_groups_table'),
                ["class_id"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGLGroupsUpdate.init();
});
