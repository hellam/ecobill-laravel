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
                            $("#kt_modal_update_fx_form input[name='sell_rate']").val(fx.sell_rate);
                            $("#kt_modal_update_fx_form input[name='date']").val(fx.date);
                            $("#kt_modal_update_fx_form select[name='currency']").val(fx.currency).trigger('change');

                            $("#update_date").val(moment(fx.date).format(''+$("#kt_date_from").attr("data-kt-date-format")+' H:mm:ss'))

                            $('#default_update_conversion').html(fx.sell_rate + " " + $('#sell_update_rate').attr('data-kt-default') + " = 1 " +  fx.currency)

                            $("#kt_update_date_from").daterangepicker({
                                    singleDatePicker: true,
                                    timePicker: true,
                                    drops: 'up',
                                    startDate: moment(fx.date),
                                    showDropdowns: true,
                                    maxYear: parseInt(moment().format("YYYY"), 10),
                                    timePicker24Hour: true,
                                    locale: {
                                        format: ''+$("#kt_date_from").attr("data-kt-date-format")+' H:mm:ss',
                                    },
                                }, function (start, end, label) {
                                    $("#update_date").val(start.format(''+$("#kt_date_from").attr("data-kt-date-format")+' H:mm:ss'))
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

    let sell_rate = $('#sell_update_rate'),
        conversion_text = $('#default_update_conversion'),
        default_currency = sell_rate.attr('data-kt-default'),
        select_currency = $('#currency_update'),
        to_currency,
        sell_rate_val = sell_rate.val();

    select_currency.on('change', function (e) {
        to_currency = e.target.value
        if (sell_rate_val === "" || sell_rate_val == 0) {
            conversion_text.html("1 " + default_currency + " = 1 " + to_currency)
        } else {
            conversion_text.html(sell_rate_val + " " + default_currency + " = 1 " + to_currency)
        }
    })

    sell_rate.on('keyup', function (e) {
        sell_rate_val = e.target.value
        if (select_currency.val() !== "")
            if (sell_rate_val === "" || sell_rate_val == 0) {
                conversion_text.html("1 " + default_currency + " = 1 " + to_currency)
            } else {
                conversion_text.html(sell_rate_val + " " + default_currency + " = 1 " + to_currency)
            }
    })


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
