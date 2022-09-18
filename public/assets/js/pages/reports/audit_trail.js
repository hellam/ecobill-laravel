"use strict";

// Class definition
const KTAuditTrailServerSide = function () {
    // Shared variables
    let table;
    let dt;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_audits_table')
        dt = $("#kt_audits_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            // order: [[1, 'desc']],
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'type'},
                {data: 'user'},
                {data: 'request_type'},
                {data: 'created_at'},
                {data: 'description'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false
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

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-outbox-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {

            if ($('#kt_audits_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAuditTrailServerSide.init();
});
