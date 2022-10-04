"use strict";

// Class definition
const KTUnsupervisedData = function () {
// Shared variables
    let table, dt;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_maker_unsupervised_table')
        dt = $("#kt_maker_unsupervised_table").DataTable({
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'maker'},
                {data: 'module'},
                {data: 'trx_type'},
                {data: 'method'},
                {data: 'description'},
                {data: 'actions'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        return row.DT_RowIndex;
                    }
                },
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, row) {
                        return `
                            <div>
                                ${row.trx_type.trx_type}
                                <input type="hidden" class="data" value="${row.trx_type.html_data}" />
                                <input type="hidden" class="approve_url" value="${row.trx_type.approve_url}" />
                                <input type="hidden" class="reject_url" value="${row.trx_type.reject_url}" />
                            </div>`;
                    }
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
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
                                    <a href="#" class="menu-link px-3" data-kt-unsupervised-table-actions="view_row">
                                        View Details
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-unsupervised-table-actions="approve_row">
                                        Approve
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-unsupervised-table-actions="reject_row">
                                        Reject
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        `;
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
            handleViewDetails();
            handleApproveReject();
        });
    };

    //Start Methods here
    //Approve Button
    const handleApproveReject = function () {
        // Select all delete buttons
        const actionButtons = document.querySelectorAll('[data-kt-unsupervised-table-actions="approve_row"], [data-kt-unsupervised-table-actions="reject_row"]');

        // dragElement(element);
        actionButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                // Select parent row
                const parent = e.target.closest('tr');
                let action_url, action;

                // Get rule name
                if ($(this).attr('data-kt-unsupervised-table-actions') === 'approve_row') {
                    action = 'approve';
                    action_url = parent.querySelector("input[class='approve_url']").value;
                } else if ($(this).attr('data-kt-unsupervised-table-actions') === 'reject_row') {
                    action = 'reject';
                    action_url = parent.querySelector("input[class='reject_url']").value;
                }

                Swal.fire({
                    text: "Are you sure you want to "+action+" this?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, "+action+"!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            text: "Submitting",
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: action_url,
                            success: function (json) {
                                const response = JSON.parse(JSON.stringify(json));
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
                                        confirmButtonText: "Ok!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            if ($('#kt_maker_unsupervised_table').length) {
                                                $("#kt_maker_unsupervised_table").DataTable().ajax.reload();
                                            }
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
                })
            })
        });
    };

    //View Details Button
    const handleViewDetails = function () {
        // Select all delete buttons
        const viewButtons = document.querySelectorAll('[data-kt-unsupervised-table-actions="view_row"]');

        // Make the DIV element draggable:
        // const element = document.querySelector('#kt_modal_unsupervised_data');
        // dragElement(element);
        viewButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = e.target.closest('tr');
                const data = parent.querySelector("input[class='data']").value;
                $('#data').html(data)
                $("#kt_modal_unsupervised_data").modal('show');//show modal

            })
        });

    };
    //End Methods here
    return {
        init: function () {
            if ($('#kt_maker_unsupervised_table').length) {
                initDatatable();
                dt.search('').draw();
                handleViewDetails();
                handleApproveReject()
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUnsupervisedData.init();
});
