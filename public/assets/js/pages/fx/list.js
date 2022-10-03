"use strict";

// Class definition
const KTFXServerSide = function () {
// Shared variables
    let dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        dt = $("#kt_fx_table").DataTable();
    };

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

    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_fx_form');

            if ($('#kt_fx_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable('[data-kt-fx-table-filter="search"]', dt);
                handleUpdateRows();
                handleDeleteRows('[data-kt-fx-table-actions="delete_row"]', 'data-kt-fx-delete-url');
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFXServerSide.init();
});
