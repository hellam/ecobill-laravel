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
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
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
    const handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-audit-trail-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    };

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        filterPayment = document.querySelectorAll('[data-kt-tax-table-filter="payment_type"] [name="payment_type"]');
        const filterButton = document.querySelector('[data-kt-tax-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Get filter values
            let paymentValue = '';

            // Get payment value
            filterPayment.forEach(r => {
                if (r.checked) {
                    paymentValue = r.value;
                }

                // Reset payment value if "All" is selected
                if (paymentValue === 'all') {
                    paymentValue = '';
                }
            });

            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            dt.search(paymentValue).draw();
        });
    }

    // Public methods
    return {
        init: function () {

            if ($('#kt_audits_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
                handleFilterDatatable();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAuditTrailServerSide.init();
});
