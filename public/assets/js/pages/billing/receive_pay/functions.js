let parent_data_src = $('#kt_aside'),
    default_currency = current_currency = parent_data_src.attr('data-kt-default-currency'),
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    }),
    form = $('#kt_receive_pay_form')

/**
 * customer select
 */
function handleCustomerSelect() {
    $('.select_customer').on('select2:select', function () {
        current_currency = $(this).find(':selected').attr('data-kt-currency')
        $('#invoices_table').find('tbody').empty()
        handleBankAccountAPISelect(current_currency)
        handleFxField(current_currency)
        getClientInvoices()
        $('.select_bank').val('').trigger('change')
    })
    handleBankAccountAPISelect(current_currency)
}


/**
 * add fx select
 */
function handleFxField(current_currency) {
    /**
     * if default currency is not equal to current currency for the selected user
     */
    if (default_currency !== current_currency) {
        if (!blockUI.isBlocked())
            blockUI.block()
        /**
         * get fx rate
         */
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('.select_customer').attr('data-kt-fx-url'),
            type: 'POST',
            data: {
                from: default_currency,
                to: current_currency
            },
            success: function (response) {
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
                    if (blockUI.isBlocked()) {
                        blockUI.release()
                        blockUI.destroy()
                    }
                    if ($('#fx_input').length) {
                    } else {
                        if (!$('#bank_parent').length) {
                            $('#fx_parent').addClass('order-first')
                        }
                        $('#fx_area').html(
                            '<!--begin::Input group-->\n' +
                            '<div id="fx_parent">' +
                            '<div class="fs-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Exchange Rate:</div>' +
                            '<div class="row fv-row" id="fx_input">\n' +
                            '    <!--begin::Col-->\n' +
                            '    <div class="col-lg-7">\n' +
                            '        <!--begin::Input-->\n' +
                            '        <input type="number" class="form-control form-control-sm form-control-solid"\n' +
                            '               placeholder="From ' + default_currency + " to " + current_currency + '"\n' +
                            '               name="fx_rate" id="fx_rate_value"/>\n' +
                            '        <!--end::Input-->\n' +
                            '    </div>\n' +
                            '    <!--end::Col-->\n' +
                            '    <!--begin::Label-->\n' +
                            '    <label\n' +
                            '        class="col-lg-5 col-form-label fw-semibold fs-9" id="label_fx"></label>\n' +
                            '    <!--end::Label-->\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '<!--end::Input group-->'
                        );
                    }
                    fx_rate = response.data.fx_rate
                    $('[name="fx_rate"]').val(fx_rate)
                    $('#label_fx').html(default_currency + " = 1 " + current_currency)
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
        })
    } else {
        /**
         * if fx rate field is available and default currency is equal to
         * current then remove fx rate field
         */
        if ($('#fx_parent').length) {
            $('#fx_parent').remove()
        }
    }
}

/**
 * handle get single client invoices
 */
function getClientInvoices() {
    let client_invoices_url = $('.select_customer').attr('data-kt-invoices-url')
    client_invoices_url = client_invoices_url.replace(':id', $('.select_customer').val())
    if (!blockUI.isBlocked())
        blockUI.block()

    $.ajax({
        url: client_invoices_url,
        type: 'GET',
        success: function (response) {
            if (blockUI.isBlocked()) {
                blockUI.release()
                blockUI.destroy()
            }

            let table = $('#invoices_table')
            let invoices = response.data

            if (invoices.length > 0) {
                $('.no_items').addClass('d-none')
                $('#notifications_area, #kt_receive_pay_submit').removeClass('d-none')
                invoices.map((item) => {
                    table.find('tbody').append(
                        "<tr data-kt-amount=" + item.amount + "  data-kt-allocated=" + item.alloc + " data-kt-balance=" + (parseFloat(item.amount) - parseFloat(item.alloc)) + ">" +
                        "<td class='fw-normal'>" + item.trans_no + "</td>" +
                        "<td class='fw-normal'>" + formatCurrency(current_currency, form, item.amount) + "</td>" +
                        "<td class='fw-normal'>" + formatCurrency(current_currency, form, item.alloc) + "</td>" +
                        "<td><input class='form-control form-control-sm form-control-solid fw-bold w-auto fw-normal' disabled value='0'></td>" +
                        "<td class='fw-normal'>" + formatCurrency(current_currency, form, (parseFloat(item.amount) - parseFloat(item.alloc))) + "</td>" +
                        "</tr>"
                    );
                })
                table.append('<tfoot>' +
                    '<tr class="border-top">' +
                    '<th>Total</th>' +
                    '<th id="amount_totals"></th>' +
                    '<th id="allocated_totals"></th>' +
                    '<th id="this_alloc_totals"></th>' +
                    '<th id="balance_totals"></th>' +
                    '</tr>' +
                    '</tfoot>')
                shareAllocation()
                handleTableTotals()
            } else {
                $('.no_items').removeClass('d-none')
                $('#notifications_area, #kt_receive_pay_submit').addClass('d-none')
                $('#empty').text('No pending invoices found for selected customer')
            }
        },
    })
}

/**
 * handle share amount to multiple invoices
 */
function handleShareAmount() {
    $('#amount').on('keyup change', function () {
        shareAllocation()
        handleTableTotals()
    })
}

/**
 * function that holds sharing of allocation
 */
function shareAllocation() {
    let amount = Math.abs($('#amount').val())
    $('#invoices_table').find('tbody tr').each(function (index, element) {
        let this_balance = $(element).attr('data-kt-balance')
        if (parseFloat(this_balance) > parseFloat(amount) || parseFloat(this_balance) == parseFloat(amount)) {
            $(element).find("td:eq(-2)").find('input').val(formatAmountOnly(form, amount))

            $(element).find("td:eq(-1)").text(formatCurrency(current_currency, form, (parseFloat(this_balance) - parseFloat(amount))))
            $(element).attr('data-kt-this-balance', (parseFloat(this_balance) - parseFloat(amount)))
            amount = 0
        } else if (parseFloat(amount) > parseFloat(this_balance)) {
            $(element).find("td:eq(-2)").find('input').val(formatAmountOnly(form, this_balance))

            $(element).find("td:eq(-1)").text(formatCurrency(current_currency, form, 0))
            $(element).attr('data-kt-this-balance', 0)
            amount = amount - this_balance
        } else {
            $(element).find("td:eq(-2)").find('input').val(0)
            $(element).attr('data-kt-this-balance', this_balance)
            amount = 0
        }
    })
}

/**
 * handle show totals for amount, allocated and balance
 * @returns {number}
 */
function handleTableTotals() {
    let amount_totals = 0, allocated_totals = 0, balance_totals = 0;
    $('#invoices_table').find('tbody tr').each(function (index, element) {
        let this_total = $(element).attr('data-kt-amount')
        let allocated_total = $(element).attr('data-kt-allocated')
        let balance_total = $(element).attr('data-kt-this-balance')
        amount_totals += parseFloat(this_total);
        allocated_totals += parseFloat(allocated_total);
        balance_totals += parseFloat(balance_total);
    })
    $('#amount_totals').text(formatCurrency(current_currency, form, amount_totals))
    $('#allocated_totals').text(formatCurrency(current_currency, form, allocated_totals))
    $('#balance_totals').text(formatCurrency(current_currency, form, balance_totals))

    return amount_totals;
}

/**
 * handle submit
 */
function formSubmit() {
    let submitButton = document.querySelector('#kt_receive_pay_submit')
    let validator = FormValidation.formValidation(
        document.querySelector('#kt_receive_pay_form'),
        {
            fields: {
                amount: {
                    validators: {
                        notEmpty: {
                            message: 'Amount is required'
                        }
                    }
                },
                into_bank: {
                    validators: {
                        notEmpty: {
                            message: 'Into Bank is required'
                        }
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                }),
            }
        }
    );

    $(`[name="into_bank"]`).on('select2:select', function () {
        // Revalidate the field when an option is chosen
        validator.revalidateField(`into_bank`);
    });


    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (validator) {
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    if (document.getElementById("sendEmail").checked) {
                        document.getElementById('sendEmailCopy').disabled = true;
                    }
                    if (document.getElementById("sendSMS").checked) {
                        document.getElementById('sendSMSCopy').disabled = true;
                    }

                    let serialized_form = form.serializeArray();

                    if (parseFloat($('[name="amount"]').val()) > parseFloat(handleTableTotals())) {
                        Swal.fire({
                            text: "Amount entered is greater than total required amount. " +
                                "Would you like to deposit the remaining balance to customer account?",
                            icon: "info",
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-secondary order-first"
                            }
                        }).then(isConfirmed => {
                            if (isConfirmed) {
                                serialized_form.push({name: 'deposit_rest', value: true});
                            }
                        });
                    }

                    ajaxSubmit(submitButton, serialized_form);


                } else {
                    Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            })
        }
    })
}

function ajaxSubmit(submitButton, serialized_form) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: form.attr("data-kt-action"),
        data: serialized_form,
        success: function (json) {
            var response = JSON.parse(JSON.stringify(json));
            if (response.status !== true) {
                var errors = response.data;
                blockUI.release()
                blockUI.destroy()
                for (const [key, value] of Object.entries(errors)) {
                    $('#err_' + value.field).remove();
                    let input = $("input[name='" + value.field + "']"),
                        select = $("select[name='" + value.field + "']")
                    input.closest('.fv-row')
                        .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                        .on('keyup change', function (e) {
                            $('#err_' + value.field).remove();
                        })
                    select.closest('.fv-row')
                        .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                        .on('change', function (e) {
                            $('#err_' + value.field).remove();
                        })

                }
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
                blockUI.release()
                blockUI.destroy()
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
                        // Enable submit button after loading
                        submitButton.disabled = false;
                        handleResetForm()
                    }
                });
            }
            submitButton.removeAttribute('data-kt-indicator');

            // Enable submit button after loading
            submitButton.disabled = false;

        },
        statusCode: {
            203: function () {
                Swal.fire({
                    text: "Please provide remarks",
                    icon: "info",
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    allowOutsideClick: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    // showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    // delete row data from server and re-draw datatable
                    if (result.isConfirmed) {
                        serialized_form.push({name: 'remarks', value: result.value});
                        ajaxSubmit(submitButton, serialized_form);
                    } else {
                        blockUI.release()
                        blockUI.destroy()
                        handleResetForm()
                    }
                });
            }
        },
        error: function () {
            blockUI.release()
            blockUI.destroy()
            Swal.fire({
                text: 'A network error occurred. Please consult your network administrator.',
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

            submitButton.removeAttribute('data-kt-indicator');

            // Enable submit button after loading
            submitButton.disabled = false;

        }
    });
}

function handleResetForm() {

}
