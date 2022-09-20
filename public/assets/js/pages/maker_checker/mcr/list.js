"use strict";

// Class definition
const KTMakerCheckerRulesServerSide = function () {
    // Shared variables
    let table;
    let dt;
    let form, closeButton, cancelButton, modal, submitButton;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_maker_checker_rules_table')
        dt = $("#kt_maker_checker_rules_table").DataTable({
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'maker_type'},
                {data: 'permission_code'},
                {data: 'created_by'},
                {data: 'status', name: 'inactive'},
                {data: 'created_at'},
                {data: 'actions'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        var response = row.id;
                        return `
                            <div>
                                <p>${response.id}</p>
                                <input type="hidden" class="edit_url" value="${response.edit_url}" />
                                <input type="hidden" class="update_url" value="${response.update_url}" />
                                <input type="hidden" class="delete_url" value="${response.delete_url}" />
                            </div>`;
                    }
                }, {
                    targets: -1,
                    data: 'action',
                    orderable: false,
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
                                    <a href="#" class="menu-link px-3 test" data-kt-rule-table-actions="edit_row">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 test" data-kt-rule-table-actions="delete_row">
                                        Delete
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        `;
                    },
                }, {
                    targets: -3,
                    orderable: true,
                    render: function (data, type, row) {
                        return decodeHtml(row.inactive);
                    }
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
        const editButtons = document.querySelectorAll('[data-kt-rule-table-actions="edit_row"]');

        // Make the DIV element draggable:
        const element = document.querySelector('#kt_modal_update_rule');
        dragElement(element);
        editButtons.forEach(d => {
            // edit button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_rule_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_rule").modal('show');//show modal
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

                            $('#kt_modal_update_rule_form').show({backdrop: 'static', keyboard: false});//show form
                            const rule = response.data;

                            $("#kt_modal_update_rule_form select[name='action']").val(rule.permission_code).trigger('change');
                            $("#kt_modal_update_rule_form").find('input[value="' + rule.maker_type + '"]').prop('checked', true);
                        }

                        $('.loader_container').hide();//hide loader

                    },
                    error: function (xhr, desc, err) {
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

    // const start = moment();
    // const end = moment();
    //
    // function cb(start, end) {
    //     $("#kt_date_range_picker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    // }
    //
    // $("#kt_date_range_picker").daterangepicker({
    //     startDate: start,
    //     endDate: end,
    //     ranges: {
    //         "Today": [moment(), moment()],
    //         "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
    //         "Last 7 Days": [moment().subtract(6, "days"), moment()],
    //         "Last 30 Days": [moment().subtract(29, "days"), moment()],
    //         "This Month": [moment().startOf("month"), moment().endOf("month")],
    //         "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
    //     }
    // }, cb);

    // Filter Datatable
    // var handleFilterDatatable = () => {
    //     // Select filter options
    //     filterPayment = document.querySelectorAll('[data-kt-tax-table-filter="payment_type"] [name="payment_type"]');
    //     const filterButton = document.querySelector('[data-kt-tax-table-filter="filter"]');
    //
    //     // Filter datatable on submit
    //     filterButton.addEventListener('click', function () {
    //         // Get filter values
    //         let paymentValue = '';
    //
    //         // Get payment value
    //         filterPayment.forEach(r => {
    //             if (r.checked) {
    //                 paymentValue = r.value;
    //             }
    //
    //             // Reset payment value if "All" is selected
    //             if (paymentValue === 'all') {
    //                 paymentValue = '';
    //             }
    //         });
    //
    //         // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
    //         dt.search(paymentValue).draw();
    //     });
    // }

    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_rule_form');


            if ($('#kt_maker_checker_rules_table').length) {
                initDatatable();
                dt.search('').draw();
                handleUpdateRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTMakerCheckerRulesServerSide.init();
});
