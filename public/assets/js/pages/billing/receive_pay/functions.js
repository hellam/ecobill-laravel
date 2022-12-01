let parent_data_src = $('#kt_aside'),
    default_currency = current_currency = parent_data_src.attr('data-kt-default-currency'),
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    });

/**
 * customer select
 */
function handleCustomerSelect() {
    $('.select_customer').on('select2:select', function () {
        current_currency = $(this).find(':selected').attr('data-kt-currency')
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
                    blockUI.release()
                    blockUI.destroy()
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
            $('#fx_area').removeClass('order-first')
            $('#fx_parent').remove()
        }
        /**
         * order class order-first to bank area
         */
        if ($('#bank_parent').length) {
            $('#bank_area').addClass('order-first')
        }
    }
}

/**
 * handle get single client invoices
 */
function getClientInvoices() {
    let client_invoices_url = $('.select_customer').attr('data-kt-invoices-url')
    client_invoices_url = client_invoices_url.replace(':id', $('.select_customer').val())

    $.ajax({
        url: client_invoices_url,
        type: 'GET',
        success: function (response) {
            console.log(response)
        },
    })
}
