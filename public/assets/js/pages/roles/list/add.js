"use strict";
var KTUsersAddRole = function () {
    const t = document.getElementById("kt_modal_add_role"), e = t.querySelector("#kt_modal_add_role_form"),
        n = new bootstrap.Modal(t);
    return {
        init: function () {
            (() => {
                var o = FormValidation.formValidation(e, {
                    fields: {role_name: {validators: {notEmpty: {message: "Role name is required"}}}},
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                });
                t.querySelector('[data-kt-roles-modal-action="close"]').addEventListener("click", (t => {
                    t.preventDefault(), Swal.fire({
                        text: "Are you sure you would like to close?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, close it!",
                        cancelButtonText: "No, return",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (t) {
                        t.value && n.hide()
                    }))
                })), t.querySelector('[data-kt-roles-modal-action="cancel"]').addEventListener("click", (t => {
                    t.preventDefault(), Swal.fire({
                        text: "Are you sure you would like to cancel?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, return",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (t) {
                        t.value ? (e.reset(), n.hide()) : "cancel" === t.dismiss && Swal.fire({
                            text: "Your form has not been cancelled!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }));
                const r = t.querySelector('[data-kt-roles-modal-action="submit"]');
                r.addEventListener("click", (function (t) {
                    t.preventDefault(), o && o.validate().then((function (t) {
                        console.log("validated!"), "Valid" == t ? (r.setAttribute("data-kt-indicator", "on"), r.disabled = !0, setTimeout((function () {
                            r.removeAttribute("data-kt-indicator"), r.disabled = !1, Swal.fire({
                                text: "Form has been successfully submitted!",
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {confirmButton: "btn btn-primary"}
                            }).then((function (t) {
                                t.isConfirmed && n.hide()
                            }))
                        }), 2e3)) : Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }))
            })(), (() => {
                const t = e.querySelector("#kt_roles_select_all"), n = e.querySelectorAll('[type="checkbox"]');
                t.addEventListener("change", (t => {
                    n.forEach((e => {
                        e.checked = t.target.checked
                    }))
                }))
            })()
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTUsersAddRole.init()
}));

"use strict";

// Class definition
var KTUsersAddRole = function () {
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
                    role_name: {
                        validators: {
                            notEmpty: {
                                message: "Role name is required"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
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

                        var str = $('#kt_modal_add_role_form').serializeArray();
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
                                            if ($('#kt_taxes_table').length) {
                                                $("#kt_taxes_table").DataTable().ajax.reload();
                                                return;
                                            }
                                            // Redirect to Taxes list page
                                            window.location = form.getAttribute("data-kt-redirect");
                                        }
                                    });
                                }
                                submitButton.removeAttribute('data-kt-indicator');

                                // Enable submit button after loading
                                submitButton.disabled = false;

                            },
                            error: function (xhr, desc, err) {
                                console.log(xhr)
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
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_role_modal'));

            form = document.querySelector('#kt_modal_add_role_form');
            submitButton = form.querySelector('#kt_modal_add_role_submit');
            cancelButton = form.querySelector('#kt_modal_add_role_cancel');
            closeButton = form.querySelector('#kt_modal_add_role_close');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddRole.init();
});
