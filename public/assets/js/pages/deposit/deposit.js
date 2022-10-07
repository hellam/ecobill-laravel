"use strict";

// Class definition
const KTDepositAdd = function () {
    // Shared variables
    let submitButton, form;

    function initializeRepeater() {
        $('.form-select').select2()
        $("#kt_deposit_items_row").repeater({
            initEmpty: !1,
            defaultValues: {"text-input": "foo"},
            show: function () {
                $(this).slideDown()
                $('.form-select').next().remove();
                $('.form-select').select2({
                    allowClear: true
                });
            },
            hide: function (remove) {
                $(this).slideUp(remove)
            },
        })

        $("#date_picker").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format("YYYY"), 10)
            }, function (start, end, label) {
            }
        );

        $('[name="from"]').on('change', function (e) {
            let value = e.target.value
            if (value == 0) {
                $('#changing_div label').text('Name')
                $('#select_input').html('' +
                    '<!--begin::Input-->\n' +
                    '<input type="text" class="form-control form-control-sm form-control-solid"\n' +
                    'placeholder="Name"\n' +
                    'name="name"/>\n' +
                    '<!--end::Input-->')
            } else {
                $('#changing_div label').text('Customer')
                $('#select_input').html('' +
                    '<select name="from"\n' +
                    ' aria-label="Select From"\n' +
                    ' data-control="select2"\n' +
                    ' data-kt-src="#"\n' +
                    ' data-placeholder="Select Country"\n' +
                    ' class="form-select form-select-sm form-select-solid fw-bolder">\n' +
                    '<option value="0">Miscellaneous</option>\n' +
                    '<option value="1">Customer</option>\n' +
                    '</select>')
                $('#select_input select').select2()
            }
        })
    }

    // Public methods
    return {
        init: function () {
            initializeRepeater()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDepositAdd.init();
});
