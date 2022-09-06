"use strict";

// Class definition
var KTModalUpdateProduct = function () {
    var element;
    var submitButton;
    var cancelButton;
    var closeButton;
    var form;
    var modal;

    // Init form inputs
    var initForm = function () {
        // Action buttons
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Show loading indication
            submitButton.setAttribute('data-kt-indicator', 'on');

            // Disable submit button whilst loading
            submitButton.disabled = true;
            const str = $('#kt_modal_update_product_form').serializeArray().reduce(function (a, x) {
                a[x.name] = x.value;
                return a;
            }, {});
            $.ajax({
                type: 'PUT',
                url: form.getAttribute("data-kt-action"),
                contentType: 'application/json',
                data: JSON.stringify(str),
                success: function (json) {
                    const response = JSON.parse(json);
                    if (response.status !== true) {
                        var errors = response.data;
                        for (const [key, value] of Object.entries(errors)) {
                            // console.log(errors)
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

                                // Reload datatable
                                $("#kt_products_table").DataTable().ajax.reload();
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
                    modal.hide(); // Hide modal
                }
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
                    modal.hide(); // Hide modal
                }
            });
        });
    }

    return {
        // Public functions
        init: function () {
            // Elements
            element = document.querySelector('#kt_modal_update_product');
            modal = new bootstrap.Modal(element);

            form = element.querySelector('#kt_modal_update_product_form');
            submitButton = form.querySelector('#kt_modal_update_product_submit');
            cancelButton = form.querySelector('#kt_modal_update_product_cancel');
            closeButton = element.querySelector('#kt_modal_update_product_close');

            initForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateProduct.init();
});
