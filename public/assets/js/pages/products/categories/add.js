"use strict";

// Class definition
var KTModalGroupAdd = function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;

    // Init form inputs
    var handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Category name is required'
                            }
                        }
                    }, 'defaultTaxId': {
                        validators: {
                            notEmpty: {
                                message: 'Default tax is required'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Action buttons
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        var str = $('#kt_modal_add_category_form').serializeArray().reduce(function (a, x) {
                            a[x.name] = x.value;
                            return a;
                        }, {});
                        $.ajax({
                            type: 'POST',
                            contentType: 'application/json',
                            url: form.getAttribute("data-kt-action"),
                            data: JSON.stringify(str),
                            success: function (json) {
                                var response = JSON.parse(json);
                                if (response.status !== true) {
                                    var errors = response.data;
                                    for (const [key, value] of Object.entries(errors)) {
                                        // var field = fields[i];
                                        // console.log(field.field);
                                        $('#err_' + key).remove();
                                        if ($("input[name='" + key + "']").length) {
                                            $("input[name='" + key + "']")
                                                .after('<small style="color: red;" id="err_' + key + '">' + value + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_' + key).remove();
                                                })
                                        } else if ($("textarea[name='" + key + "']").length) {
                                            $("textarea[name='" + key + "']")
                                                .after('<small style="color: red;" id="err_' + key + '">' + value + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_' + key).remove();
                                                })
                                        }
                                    }

                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });

                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            // Hide modal
                                            modal.hide();

                                            // Enable submit button after loading
                                            submitButton.disabled = false;


                                            form.reset(); // Reset form
                                            $(".select_contact").val(null).trigger('change');

                                            if ($('#kt_categories_table').length) {
                                                $("#kt_categories_table").DataTable().ajax.reload();
                                                return;
                                            }
                                            // Redirect to customers list page
                                            window.location = form.getAttribute("data-kt-redirect");
                                        }
                                    });
                                }
                                submitButton.removeAttribute('data-kt-indicator');

                                // Enable submit button after loading
                                submitButton.disabled = false;

                            },
                            error: function (xhr, desc, err) {
                                Swal.fire({
                                    text: 'A network error occured. Please consult your network administrator.',
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });

                                submitButton.removeAttribute('data-kt-indicator');

                                // Enable submit button after loading
                                submitButton.disabled = false;

                            }
                        });
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    $("#select_contact").val(null).trigger('change');
                    modal.hide(); // Hide modal
                }
                // else if (result.dismiss === 'cancel') {
                //     Swal.fire({
                //         text: "Your form has not been cancelled!.",
                //         icon: "error",
                //         buttonsStyling: false,
                //         confirmButtonText: "Ok, got it!",
                //         customClass: {
                //             confirmButton: "btn btn-primary",
                //         }
                //     });
                // }
            });
        });

        closeButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    $("#select_contact").val(null).trigger('change');
                    modal.hide(); // Hide modal
                }
                // else if (result.dismiss === 'cancel') {
                //     Swal.fire({
                //         text: "Your form has not been cancelled!.",
                //         icon: "error",
                //         buttonsStyling: false,
                //         confirmButtonText: "Ok, got it!",
                //         customClass: {
                //             confirmButton: "btn btn-primary",
                //         }
                //     });
                // }
            });
        })
    }

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_category'));

            form = document.querySelector('#kt_modal_add_category_form');
            submitButton = form.querySelector('#kt_modal_add_category_submit');
            cancelButton = form.querySelector('#kt_modal_add_category_cancel');
            closeButton = form.querySelector('#kt_modal_add_category_close');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalGroupAdd.init();
});
