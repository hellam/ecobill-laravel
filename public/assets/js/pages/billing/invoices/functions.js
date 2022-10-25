let
    /**
     * define variables
     * @type {*|jQuery|HTMLElement}
     */
    parent_data_src = $('#kt_aside'),
    default_currency = current_currency = parent_data_src.attr('data-kt-default-currency'), fx_rate = 1,
    form = $('#kt_invoice_form'), customer_tax_rate, customer_tax_name, customer_discount, customer_tax_id = null,
    discount = null, total_discount, discount_type, sb_total = 0, tax_type = form.attr('data-kt-tax-type'),
    loader_image = parent_data_src.attr('data-kt-loader'), attachments = [],
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    });

/**
 * format row total, subtotal and total to home currency by default
 */
$('#grand-total, .total').html(
    new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
        currency: default_currency
    }).format(0)
)

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
        handleTotal()

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
                maximumFractionDigits: form.attr('data-kt-decimals'),
                minimumFractionDigits: form.attr('data-kt-decimals'),
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

        handleTotal()
        handleHomeCurrencyTotal()
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
                quotient = Math.abs(quotient)
            }
            row_total.html(new Intl.NumberFormat('ja-JP', {
                style: 'currency',
                maximumFractionDigits: form.attr('data-kt-decimals'),
                minimumFractionDigits: form.attr('data-kt-decimals'),
                currency: current_currency
            }).format(Math.abs(quotient)))
            row_total.attr('data-kt-quotient-total', Math.abs(quotient))
            sb_total = handleSubtotal()
            handleTaxTotal()
            handleTotal()
            handleHomeCurrencyTotal()
        });
    });

    $('.quantity').each(function () {
        $(this).on('keyup change', function () {
            let row_total = $(this).closest('tr').find('.total')
            let amount = $(this).closest('tr').find('.amount').val()
            if (!isNaN(this.value) && this.value.length !== 0 && amount.length !== 0 && !isNaN(amount)) {
                quotient = parseFloat(this.value) * parseFloat(amount);
                quotient = Math.abs(quotient)
            }
            row_total.html(new Intl.NumberFormat('ja-JP', {
                style: 'currency',
                maximumFractionDigits: form.attr('data-kt-decimals'),
                minimumFractionDigits: form.attr('data-kt-decimals'),
                currency: current_currency
            }).format(Math.abs(quotient)))
            row_total.attr('data-kt-quotient-total', Math.abs(quotient))
            sb_total = handleSubtotal()
            handleTaxTotal()
            handleTotal()
            handleHomeCurrencyTotal()
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
            sum = Math.abs(sum)
        }
        handleTaxTotal()
    })

    $('#sub-total').html(new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
        currency: current_currency
    }).format(sum))
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

    tax_total = Math.abs(tax_total)

    let num_format = new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
        currency: current_currency
    }).format(!isNaN(tax_total) ? tax_total : 0)

    if (tax_type == 1) {
        tax_table_head.html('(Tax Inclusive: ' + num_format + ')')
    } else {
        tax_table_head.html('Tax: ')
        tax_table_tax.html(num_format)
    }

    return tax_total
}

/**
 * handle tax change
 */
function handleTaxChange() {
    $('.tax_select').on('change', function () {
        handleSubtotal()
        handleTotal()
        handleHomeCurrencyTotal()
    })
}

/**
 * handle convert inputs and totals with current fx value if set
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

        $('#total_converted').html("")
    }

    $('#fx_rate_value').on('keyup change', function () {
        handleHomeCurrencyTotal()
        fx_rate = $(this).val()
        $('.repeater_items').find('tr').each(function () {
            let amount = $(this).find('.amount')
            let quantity = $(this).find('.quantity').val()
            let price = $(this).find('.select_product').find(':selected').attr('data-kt-price') ?? 0
            if (!isNaN(amount.val()) && !isNaN(fx_rate) && amount.length !== 0 && fx_rate.length !== 0 && fx_rate != 0) {
                let val = (price * quantity) / Math.abs(fx_rate)
                val = roundFloat(val, form.attr('data-kt-decimals'))
                Math.abs(amount.val(val).trigger('change'))
                handleRowQuotient()
            } else {
                Math.abs(amount.val(roundFloat(price * quantity, form.attr('data-kt-decimals'))).trigger('change'))
            }
        })
        handleTotal()
    })
}

/**
 * handle show total
 */
function handleTotal() {
    let total, num_total, initial_total

    if (tax_type == 1) total = handleSubtotal()
    else total = handleSubtotal() + handleTaxTotal()

    initial_total = total

    if (discount_type == 1) {
        total = ((100 - discount) / 100) * total
        total_discount = initial_total - total
    } else if (discount_type == 2) {
        total = total - discount
        total_discount = initial_total - total
    }

    num_total = total

    total = new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
        currency: current_currency
    }).format(!isNaN(total) ? total : 0)

    $('#grand-total').html(total)
    return num_total
}

/**
 * show or hide home currency total
 */
function handleHomeCurrencyTotal() {
    if (default_currency !== current_currency)
        $('#total_converted').html("(" + new Intl.NumberFormat('ja-JP', {
            style: 'currency',
            maximumFractionDigits: form.attr('data-kt-decimals'),
            minimumFractionDigits: form.attr('data-kt-decimals'),
            currency: default_currency
        }).format(handleTotal() * fx_rate) + ")")
    else
        $('#total_converted').html("")
}

/**
 * handle discount
 */
function handleAddRemoveDiscount() {
    $('#add_discount').on('click', function (e) {
        e.preventDefault()

        let add_dis_btn = $(this).hide()
        $('#discount_area').html(
            '<div class="row">' +
            '<div class="col-md-5 mb-2">' +
            '    <select name="discount_type"\n' +
            '            aria-label="Select Discount"\n' +
            '            data-placeholder="Select Discount"\n' +
            '            class="form-select form-select-sm form-select-solid fw-bolder select_discount">\n' +
            '        <option></option>\n' +
            '    </select>\n' +
            '</div>' +
            '<div class="col-md-5 mb-2">' +
            '    <input type="text"\n' +
            '           class="form-control form-control-solid form-control-sm text-end" id="discount_input" \n' +
            '           name="discount" placeholder="0.00" value="0.00"/>\n' +
            '</div>' +
            '<div class="col-md-2">' +
            '<button type="button" id="delete_discount" \n' +
            '        class="btn btn-sm btn-icon btn-light-danger"><i\n' +
            '        class="fa fa-times"></i></button>' +
            '</div>' +
            '</div>'
        )


        for (const [key, value] of Object.entries(JSON.parse($(this).attr('data-kt-discount')))) {
            let newOption = new Option(value, key, false, false);
            $('.select_discount').append(newOption).trigger('change');
        }

        $('.select_discount').select2()

        $('#delete_discount').on('click', function () {
            $('#discount_area').html('')
            add_dis_btn.show()
            discount = null
            discount_type = null
            total_discount = 0
            handleTotal()
        })

        $('.select_discount').on('select2:select', function () {
            discount_type = $(this).find(':selected').val()
            handleTotal()
        })


        $('#discount_input').on('keyup change', function () {
            discount = $(this).val()
            handleTotal()
            handleTaxTotal()
        })

    })
}

/**
 * handle invoice submit
 */
function handleSubmit() {
    let submitButton;
    submitButton = document.querySelector('#kt_add_invoice_submit')

    submitButton.addEventListener('click', function (e) {
        e.preventDefault()
        if (document.getElementById("sendEmail").checked) {
            document.getElementById('sendEmailCopy').disabled = true;
        }
        if (document.getElementById("sendSMS").checked) {
            document.getElementById('sendSMSCopy').disabled = true;
        }
        let serialized_form = form.serializeArray()

        let small_errors = $('small')
        if (small_errors.length) {
            small_errors.remove()
        }
        blockUI.block()
        submitInvoice(submitButton, form, serialized_form)

    })
}

/**
 * now submit invoice
 * @param submitButton
 * @param form
 * @param serialized_form
 */
function submitInvoice(submitButton, form, serialized_form) {
    if (attachments.length > 0)
        serialized_form.push({name: 'attachments', value: attachments});
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
                        select = $("select[name='" + value.field + "']"),
                        textarea = $("textarea[name='" + value.field + "']");
                    if (input.length) {
                        input.closest('.fv-row')
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup change', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }
                    if (textarea.length) {
                        textarea.closest('.fv-row')
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup change', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }

                    if (select.length) {
                        select.closest('.fv-row')
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('change', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }

                    if (value.field.includes('invoice_items.')) {
                        let field = value.field.split('.')
                        field = field[0] + "[" + field[1] + "][" + field[2] + "]"
                        let field_id = field[0] + "_" + field[1] + "_" + field[2] + "_" + key
                        let field_name = $('[name="' + field + '"]')
                        if (field_name.is("select")) {
                            if ($('#err_' + field_id).length) {
                                $('#err_' + field_id).html(value.error)
                            } else {
                                field_name.closest('.fv-row')
                                    .after('<small style="color: red;" id="err_' + field_id + '">' + value.error + '</small>')
                                    .on('change', function () {
                                        $('#err_' + field_id).remove();
                                    })
                            }
                        } else {
                            if ($('#err_' + field_id).length) {
                                $('#err_' + field_id).html(value.error)
                            } else {
                                field_name
                                    .after('<small style="color: red;" id="err_' + field_id + '">' + value.error + '</small>')
                                    .on('keyup', function () {
                                        $('#err_' + field_id).remove();
                                    })
                            }
                        }
                    }

                    if (value.field === 'invoice_items') {
                        $('.invoice_header').after('<div style="color: red;" id="err_invoice_items" class="text-center">' + value.error + '</div>')
                        $('#add_row').on('click', function () {
                            $('#err_invoice_items').remove()
                        })
                    }

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
                        submitDeposit(submitButton, form, serialized_form);
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

/**
 * reset invoice form after success on submit
 */
function handleResetForm() {
    form[0].reset(); // Reset form
    refGen($('[name="reference"]').attr('data-kt-src'))
    $('[name="due_date"], [name="invoice_date"]').val(moment().format(form.attr("data-kt-date-format")))
    $('[name="pay_terms"]').val(0).trigger('change')
    if ($('#fx_parent').length)
        $('#fx_parent').remove()
    if ($('#bank_parent').length) {
        $('#bank_parent').remove()
    }
    $('#total').text(0.00)
    $('.select_product').val(null).trigger('change')
    $('.select_customer').val(null).trigger('change')
    $('.invoice_items').children().not(':first').remove();
    discount_type = null
    total_discount = 0.00
    discount = 0
    fx_rate = 1
    $('#discount_area').html('')
    $('#add_discount').show()
    $('#total_converted').html('')
}

/**
 * handle show or hide send later date select field
 */
function handleShowScheduleSendDateTime() {
    let send_later_check = document.getElementById("sendLaterCheck")
    send_later_check.addEventListener('click', function () {
        if (this.checked) {
            $('#send_later_area').html(
                '<input name="schedule_send_date" placeholder="Select schedule time" id="schedule_send_date" class="form-control form-control-sm form-control-solid">'
            )

            $('#schedule_send_date').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                showDropdowns: true,
                startDate: moment(),
                minYear: 1901,
                locale: {
                    format: '' + form.attr('data-kt-date-format') + ' HH:mm:ss'
                },
                // autoApply: true,
                maxYear: parseInt(moment().format('YYYY'), 10)
            }, function (start, end, label) {
            });
        } else {
            $('#send_later_area').html('')
        }
    })
}

/**
 * handle show or hide late penalty fee input
 */
function handleShowLatePenaltyFee() {
    let late_penalty_check = document.getElementById("latePenaltyCheck")
    late_penalty_check.addEventListener('click', function () {
        if (this.checked) {
            $('#late_penalty_area').html(
                '<input name="late_penalty" placeholder="Penalty in percentage" id="late_penalty" class="form-control form-control-sm form-control-solid">'
            )
        } else {
            $('#late_penalty_area').html('')
        }
    })
}

/**
 * handle files dropzone show, hide among other dropzone operations
 */
function handleAddRemoveAttachment() {
    $('#add_attachment').on('click', function (e) {
        e.preventDefault()

        let btn = $(this).hide()
        let attachment_area = $('#attachment_area').html(
            '<!--begin::Dropzone-->\n' +
            '<div class="dropzone" id="kt_attachment_zone">\n' +
            '    <!--begin::Message-->\n' +
            '    <div class="dz-message needsclick">\n' +
            '        <!--begin::Icon-->\n' +
            '        <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>\n' +
            '        <!--end::Icon-->\n' +
            '        <!--begin::Info-->\n' +
            '        <div class="ms-4">\n' +
            '            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop attachments here or click to upload.</h3>\n' +
            '            <span class="fs-7 fw-semibold text-gray-400">Upload up to 10 files</span>\n' +
            '        </div>\n' +
            '        <!--end::Info-->\n' +
            '    </div>\n' +
            '</div>\n' +
            '<!--end::Dropzone-->' +
            '<button class="btn btn-sm btn-primary mt-5" type="button" id="remove_attachment">\n' +
            '    Remove Attachments\n' +
            '</button>'
        )

        let attachment_zone = new Dropzone("#kt_attachment_zone", {
            url: "#", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            autoProcessQueue: false,
            acceptedFiles: "image/*,application/pdf",
            maxFiles: 1,
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            accept: function (file, done) {
            },
            init: function () {
                this.on("addedfile", file => {
                    let dropzone = this
                    let files = this.files
                    attachments = []
                    files.map(single_file => {
                        $(".dz-progress").remove();
                        const reader = new FileReader()
                        reader.readAsDataURL(single_file)
                        reader.onload = function (event) {
                            if (single_file.type === 'application/pdf' || single_file.type.includes('image/')) {
                                if (!attachments.includes(event.target.result))
                                    attachments.push('' + event.target.result + '')
                                else {
                                    Snackbar.show({
                                        text: 'File already in queue!',
                                        pos: 'bottom-center'
                                    });
                                    dropzone.removeFile(file);
                                }
                            } else {
                                Snackbar.show({
                                    text: 'Failed!! Only PDF and image type files accepted.',
                                    pos: 'bottom-center'
                                });
                                dropzone.removeFile(file);
                            }
                        };
                    })
                });

                this.on("removedfile", file => {
                    let files = this.files
                    attachments = []
                    files.map(single_file => {
                        const reader = new FileReader()
                        reader.readAsDataURL(single_file)
                        reader.onload = function (event) {
                            if (single_file.type === 'application/pdf' || single_file.type.includes('image/'))
                                attachments.push('' + event.target.result + '')
                        };
                    })
                });
            }
        });

        $('#remove_attachment').on('click', function () {
            attachment_area.html('')
            btn.show()
            attachments = []
        })
    })
}
