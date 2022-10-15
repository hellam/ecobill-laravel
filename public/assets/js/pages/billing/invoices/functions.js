/**
 * define variables
 * @type {*|jQuery|HTMLElement}
 */
let parent_data_src = $('#kt_aside')
let default_currency = current_currency = parent_data_src.attr('data-kt-default-currency'),
    fx_rate = 1,
    form = $('#kt_invoice_form'),
    customer_tax_rate,
    customer_tax_name,
    customer_discount,
    customer_tax_id = null,
    sb_total = 0,
    tax_type = form.attr('data-kt-tax-type'),
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    })

/**
 * add fx select
 */
function addFxField() {
    $('.select_customer').on('select2:select', function () {
        /**
         * on change of customer reset bank select if present
         */
        if ($('.select_bank').length) {
            $('.select_bank').val(null).trigger('change')
        }
        /**
         * set selected payment term to customer's payment terms
         * @type {*|jQuery}
         */
        let pay_term = $(this).find(':selected').attr('data-kt-pay-terms')
        $('.select_terms').val(pay_term).trigger('change')

        /**
         * set default currency
         * @type {*|jQuery}
         */
        current_currency = $(this).find(':selected').attr('data-kt-currency')

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
                        handleConvertWithFX()
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
                handleConvertWithFX()
            }
            /**
             * order class order-first to bank area
             */
            if ($('#bank_parent').length) {
                $('#bank_area').addClass('order-first')
            }
        }
    })
}

/**
 * add bank select
 */
function addBankField() {
    $('[name="pay_terms"]').on('change', function () {
        let type = $(this).find(":selected").attr('data-kt-type')
        let select_url = $(this).attr('data-kt-select-url')
        if (type == 0) {
            if (!$('#fx_parent').length) {
                $('#bank_area').addClass('order-first')
            }
            $('#bank_area').html(
                '<!--begin::Label-->\n' +
                '<div id="bank_parent">' +
                '<label\n' +
                '    class="s-6 fw-bold text-gray-700 text-nowrap me-2 mb-2">Into Bank</label>\n' +
                '<!--end::Label-->\n' +
                '<!--begin::Col-->\n' +
                '<div class="fv-row">\n' +
                '    <!--begin::Input-->\n' +
                '    <select name="into_bank"\n' +
                '            aria-label="Select Account"\n' +
                '            data-kt-src="' + select_url + '"\n' +
                '            data-placeholder="Select Account"\n' +
                '            class="form-select form-select-sm form-select-solid fw-bolder select_bank">\n' +
                '        <option></option>\n' +
                '    </select>\n' +
                '    <!--end::Input-->\n' +
                '</div>\n' +
                '</div>\n' +
                '<!--end::Col-->'
            )
            handleBankAPISelect()
        } else {
            if ($('#bank_parent').length) {
                $('#bank_area').removeClass('order-first')
                $('#bank_parent').remove()
            }
        }
    })
}

/**
 * bank select
 */
function handleBankAPISelect(preselect = null) {
    const element = $('.select_bank');

    element.html("").trigger('change');

    if (preselect) {
        const option = new Option(preselect?.account_name + " - " + preselect?.currency, preselect?.id, true, true);
        option.setAttribute("data-kt-currency", preselect?.currency);
        $('.select_bank').append(option).trigger('change');
    }

    element.select2({
        minimumInputLength: 0,
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: element.attr("data-kt-src"),
            dataType: 'json',
            type: 'GET',
            contentType: 'application/json',
            delay: 50,
            data: function (params) {
                return {
                    search: params.term,
                    currency: current_currency
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.account_name + ' - ' + item.currency,
                            id: item.id,
                            'data-kt-currency': item.currency
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-kt-currency': data["data-kt-currency"],
            }
        );
    })
}

/**
 * customer select
 */
function handleCustomerSelect() {
    $('.select_customer').on('select2:select', function () {
        customer_tax_id = $(this).find(':selected').attr('data-kt-tax-id') ?? null
        customer_tax_rate = $(this).find(':selected').attr('data-kt-tax-rate')
        customer_tax_name = $(this).find(':selected').attr('data-kt-tax-name')
        customer_discount = $(this).find(':selected').attr('data-kt-discount')
        current_currency = $(this).find(':selected').attr('data-kt-currency')
        handleSubtotal()
        handleRowQuotient()

        /**
         * if customer tax is null set all products selected taxes to customer tax
         */
        if (customer_tax_id !== null) {
            $('.tax_select').val(customer_tax_id).trigger('change')

        }
        /**
         * else reset product taxes to default if customer tax is null
         */
        else {
            $('.select_product option').each(function () {
                let this_tax = $(this).attr('data-kt-tax')
                $(this).closest('tr').find('.tax_select').val(this_tax).trigger('change')
            })
        }

        /**
         * change currency on row total because no keyup is applied on inputs when customer changes
         */
        $('.total').each(function () {
            let total = 0
            if ($(this).attr('data-kt-quotient-total')) {
                total = $(this).attr('data-kt-quotient-total')
            }

            $(this).html(new Intl.NumberFormat('ja-JP', {
                style: 'currency',
                currency: current_currency
            }).format(total))
        })
    })
}

/**
 * product select
 */
function handleSelectProduct() {
    $('.select_product').on('select2:select', function () {
        let tax_id = $(this).find(':selected').attr('data-kt-tax')
        let price = $(this).find(':selected').attr('data-kt-price')
        /**
         * if customer tax is null set default selected product tax
         */
        if (customer_tax_id === null)
            $(this).closest('tr').find('.tax_select').val(tax_id).trigger('change')
        /**
         * else set product tax to customer selected tax
         */
        else
            $(this).closest('tr').find('.tax_select').val(customer_tax_id).trigger('change')

        $(this).closest('tr').find('.amount').val((price / fx_rate).toFixed(form.attr('data-kt-decimals'))).trigger('keyup')
    })
}

/**
 * handle quantity * price
 */
function handleRowQuotient() {
    let quotient = 0;
    $('.amount').each(function () {
        $(this).on('keyup change', function () {
            let row_total = $(this).closest('tr').find('.total')
            let quantity = $(this).closest('tr').find('.quantity').val()
            if (!isNaN(this.value) && this.value.length !== 0 && quantity.length !== 0 && !isNaN(quantity)) {
                quotient = parseFloat(this.value) * parseFloat(quantity);
            }
            row_total.html(new Intl.NumberFormat('ja-JP', {
                style: 'currency',
                currency: current_currency
            }).format(quotient))
            row_total.attr('data-kt-quotient-total', quotient)
            sb_total = handleSubtotal()
            handleTaxTotal()
        });
    });

    $('.quantity').each(function () {
        $(this).on('keyup change', function () {
            let row_total = $(this).closest('tr').find('.total')
            let amount = $(this).closest('tr').find('.amount').val()
            if (!isNaN(this.value) && this.value.length !== 0 && amount.length !== 0 && !isNaN(amount)) {
                quotient = parseFloat(this.value) * parseFloat(amount);
            }
            row_total.html(new Intl.NumberFormat('ja-JP', {
                style: 'currency',
                currency: current_currency
            }).format(quotient))
            row_total.attr('data-kt-quotient-total', quotient)
            sb_total = handleSubtotal()
            handleTaxTotal()
        });
    });
}

/**
 * handle subtotals from rows
 */
function handleSubtotal() {
    let sum = 0
    $('tr').each(function () {
        let quantity = $(this).find('.quantity').val()
        let amount = $(this).find('.amount').val()
        if (!isNaN(amount) && amount.length !== 0 && quantity.length !== 0 && !isNaN(quantity)) {
            sum += parseFloat(amount) * parseFloat(quantity);
        }
        handleTaxTotal()
    })
    $('#sub-total').html(new Intl.NumberFormat('ja-JP', {style: 'currency', currency: current_currency}).format(sum))
    return sum
}

/**
 * calculate tax, whether inclusive or exclusive
 */
function handleTaxTotal() {
    let tax_table_head = $('#tax_table_head')
    let tax_table_tax = $('#tax_table_tax')
    let tax_total = 0

    $('tr').each(function () {
        let quantity = $(this).find('.quantity').val()
        let amount = $(this).find('.amount').val()
        if (!isNaN(amount) && amount.length !== 0 && quantity.length !== 0 && !isNaN(quantity)) {
            let tax_rate = $(this).find('.tax_select').find(':selected').attr('data-kt-rate')
            let total_amount = parseFloat(amount) * parseFloat(quantity)
            let total_rate = parseFloat(tax_rate) + parseFloat("100")
            if (tax_type == 1) {
                tax_total += (total_amount * parseFloat(tax_rate)) / total_rate
            } else {
                tax_total += ((total_amount * total_rate) / parseFloat("100")) - total_amount
            }
        }
    })

    let num_format = new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        currency: current_currency
    }).format(!isNaN(tax_total) ? tax_total : 0)

    if (tax_type == 1) {
        tax_table_head.html('(Tax Inclusive: ' + num_format + ')')
    } else {
        tax_table_head.html('Tax: ')
        tax_table_tax.html(num_format)
    }
}

/**
 * handle tax change
 */
function handleTaxChange() {
    $('.tax_select').on('change', function () {
        handleSubtotal()
    })
}

/**
 *
 *
 */
function handleConvertWithFX() {
    if ($('[name="fx_rate"]').length > 0) {
        $('.repeater_items').find('tr').each(function () {
            let current_tr = $(this)
            let amount = current_tr.find('.amount')
            amount.val((parseFloat(amount.val()) / fx_rate).toFixed(form.attr('data-kt-decimals'))).trigger('change')
        })
    } else {
        $('.repeater_items').find('tr').each(function () {
            let product = $(this).find('.select_product').find(':selected')
            let quantity = $(this).find('.quantity').val()
            let amount = $(this).find('.amount')
            let price = product.attr('data-kt-price')

            if (!isNaN(price))
                amount.val(price * quantity).trigger('change')
        })
    }

    $('#fx_rate_value').on('keyup change', function () {
        fx_rate = $(this).val()
        $('.repeater_items').find('tr').each(function () {
            let amount = $(this).find('.amount')
            let quantity = $(this).find('.quantity').val()
            let price = $(this).find('.select_product').find(':selected').attr('data-kt-price')
            if (!isNaN(amount.val()) && !isNaN(fx_rate) && amount.length !== 0 && fx_rate.length !== 0 && fx_rate != 0) {
                let val = (price * quantity) / Math.abs(fx_rate)
                val = roundFloat(val, form.attr('data-kt-decimals'))
                Math.abs(amount.val(val).trigger('change'))
                handleRowQuotient()
            } else {
                Math.abs(amount.val(roundFloat(price * quantity, form.attr('data-kt-decimals'))).trigger('change'))
            }
        })
    })
}
