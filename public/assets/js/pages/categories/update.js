"use strict";

// Class definition
const KTCategoriesUpdate = function () {
    // Shared variables
    let closeButton, discardButton, submitButton, form, modal;


    // Public methods
    return {
        init: function () {
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_category'));
            form = document.querySelector('#kt_modal_update_category_form');
            submitButton = form.querySelector('#kt_modal_update_category_submit');
            discardButton = form.querySelector('#kt_modal_update_category_cancel');
            closeButton = form.querySelector('#kt_modal_update_category_close');

            handleFormSubmit(
                form,
                {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Category name is required'
                            }
                        }
                    },
                    default_tax_id: {
                        validators: {
                            notEmpty: {
                                message: 'Default tax is required'
                            }
                        }
                    }
                },
                $('#kt_modal_update_category_form'),
                discardButton,
                closeButton,
                submitButton,
                'PUT',
                modal,
                $('#kt_categories_table'),
                ["default_tax_id"],
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCategoriesUpdate.init();
});
