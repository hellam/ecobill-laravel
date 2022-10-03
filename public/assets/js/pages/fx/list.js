"use strict";

// Class definition
const KTFXServerSide = function () {
// Shared variables
    let dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        dt = $("#kt_fx_table").DataTable();
    };

    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-fx-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-fx-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_fx');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_fx_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_fx").modal('show');//show modal

                const update_url = d.getAttribute('data-kt-fx-update-url');
                const edit_url = d.getAttribute('data-kt-fx-edit-url');
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

                            $('#kt_modal_update_fx_form').show({backdrop: 'static', keyboard: false});//show form
                            const fx = response.data;
                            //
                            $("#kt_modal_update_fx_form input[name='buy_rate']").val(fx.buy_rate);
                            $("#kt_modal_update_fx_form input[name='sell_rate']").val(fx.sell_rate);
                            $("#kt_modal_update_fx_form input[name='date']").val(fx.date);
                            $("#kt_modal_update_fx_form select[name='currency']").val(fx.currency).trigger('change');

                            $("#update_date").val(moment(fx.date).format('DD/MM/YYYY H:mm:ss'))

                            $("#kt_update_date_from").daterangepicker({
                                    singleDatePicker: true,
                                    timePicker: true,
                                    drops: 'up',
                                    startDate: moment(fx.date),
                                    showDropdowns: true,
                                    maxYear: parseInt(moment().format("YYYY"), 10),
                                    timePicker24Hour: true,
                                    locale: {
                                        format: 'DD/MM/YYYY HH:mm',
                                    },
                                }, function (start, end, label) {
                                    $("#update_date").val(start.format('DD/MM/YYYY H:mm:ss'))
                                }
                            );
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
        const deleteButtons = document.querySelectorAll('[data-kt-fx-table-actions="delete_row"]');

        deleteButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get rule name
                const fxName = parent.querySelectorAll('td')[1].innerText;
                delete_url = d.getAttribute('data-kt-fx-delete-url');
                Swal.fire({
                    text: "Are you sure you want to delete " + fxName,
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
                            text: "Deleting " + fxName,
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        handleDelete('')

                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: fxName + " was not deleted.",
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
            form = document.querySelector('#kt_modal_update_fx_form');

            if ($('#kt_fx_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
                handleUpdateRows();
                handleDeleteRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFXServerSide.init();
});
