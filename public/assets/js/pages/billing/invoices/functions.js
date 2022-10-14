let parent_data_src = $('#kt_aside')
let default_currency = parent_data_src.attr('data-kt-default-currency'),
    current_currency, fx_rate,
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    })

function addFxField() {
    $('.select_customer').on('select2:select', function () {
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
                            $('#fx_area').html(
                                '<!--begin::Input group-->\n' +
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
                                '        class="col-lg-5 col-form-label fw-semibold fs-7" id="label_fx"></label>\n' +
                                '    <!--end::Label-->\n' +
                                '</div>\n' +
                                '<!--end::Input group-->'
                            );
                        }
                        fx_rate = response.data.fx_rate
                        $('[name="fx_rate"]').val(fx_rate)
                        $('#label_fx').html(default_currency + " = 1 " + current_currency)
                        $('#total').after('<div class="text-end my-5 text-muted" id="total_converted">' + "(Total: " + new Intl.NumberFormat('ja-JP', {
                            style: 'currency',
                            currency: default_currency
                        }).format(total * fx_rate) + ")" + '</div>')

                        $('[name="fx_rate"]').on('keyup', function () {
                            if (!isNaN($(this).val()) && $(this).val().length !== 0) {
                                fx_rate = $(this).val()
                                totalHomeCurrency()
                            } else {
                                fx_rate = 1
                                totalHomeCurrency()
                            }
                        })
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
            if ($('#fx_input').length) {
                $('#fx_input').remove()
            }

        }
    })
}
