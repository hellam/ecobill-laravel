"use strict";

// Class definition
var KTModalProductsAdd = function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var generateBarcodeButton;
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
                    'barcode': {
                        validators: {
                            notEmpty: {
                                message: 'Barcode is required'
                            }
                        }
                    }, 'name': {
                        validators: {
                            notEmpty: {
                                message: 'Product name is required'
                            }
                        }
                    }, 'cost': {
                        validators: {
                            notEmpty: {
                                message: 'Product cost is required'
                            }
                        }
                    }, 'price': {
                        validators: {
                            notEmpty: {
                                message: 'Product price is required'
                            }
                        }
                    },
                    'categoryId': {
                        validators: {
                            notEmpty: {
                                message: 'Category is required'
                            }
                        }
                    },
                    'taxId': {
                        validators: {
                            notEmpty: {
                                message: 'Tax is required'
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

        // Revalidate category field. For more info, plase visit the official plugin site: https://select2.org/
        // $(form.querySelector('[name="category"]')).on('change', function () {
        //     // Revalidate the field when an option is chosen
        //     validator.revalidateField('category');
        // });

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

                        var str = $('#kt_modal_add_product_form').serializeArray().reduce(function (a, x) {
                            a[x.name] = x.value;
                            return a;
                        }, {});
                        $.ajax({
                            type: 'POST',
                            url: form.getAttribute("data-kt-action"),
                            contentType: 'application/json',
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
                                            $('#add_product-details').hide();//hide contact details form
                                            $("#select_product").val(null).trigger('change');

                                            if ($('#kt_products_table').length) {
                                                $("#kt_products_table").DataTable().ajax.reload();
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
                    $('#add_product-details').hide();//hide contact details form
                    $("#select_product").val(null).trigger('change');
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
                    $('#add_product-details').hide();//hide contact details form
                    $("#select_product").val(null).trigger('change');
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

        generateBarcodeButton.addEventListener('click', function (e) {
            e.preventDefault();
            $('#barcode').val(Math.floor(Math.random() * 100000000))
        })
    }

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_product'));

            form = document.querySelector('#kt_modal_add_product_form, #kt_modal_update_product_form');
            submitButton = form.querySelector('#kt_modal_add_product_submit');
            cancelButton = form.querySelector('#kt_modal_add_product_cancel');
            closeButton = form.querySelector('#kt_modal_add_product_close');
            generateBarcodeButton = form.querySelector('#kt_generate_product_barcode');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalProductsAdd.init();
});
