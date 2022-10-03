"use strict";

// Class definition
const KTPayTermsServerSide = function () {
// Shared variables
    let dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        dt = $("#kt_pay_terms_table").DataTable();
    };

    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-pay-terms-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-pay-terms-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_pay_terms');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_pay_terms_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_pay_terms").modal('show');//show modal

                const update_url = d.getAttribute('data-kt-pay-terms-update-url');
                const edit_url = d.getAttribute('data-kt-pay-terms-edit-url');
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

                            $('#kt_modal_update_pay_terms_form').show({backdrop: 'static', keyboard: false});//show form
                            const pay_terms = response.data;
                            //
                            $("#kt_modal_update_pay_terms_form input[name='terms']").val(pay_terms.terms);
                            $("#kt_modal_update_pay_terms_form select[name='type']").val(pay_terms.type).trigger('change');
                            $("#kt_modal_update_pay_terms_form input[name='days']").val(pay_terms.days);

                            let label = pay_terms.type == 1 ? 'Number of days' : 'Day in the next month';
                            if (pay_terms.type !== 0) {
                                $('#update_days').removeClass('d-none')
                                $(form.querySelector('[name="days"]')).attr('placeholder', label).attr('disabled', false)
                                $('#update_day_label').html(label)
                            }
                            //revalidate all select boxes
                            $(form.querySelector(`[name="type"]`)).on('change', function () {
                                // Revalidate the field when an option is chosen
                                let label = $(this).val() == 1 ? 'Number of days' : 'Day in the next month';
                                if ($(this).val() == 1 || $(this).val() == 2) {
                                    $('#days').removeClass('d-none')
                                    $('#update_day_label').html(label)
                                    $('input[name="days"]').attr('placeholder', label).attr('disabled', false)
                                } else {
                                    $('#days').addClass('d-none')
                                    $('#day_label').html(label)
                                    $('input[name="days"]').attr('placeholder', label).attr('disabled', 'disabled')
                                }
                            });
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
        const deleteButtons = document.querySelectorAll('[data-kt-pay-terms-table-actions="delete_row"]');

        deleteButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get rule name
                const payTermsName = parent.querySelectorAll('td')[1].innerText;
                delete_url = d.getAttribute('data-kt-pay-terms-delete-url');
                Swal.fire({
                    text: "Are you sure you want to delete " + payTermsName,
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
                            text: "Deleting " + payTermsName,
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        handleDelete('')

                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: payTermsName + " was not deleted.",
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
            form = document.querySelector('#kt_modal_update_pay_terms_form');

            if ($('#kt_pay_terms_table').length) {
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
    KTPayTermsServerSide.init();
});
