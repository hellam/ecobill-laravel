function handleDeleteRows(deleteButtons, delete_url, dt = null) {
    deleteButtons = document.querySelectorAll(deleteButtons)
    deleteButtons.forEach(d => {
        // edit button on click
        d.addEventListener('click', function (e) {
            e.preventDefault();

            // Select parent row
            const parent = e.target.closest('tr');

            // Get rule name
            const name = parent.querySelectorAll('td')[1].innerText;
            let delete_uri = d.getAttribute(delete_url) ?? parent.querySelector(delete_url).value;
            Swal.fire({
                text: "Are you sure you want to delete " + name,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting " + name,
                        icon: "info",
                        allowOutsideClick: false,
                        buttonsStyling: false,
                        showConfirmButton: false,
                    })
                    handleDelete(delete_uri, '', dt)

                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: name + " was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        })
    });
}

function handleDelete(delete_uri, remarks, dt) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'DELETE',
        url: delete_uri,
        data: {
            remarks: remarks
        },
        success: function (json) {
            var response = json;
            if (response.status !== true) {
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
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                }).then(function () {
                    // delete row data from server and re-draw datatable
                    if (dt !== null)
                        dt.draw()
                    else
                        window.location.reload()
                });
            }
        },
        statusCode: {
            203: function () {
                Swal.fire({
                    text: "Please provide remarks",
                    icon: "info",
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    allowOutsideClick: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    // delete row data from server and re-draw datatable
                    if (result.isConfirmed) {
                        handleDelete(delete_uri, result.value, dt)
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                text: 'A network error occured. Please consult your network administrator.',
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

function handleSearchDatatable(input, dt) {
    const filterSearch = document.querySelector(input);
    filterSearch.addEventListener('keyup', function (e) {
        dt.search(e.target.value).draw();
    });
}

function handleFormSubmit(form, fields, form_jquery, cancelButton, closeButton, submitButton, method, modal = null, table = null, select_fields = null) {
    let validator = FormValidation.formValidation(
        form,
        {
            fields: fields,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                }),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            }
        }
    );

    if (select_fields !== null) {
        select_fields.forEach(select => {
            $(form.querySelector(`[name=${select}]`)).on('change', function () {
                // Revalidate the field when an option is chosen
                validator.revalidateField(`${select}`);
            });
        })
    }

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Validate form before submit
        if (validator) {
            validator.validate().then(function (status) {

                if (status === 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    let str = form_jquery.serialize();
                    submitFormData(str, form, modal, submitButton, table, select_fields, method);
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
            })
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
                if (select_fields !== null)
                    select_fields.forEach(select => {
                        $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                    })
                if (modal !== null)
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
                if (select_fields !== null)
                    select_fields.forEach(select => {
                        $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                    })
                if (modal !== null)
                    modal.hide(); // Hide modal
            }
        });
    });
}

function submitFormData(str, form, modal, submitButton, table, select_fields, method) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: method,
        url: form.getAttribute("data-kt-action"),
        data: str,
        success: function (json) {
            var response = JSON.parse(JSON.stringify(json));
            if (response.status !== true) {
                var errors = response.data;
                for (const [key, value] of Object.entries(errors)) {
                    // var field = fields[i];
                    // console.log(field.field);
                    $('#err_' + value.field).remove();
                    if ($("input[name='" + value.field + "']").length) {
                        $("input[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }
                    if ($("select[name='" + value.field + "']").length) {
                        $("select[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('change', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }
                    if ($("textarea[name='" + value.field + "']").length) {
                        $("textarea[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup', function (e) {
                                $('#err_' + value.field).remove();
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
                        if (modal !== null)
                            modal.hide();
                        // Enable submit button after loading
                        submitButton.disabled = false;
                        form.reset(); // Reset form
                        if (select_fields !== null)
                            select_fields.forEach(select => {
                                $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                            })

                        if (table !== null && table.length) {
                            table.DataTable().ajax.reload();
                            return;
                        }

                        window.location = form.getAttribute("data-kt-redirect");
                    }
                });
            }
            submitButton.removeAttribute('data-kt-indicator');

            // Enable submit button after loading
            submitButton.disabled = false;

        },
        statusCode: {
            203: function () {
                modal.hide()//hide modal
                Swal.fire({
                    text: "Please provide remarks",
                    icon: "info",
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    allowOutsideClick: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    // showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    // delete row data from server and re-draw datatable
                    if (result.isConfirmed) {
                        //data.add('remarks', result.value);
                        // alert(result.value)
                        if (modal !== null)
                            modal.show()//show modal
                        // console.log(str)
                        // if (result.value)
                        str = str + "&remarks=" + result.value
                        submitFormData(str, form, modal, submitButton, table, select_fields, method);
                    } else {
                        form.reset(); // Reset form
                        if (select_fields !== null)
                            select_fields.forEach(select => {
                                $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                            })
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                text: 'A network error occurred. Please consult your network administrator.',
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
}
