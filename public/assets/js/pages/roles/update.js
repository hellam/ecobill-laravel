"use strict";

var KTUsersUpdatePermissions = function () {
    var updateButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;
    var editButton;
    var deleteButton;
    var updateURL;

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


        editButton.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_role_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_role").modal('show');//show modal

                let edit_url = d.getAttribute('data-kt-edit-url');
                updateURL = d.getAttribute('data-kt-update-url');

                $.ajax({
                    type: 'GET',
                    url: edit_url,
                    success: function (json) {
                        var response = JSON.parse(JSON.stringify(json));
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
                            $('#kt_modal_update_role_form').show({backdrop: 'static', keyboard: false});//hide form
                            var data = JSON.parse(JSON.stringify(response.data));
                            $("#kt_modal_update_role_form input[name='name']").val(data.role.name);
                            // console.log(data)
                            let table_update = document.getElementById('table_update');
                            let html = '';
                            let group_name = ''
                            let counter = 0
                            data.permissions.forEach(permission => {
                                counter += 1
                                if (group_name !== permission.group_name)
                                    html += `
                                                    <!--begin::Permission-->
                                                    <tr>
                                                        <td class="text-gray-800">
                                                            <label
                                                                class="fs-5 fw-bolder form-label mb-2">${permission.group_name}</label>`;


                                group_name = permission.group_name
                                if (group_name !== permission.group_name && counter > 1)
                                    html += `</td>
                                                    </tr>
                                                    <!--end::Permission-->`;
                                html += `
                                                            <!--begin::Checkbox-->
                                                                <label
                                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mx-6">
                                                                    <input class="form-check-input" type="checkbox" ${permission.checked ? "checked" : ""}
                                                                           value="${permission.code}"
                                                                           name="permissions[]"/>
                                                                    <span
                                                                        class="form-check-label">${permission.name}</span>
                                                                </label>
                                                            <!--end::Checkbox-->
                                                        `;
                            })

                            table_update.innerHTML = html;
                        }

                        $('.loader_container').hide();//hide loader

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

                        updateButton.removeAttribute('data-kt-indicator');

                        // Enable submit button after loading
                        updateButton.disabled = false;
                        $("#kt_modal_update_role").modal('hide');//show modal
                        $('.loader_container').hide();//hide loader
                    }
                });
            });
        });

        // Action buttons
        updateButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        updateButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        updateButton.disabled = true;

                        var str = $('#kt_modal_update_role_form').serialize();
                        submitUpdate(str)
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

        deleteButton.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const roleName = $(this).parent().parent().find('.card-header h2').text();
                let deleteURL = d.getAttribute('data-kt-delete-url');

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + roleName + "? This is not reversible!",
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
                        submitData(deleteURL, roleName)
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: roleName + " was not deleted.",
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

        function submitData(deleteURL, roleName) {
            Swal.fire({
                text: "Deleting " + roleName,
                icon: "info",
                allowOutsideClick: false,
                buttonsStyling: false,
                showConfirmButton: false,
            })
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: deleteURL,
                success: function (json) {
                    var response = JSON.parse(JSON.stringify(json));
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
                            window.location.reload();
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
                                //data.add('remarks', result.value);
                                // alert(result.value)
                                submitData(deleteURL + "?remarks=" + result.value, roleName)
                            }
                        });
                    }
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
                }
            });
        }

        function submitUpdate(str) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'PUT',
                url: updateURL,
                data: str,
                success: function (json) {
                    var response = JSON.parse(JSON.stringify(json));
                    if (response.status !== true) {
                        var errors = response.data;
                        for (const [key, value] of Object.entries(errors)) {
                            // console.log(value)
                            $('#err_' + value.field).remove();
                            if ("input[name='" + value.field + "']") {
                                $("input[name='" + value.field + "']")
                                    .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                    .on('keyup', function (e) {
                                        $('#err_' + value.field).remove();
                                    })
                            }
                            if (value.field === 'permissions') {
                                $('#permissions_update').after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
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
                                modal.hide();

                                // Enable submit button after loading
                                updateButton.disabled = false;

                                window.location.reload();
                            }
                        });
                    }
                    updateButton.removeAttribute('data-kt-indicator');

                    // Enable submit button after loading
                    updateButton.disabled = false;

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
                            showLoaderOnConfirm: true,
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary"
                            }
                        }).then(function (result) {
                            // delete row data from server and re-draw datatable
                            if (result.isConfirmed) {
                                modal.show()//show modal
                                str = str + "&remarks=" + result.value
                                submitUpdate(str)
                            } else {
                                form.reset(); // Reset form
                            }
                        });
                    }
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

                    updateButton.removeAttribute('data-kt-indicator');

                    // Enable submit button after loading
                    updateButton.disabled = false;

                }
            });
        }
    }

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_role'));

            form = document.querySelector('#kt_modal_update_role_form');
            editButton = document.querySelectorAll('[data-kt-role-edit="kt_modal_edit_role_btn"]');
            deleteButton = document.querySelectorAll('[data-kt-role-delete="kt_modal_delete_role_btn"]');
            updateButton = form.querySelector('#kt_modal_update_role_submit');
            cancelButton = form.querySelector('#kt_modal_update_role_cancel');
            closeButton = document.querySelector('#kt_modal_update_role_close');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePermissions.init();
});
