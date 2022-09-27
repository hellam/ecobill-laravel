"use strict";

// Class definition
const KTUserRolesServerSide = function () {
// Shared variables
    let table, dt, form, delete_url;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_user_roles_table')
        dt = $("#kt_user_roles_table").DataTable({
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'user'},
                {data: 'branch'},
                {data: 'role'},
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
                                <input type="hidden" class="delete_url" value="${response.delete_url}" />
                            </div>`;
                    }
                }, {
                    targets: -1,
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function () {
                        return `
                            <a href="#" class="btn btn-success my-1 me-12" data-kt-user-roles-table-actions="delete_row">
                                Delete
                            </a>
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
        });
    };

    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_user_roles_form');

            if ($('#kt_user_roles_table').length) {
                initDatatable();
                dt.search('').draw();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUserRolesServerSide.init();
});
