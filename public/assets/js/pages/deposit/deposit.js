"use strict";

// Class definition
const KTDepositAdd = function () {
    // Shared variables
    let submitButton, form;

    function initializeRepeater() {
        $('#kt_deposit_items_row').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $(this).slideDown();
                // Re-init select2
                $(this).find('[data-kt-add-deposit="deposit_option"]').select2({
                    minimumInputLength: 0,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    ajax: {
                        url: $(this).find('[data-kt-add-deposit="deposit_option"]').attr("data-kt-src"),
                        dataType: 'json',
                        type: 'GET',
                        contentType: 'application/json',
                        delay: 50,
                        data: function (params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.account_code + ' - ' + item.account_name,
                                        id: item.id
                                    }
                                })
                            }
                        }
                    }
                });
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function () {
                // Init select2
                $('[data-kt-add-deposit="deposit_option"]').select2();
            }
        });

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
                let src_url = $(this).attr('data-kt-src')
                $('#changing_div label').text('Customer')
                $('#select_input').html('' +
                    '<select name="customer"\n' +
                    ' aria-label="Select Customer"\n' +
                    ' data-control="select2"\n' +
                    ' data-kt-src="' + src_url + '"\n' +
                    ' data-placeholder="Select Customer"\n' +
                    ' class="form-select form-select-sm form-select-solid fw-bolder select_customer_branch">\n' +
                    '</select>')
                $('#select_input select').select2()
                handleCustomerBranchAPISelect()
            }
        })
    }


    // Public methods
    return {
        init: function () {
            initializeRepeater()
            handleBankAPISelect()
            handleGLAccountsAPISelect()
            handleAddFXFields()
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDepositAdd.init();
});
