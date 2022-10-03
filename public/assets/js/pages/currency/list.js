"use strict";

// Class definition
const KTCurrencyServerSide = function () {
// Shared variables
    let dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        dt = $("#kt_currency_table").DataTable();
    };

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-currency-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_currency');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_currency_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_currency").modal('show');//show modal

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

                            $('#kt_modal_update_currency_form').show({backdrop: 'static', keyboard: false});//show form
                            const currency = response.data;
                            //
                            $("#kt_modal_update_currency_form input[name='abbreviation']").val(currency.abbreviation).attr('disabled', true);
                            $("#kt_modal_update_currency_form input[name='symbol']").val(currency.symbol);
                            $("#kt_modal_update_currency_form input[name='name']").val(currency.name);
                            $("#kt_modal_update_currency_form input[name='hundredths_name']").val(currency.hundredths_name);
                            $("#kt_modal_update_currency_form select[name='country']").val(currency.country).trigger('change');

                            $("#kt_modal_update_currency_form input[name='auto_fx']").val(currency.auto_fx)

                            if (currency.auto_fx === 1) {
                                $("#kt_modal_update_currency_form input[id='check_auto_fx']").attr("checked", "checked");
                            }

                            //
                            $(form.querySelector(`[id="check_auto_fx"]`)).on('change', function (){
                                if(this.checked) {
                                    $('[name="auto_fx"]').val(1)
                                }else{
                                    $('[name="auto_fx"]').val(0)
                                }
                            })

                            //active/inactive
                            $("#kt_modal_update_currency_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked'))
                                    $("#kt_modal_update_currency_form input[name='inactive']").val(0)
                                else {
                                    $("#kt_modal_update_currency_form input[name='inactive']").val(1)
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

    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_currency_form');

            if ($('#kt_currency_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable('[data-kt-currency-table-filter="search"]', dt);
                handleUpdateRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCurrencyServerSide.init();
});
