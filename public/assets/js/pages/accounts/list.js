"use strict";

// Class definition
const KTBankAccountsServerSide = function () {
// Shared variables
    let dt, form;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_accounts_table')
        dt = $("#kt_accounts_table").DataTable();
    };

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-accounts-table-actions="edit_row"]');

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

                const update_url = d.getAttribute('data-kt-accounts-update-url');
                const edit_url = d.getAttribute('data-kt-accounts-edit-url');
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


    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_account_form');

            if ($('#kt_accounts_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable('[data-kt-accounts-table-filter="search"]', dt);
                handleUpdateRows();
                handleDeleteRows('[data-kt-accounts-table-actions="delete_row"]', 'data-kt-accounts-delete-url');
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBankAccountsServerSide.init();
});
