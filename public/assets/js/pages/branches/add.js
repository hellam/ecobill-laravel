"use strict";

// Class definition
const KTBranchesAdd = function () {
    // Shared variables
    let table, dt, form;

    const handleForm = function () {

    }

    $("#kt_date_range_picker").daterangepicker({
            singleDatePicker: true,
            // showDropdowns: true,
            locale: {
                format: "DD/MM"
            },
            // minYear: 1901,
            // maxYear: parseInt(moment().format("YYYY"), 10)
        }, function (start, end, label) {
        }
    );

    // Public methods
    return {
        init: function () {
            // form = document.querySelector('#kt_modal_update_rule_form');

            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBranchesAdd.init();
});
