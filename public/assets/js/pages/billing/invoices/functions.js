let parent_data_src = $('#kt_aside')
let default_currency = current_currency = parent_data_src.attr('data-kt-default-currency'),
    fx_rate,
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    })

function addFxField() {
    $('.select_customer').on('select2:select', function () {
        if ($('.select_bank').length) {
            $('.select_bank').val(null).trigger('change')
        }
        let pay_term = $(this).find(':selected').attr('data-kt-pay-terms')
        $('.select_terms').val(pay_term).trigger('change')
        current_currency = $(this).find(':selected').attr('data-kt-currency')
        if (default_currency !== current_currency) {
            blockUI.block()
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
                                '               name="fx_rate"/>\n' +
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
                        // $('#total').after('<div class="text-end my-5 text-muted" id="total_converted">' + "(Total: " + new Intl.NumberFormat('ja-JP', {
                        //     style: 'currency',
                        //     currency: default_currency
                        // }).format(total * fx_rate) + ")" + '</div>')

                        // $('[name="fx_rate"]').on('keyup', function () {
                        //     if (!isNaN($(this).val()) && $(this).val().length !== 0) {
                        //         fx_rate = $(this).val()
                        //         totalHomeCurrency()
                        //     } else {
                        //         fx_rate = 1
                        //         totalHomeCurrency()
                        //     }
                        // })
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
            if ($('#fx_parent').length) {
                $('#fx_area').removeClass('order-first')
                $('#fx_parent').remove()
            }
            if ($('#bank_parent').length) {
                $('#bank_area').addClass('order-first')
            }
        }
    })
}

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
