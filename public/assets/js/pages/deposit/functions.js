let loader_image = $('#rates_area').attr('data-kt-loader')
let blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
    message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
})

let default_currency = $('[name="currency"]').attr('data-kt-default')
let current_currency = $('[name="currency"]').attr('data-kt-default')

$('[name="currency"]').on('change', function (e) {
    current_currency = e.target.value
    $('.select_bank').val(null).trigger('change')
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
        addFXField()
    })
}

function addFXField() {
    if (current_currency !== default_currency) {
        blockUI.block()
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('.select_bank').attr('data-kt-fx-url'),
            type: 'POST',
            data: {
                from: current_currency,
                to: default_currency
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
                }else{
                    blockUI.release()
                    blockUI.destroy()
                    if ($('#current_to_default').length) {
                    } else {
                        $('#rates_area').append(
                            '<div class="col-md-4" id="current_to_default">' +
                            '<!--begin::Input group-->\n' +
                            '<div class="row mb-6 mx-2 fv-row" id="cust_to_bank">\n' +
                            '    <!--begin::Label-->\n' +
                            '    <label\n' +
                            '        class="col-lg-4 col-form-label fw-semibold fs-7">' + current_currency + " to " + default_currency + '</label>\n' +
                            '    <!--end::Label-->\n' +
                            '    <!--begin::Col-->\n' +
                            '    <div class="col-lg-8">\n' +
                            '        <!--begin::Input-->\n' +
                            '        <input type="text" class="form-control form-control-sm form-control-solid"\n' +
                            '               placeholder="' + current_currency + " to " + default_currency + '"\n' +
                            '               name="name"/>\n' +
                            '        <!--end::Input-->\n' +
                            '    </div>\n' +
                            '    <!--end::Col-->\n' +
                            '</div>\n' +
                            '<!--end::Input group-->' +
                            '</div>'
                        );
                    }
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
        if ($('#current_to_default').length) {
            $('#current_to_default').remove()
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
        addFXField()
    })
}

function handleGLAccountsAPISelect(preselect = null) {
    const element = document.querySelector('.gl_select');

    $('.gl_select').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.account_name + " - " + preselect?.currency, preselect?.id, true, true);
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
                            id: item.id
                        }
                    })
                }
            }
        }
    })
}


