"use strict";

// Class definition
const KTBankAccountsServerSide = function () {
// Shared variables
    let table, dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_accounts_table')
        dt = $("#kt_accounts_table").DataTable();
    };

    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-accounts-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-gl-accounts-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_gl_account');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_gl_account_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_gl_account").modal('show');//show modal
                // Select parent row
                const parent = e.target.closest('tr');

                // Get rule name
                const update_url = parent.querySelector("input[class='update_url']").value;
                const edit_url = parent.querySelector("input[class='edit_url']").value;
                form.setAttribute("data-kt-action", update_url);

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

                            $('#kt_modal_update_gl_account_form').show({backdrop: 'static', keyboard: false});//show form
                            const gl_account = response.data;
                            //
                            $("#kt_modal_update_gl_account_form input[name='account_code']").val(gl_account.account_code);
                            $("#kt_modal_update_gl_account_form input[name='account_name']").val(gl_account.account_name);
                            $("#kt_modal_update_gl_account_form select[name='account_group']").val(gl_account.account_group).trigger('change');
                            $("#kt_modal_update_gl_account_form input[name='inactive']").val(gl_account.inactive)

                            if (gl_account.inactive !== 1) {
                                $("#kt_modal_update_gl_account_form input[id='inactive']").attr("checked", "checked");
                            } else {
                                $("#kt_modal_update_gl_account_form input[id='inactive']").removeAttr("checked")
                            }

                            //active/inactive
                            $("#kt_modal_update_gl_account_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked'))
                                    $("#kt_modal_update_gl_account_form input[name='inactive']").val(0)
                                else {
                                    $("#kt_modal_update_gl_account_form input[name='inactive']").val(1)
                                }
                            })
                        }

                        $('.loader_container').hide();//hide loader

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


            })
        });

    };

    //Delete Button
    const handleDeleteRows = function () {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-gl-accounts-table-actions="delete_row"]');

        deleteButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get rule name
                const className = parent.querySelectorAll('td')[1].innerText;
                delete_url = parent.querySelector("input[class='delete_url']").value;
                Swal.fire({
                    text: "Are you sure you want to delete " + className,
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
                            text: "Deleting " + className,
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        handleDelete('')

                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: className + " was not deleted.",
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

    };

    function handleDelete(remarks) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            url: delete_url,
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
                        dt.draw();
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
                            handleDelete(result.value)
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

    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_account_form');

            if ($('#kt_accounts_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
                handleUpdateRows();
                // handleDeleteRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBankAccountsServerSide.init();
});
