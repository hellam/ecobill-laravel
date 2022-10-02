"use strict";

// Class definition
const KTCurrencyServerSide = function () {
// Shared variables
    let dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_currency_table')
        dt = $("#kt_currency_table").DataTable();
    };

    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-currency-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-currency-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_account');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_account_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_account").modal('show');//show modal

                const update_url = d.getAttribute('data-kt-currency-update-url');
                const edit_url = d.getAttribute('data-kt-currency-edit-url');
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

                            $('#kt_modal_update_account_form').show({backdrop: 'static', keyboard: false});//show form
                            const account = response.data;
                            //
                            $("#kt_modal_update_account_form input[name='account_name']").val(account.account_name);
                            $("#kt_modal_update_account_form input[name='account_number']").val(account.account_number);
                            $("#kt_modal_update_account_form input[name='entity_name']").val(account.entity_name);
                            $("#kt_modal_update_account_form input[name='entity_address']").val(account.entity_address);
                            $("#kt_modal_update_account_form input[name='currency']").val(account.currency);
                            $("#kt_modal_update_account_form input[name='chart_code']").val(account.chart_account.account_code+' - '+account.chart_account.account_name);
                            $("#kt_modal_update_account_form select[name='charge_chart_code']").val(account.charge_chart_code).trigger('change');
                            $("#kt_modal_update_account_form input[name='branch_id']").val(account.branch.name);
                            $("#kt_modal_update_account_form input[name='inactive']").val(account.inactive)

                            if (account.inactive !== 1) {
                                $("#kt_modal_update_account_form input[id='inactive']").attr("checked", "checked");
                            } else {
                                $("#kt_modal_update_account_form input[id='inactive']").removeAttr("checked")
                            }

                            //active/inactive
                            $("#kt_modal_update_account_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked'))
                                    $("#kt_modal_update_account_form input[name='inactive']").val(0)
                                else {
                                    $("#kt_modal_update_account_form input[name='inactive']").val(1)
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
        const deleteButtons = document.querySelectorAll('[data-kt-currency-table-actions="delete_row"]');

        deleteButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get rule name
                const accountName = parent.querySelectorAll('td')[1].innerText;
                delete_url = d.getAttribute('data-kt-currency-delete-url');
                Swal.fire({
                    text: "Are you sure you want to delete " + accountName,
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
                            text: "Deleting " + accountName,
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        handleDelete('')

                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: accountName + " was not deleted.",
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
                        window.location = form.getAttribute("data-kt-redirect");
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

            if ($('#kt_currency_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
                // handleUpdateRows();
                // handleDeleteRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCurrencyServerSide.init();
});
