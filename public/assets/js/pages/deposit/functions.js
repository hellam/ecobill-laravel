let loader_image = $('#rates_area').attr('data-kt-loader')
let blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
    message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
})

let default_currency = $('[name="currency"]').attr('data-kt-default')
let current_currency = $('[name="currency"]').attr('data-kt-default')
let total, fx_rate;

$('[name="currency"]').on('change', function (e) {
    current_currency = e.target.value
    $('.select_bank').val(null).trigger('change')
    calculateSum()
    addFXField()
})

function handleCustomerBranchAPISelect(preselect = null) {
    const element = document.querySelector('.select_customer_branch');

    $('.select_customer_branch').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.short_name + " | " + preselect?.f_name + " " + preselect?.l_name, preselect?.id, true, true);
        $('.select_customer_branch').append(option).trigger('change');
    }

    $('.select_customer_branch').select2({
        placeholder: 'Select Customer',
        minimumInputLength: 0,
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: element.getAttribute("data-kt-src"),
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
                            text: item.short_name + ' | ' + item.f_name + ' ' + item.l_name + ' - ' + item.currency,
                            id: item.id,
                            'data-kt-currency': item.currency,
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

        let customer_currency = $('.select_customer_branch').find(':selected').data('kt-currency')
        $('#currency').val(customer_currency).trigger('change').attr('disabled', true)
    })
}

function addFXField() {
    if (current_currency !== default_currency) {
        blockUI.block()
        if ($('#total_converted'))
            $('#total_converted').remove()
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('.select_bank').attr('data-kt-fx-url'),
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
                    if ($('#current_to_default').length) {
                    } else {
                        $('#rates_area').append(
                            '<div class="col-md-4" id="current_to_default">' +
                            '<!--begin::Input group-->\n' +
                            '<div class="row mb-6 mx-2 fv-row" id="cust_to_bank">\n' +
                            '    <!--begin::Col-->\n' +
                            '    <div class="col-lg-7">\n' +
                            '        <!--begin::Input-->\n' +
                            '        <input type="text" class="form-control form-control-sm form-control-solid"\n' +
                            '               placeholder="From ' + default_currency + " to " + current_currency + '"\n' +
                            '               name="fx_rate"/>\n' +
                            '        <!--end::Input-->\n' +
                            '    </div>\n' +
                            '    <!--end::Col-->\n' +
                            '    <!--begin::Label-->\n' +
                            '    <label\n' +
                            '        class="col-lg-5 col-form-label fw-semibold fs-7" id="label_fx"></label>\n' +
                            '    <!--end::Label-->\n' +
                            '</div>\n' +
                            '<!--end::Input group-->' +
                            '</div>'
                        );
                    }
                    fx_rate = response.data.fx_rate
                    $('[name="fx_rate"]').val(fx_rate)
                    $('#label_fx').html(default_currency + " = 1 " + current_currency)
                    $('#total').after('<div class="text-end my-5 text-muted" id="total_converted">'+"(Total: " + new Intl.NumberFormat('ja-JP', {
                        style: 'currency',
                        currency: default_currency
                    }).format(total * fx_rate) + ")"+'</div>')
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
        if ($('#current_to_default').length || $('#total_converted').length) {
            $('#current_to_default').remove()
            $('#total_converted').remove()
        }
    }
}

function handleBankAPISelect(preselect = null) {
    const element = document.querySelector('.select_bank');

    $('.select_bank').html("").trigger('change');

    if (preselect) {
        const option = new Option(preselect?.account_name + " - " + preselect?.currency, preselect?.id, true, true);
        option.setAttribute("data-kt-currency", preselect?.currency);
        $('.select_bank').append(option).trigger('change');
    }

    $('.select_bank').select2({
        minimumInputLength: 0,
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: element.getAttribute("data-kt-src"),
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

function handleSum() {
    $('.amount').each(function () {
        $(this).keyup(function () {
            calculateSum()
        });
    });
}

function calculateSum() {
    let sum = 0
    $('.amount').each(function () {
        if (!isNaN(this.value) && this.value.length !== 0) {
            sum += parseFloat(this.value);
        }
    });

    $('#total').html(new Intl.NumberFormat('ja-JP', {style: 'currency', currency: current_currency}).format(sum))
    total = sum
    totalHomeCurrency()
    return sum
}

function handleGLAccountsAPISelect(preselect = null) {
    const element = document.querySelector('.gl_select');

    $('.gl_select').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.account_name + " - " + preselect?.currency, preselect?.account_code, true, true);
        $('.gl_select').append(option).trigger('change');
    }

    $('.gl_select').select2({
        minimumInputLength: 0,
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: element.getAttribute("data-kt-src"),
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
                            id: item.account_code
                        }
                    })
                }
            }
        }
    })
}

function handleSubmit() {
    let form, submitButton;
    form = $('#kt_add_deposit_form');
    submitButton = document.querySelector('#kt_add_deposit_submit')

    form.on('submit', function (e) {
        e.preventDefault()
        let serialized_form = form.serializeArray()

        let small_errors = $('small')
        if (small_errors.length) {
            small_errors.remove()
        }
        blockUI.block()
        submitDeposit(submitButton, form, serialized_form)

    })
}

function submitDeposit(submitButton, form, serialized_form) {
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
                        select = $("select[name='" + value.field + "']");
                    if (input.length) {
                        input.closest('.fv-row')
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup', function (e) {
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

                    if (value.field.includes('deposit_options.')) {
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

                    if (value.field === 'deposit_options') {
                        $('.deposit_header').after('<div style="color: red;" id="err_deposit_options" class="text-center">' + value.error + '</div>')
                        $('#add_row').on('click', function () {
                            $('#err_deposit_options').remove()
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
                        form[0].reset(); // Reset form
                        refGen($('[name="reference"]').attr('data-kt-src'))
                        $("#date_picker").val(moment().format($("#date_picker").attr("data-kt-date-format")))
                        $('[name="from"]').val(0).trigger('change')
                        $('[name="currency"]').val($('[name="currency"]').attr('data-kt-default')).trigger('change')
                        $('#total').text(0.00)
                        $('.gl_select').val(null).trigger('change')
                        $('.deposit_options').children().not(':first').remove();
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
                        form[0].reset(); // Reset form
                        refGen('deposit', $('[name="reference"]').attr('data-kt-src'))
                        $("#date_picker").val(moment().format($("#date_picker").attr("data-kt-date-format")))
                        $('[name="from"]').val(0).trigger('change')
                        $('[name="currency"]').val($('[name="currency"]').attr('data-kt-default')).trigger('change')
                        $('#total').text(0.00)
                        $('.gl_select').val(null).trigger('change')
                        $('.deposit_options').children().not(':first').remove();
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

function totalHomeCurrency() {
    if ($('#total_converted').length)
        $('#total_converted').html("(Total: " + new Intl.NumberFormat('ja-JP', {
            style: 'currency',
            currency: default_currency
        }).format(total * fx_rate) + ")")
}
