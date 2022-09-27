"use strict";

// Class definition
const KTUsersServerSide = function () {
// Shared variables
    let table, dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_users_table')
        dt = $("#kt_users_table").DataTable({
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'full_name'},
                {data: 'phone'},
                {data: 'email'},
                {data: 'username'},
                {data: 'last_visit'},
                {data: 'inactive'},
                {data: 'actions'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        const response = row.id;
                        return `
                            <div>
                                ${row.DT_RowIndex}
                                <input type="hidden" class="edit_url" value="${response.edit_url}" />
                                <input type="hidden" class="update_url" value="${response.update_url}" />
                                <input type="hidden" class="delete_url" value="${response.delete_url}" />
                            </div>`;
                    }
                }, {
                    targets: -1,
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function () {
                        return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 test" data-kt-branch-table-actions="edit_row">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->
                        `;
                    },
                }, {
                    targets: -2,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return decodeHtml(row.inactive)
                    },
                }
            ],
            // Add data-filter attribute
            createdRow: function (row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            }
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            KTMenu.createInstances();
            handleUpdateRows();
        });
    };

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-branch-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_users');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_users_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_users").modal('show');//show modal
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

                            $('#kt_modal_update_users_form').show({backdrop: 'static', keyboard: false});//show form
                            const branch = response.data;
                            //
                            $("#kt_modal_update_users_form input[name='name']").val(branch.name);
                            $("#kt_modal_update_users_form input[name='email']").val(branch.email);
                            $("#kt_modal_update_users_form input[name='bcc_email']").val(branch.bcc_email);
                            $("#kt_modal_update_users_form input[name='phone']").val(branch.phone);
                            $("#kt_modal_update_users_form input[name='tax_no']").val(branch.tax_no);
                            $("#kt_modal_update_users_form select[name='default_bank_account']").val(branch.default_bank_account).trigger('change');
                            $("#kt_modal_update_users_form select[name='tax_period']").val(branch.tax_period).trigger('change');
                            $("#kt_modal_update_users_form select[name='default_currency']").val(branch.default_currency).trigger('change');
                            $("#kt_modal_update_users_form select[name='fiscal_year']").val(branch.fiscal_year).trigger('change');
                            $("#kt_modal_update_users_form select[name='timezone']").val(branch.timezone).trigger('change');
                            $("#kt_modal_update_users_form textarea[name='address']").val(branch.address);

                            $("#kt_modal_update_users_form input[name='inactive']").val(branch.inactive);
                            if (branch.inactive === 0) {
                                $("#kt_modal_update_users_form input[id='inactive']").prop("checked", true);
                            } else {
                                $("#kt_modal_update_users_form input[id='inactive']").prop("checked", false)
                            }

                            //active/inactive
                            $("#kt_modal_update_users_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked'))
                                    $("#kt_modal_update_users_form input[name='inactive']").val(0)
                                else {
                                    $("#kt_modal_update_users_form input[name='inactive']").val(1)
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
            form = document.querySelector('#kt_modal_update_users_form');

            if ($('#kt_users_table').length) {
                initDatatable();
                dt.search('').draw();
                handleUpdateRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersServerSide.init();
});
