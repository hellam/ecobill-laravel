/**
 * handle row delete for datatable
 * @param deleteButtons
 * @param delete_url
 * @param dt
 */
function handleDeleteRows(deleteButtons, delete_url, dt = null) {
    deleteButtons = document.querySelectorAll(deleteButtons)
    deleteButtons.forEach(d => {
        // edit button on click
        d.addEventListener('click', function (e) {
            e.preventDefault();

            // Select parent row
            const parent = e.target.closest('tr');

            // Get rule name
            const name = parent.querySelectorAll('td')[1].innerText;
            let delete_uri = d.getAttribute(delete_url) ?? parent.querySelector(delete_url).value;
            Swal.fire({
                text: "Are you sure you want to delete " + name,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting " + name,
                        icon: "info",
                        allowOutsideClick: false,
                        buttonsStyling: false,
                        showConfirmButton: false,
                    })
                    handleDelete(delete_uri, '', dt)

                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: name + " was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        })
    });
}

/**
 * perform row delete
 * @param delete_uri
 * @param remarks
 * @param dt
 */
function handleDelete(delete_uri, remarks, dt) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'DELETE',
        url: delete_uri,
        data: {
            remarks: remarks
        },
        success: function (json) {
            var response = json;
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
                Swal.fire({
                    text: response.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                }).then(function () {
                    // delete row data from server and re-draw datatable
                    if (dt !== null)
                        dt.draw()
                    else
                        window.location.reload()
                });
            }
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
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    // delete row data from server and re-draw datatable
                    if (result.isConfirmed) {
                        handleDelete(delete_uri, result.value, dt)
                    }
                });
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
    });
}

/**
 * handle datatable search
 * @param input
 * @param dt
 */
function handleSearchDatatable(input, dt) {
    const filterSearch = document.querySelector(input);
    filterSearch.addEventListener('keyup', function (e) {
        dt.search(e.target.value).draw();
    });
}

/**
 * initialize form
 * @param form
 * @param fields
 * @param form_jquery
 * @param cancelButton
 * @param closeButton
 * @param submitButton
 * @param method
 * @param modal
 * @param table
 * @param select_fields
 * @param ckeditor
 */
function handleFormSubmit(form, fields, form_jquery, cancelButton, closeButton, submitButton, method, modal = null, table = null, select_fields = null, ckeditor = null) {
    let validator = FormValidation.formValidation(
        form,
        {
            fields: fields,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                }),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            }
        }
    );

    if (select_fields !== null) {
        select_fields.forEach(select => {
            $(form.querySelector(`[name=${select}]`)).on('change', function () {
                // Revalidate the field when an option is chosen
                validator.revalidateField(`${select}`);
            });
        })
    }

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Validate form before submit
        if (validator) {
            validator.validate().then(function (status) {

                if (status === 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    let str;
                    if (ckeditor !== null)
                        str = form_jquery.serialize() + "&features=" + CKEDITOR.instances[ckeditor].getData()
                    else
                        str = form_jquery.serialize();
                    submitFormData(str, form, modal, submitButton, table, select_fields, method, ckeditor);
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
    });


    cancelButton.addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            text: "Are you sure you would like to cancel?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function (result) {
            if (result.value) {
                form.reset(); // Reset form
                if (select_fields !== null)
                    select_fields.forEach(select => {
                        $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                    })
                if (modal !== null)
                    modal.hide(); // Hide modal
            }
        });
    });

    closeButton.addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            text: "Are you sure you would like to cancel?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function (result) {
            if (result.value) {
                form.reset(); // Reset form
                if (select_fields !== null)
                    select_fields.forEach(select => {
                        $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                    })
                if (modal !== null)
                    modal.hide(); // Hide modal
            }
        });
    });
}

/**
 * perform submit of form data
 * @param str
 * @param form
 * @param modal
 * @param submitButton
 * @param table
 * @param select_fields
 * @param method
 * @param ckeditor
 */
function submitFormData(str, form, modal, submitButton, table, select_fields, method, ckeditor) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: method,
        url: form.getAttribute("data-kt-action"),
        data: str,
        success: function (json) {
            var response = JSON.parse(JSON.stringify(json));
            if (response.status !== true) {
                var errors = response.data;
                for (const [key, value] of Object.entries(errors)) {
                    // var field = fields[i];
                    // console.log(field.field);
                    $('#err_' + value.field).remove();
                    if ($("input[name='" + value.field + "']").length) {
                        $("input[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }
                    if ($("select[name='" + value.field + "']").length) {
                        $("select[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('change', function (e) {
                                $('#err_' + value.field).remove();
                            })
                    }
                    if ($("textarea[name='" + value.field + "']").length) {
                        $("textarea[name='" + value.field + "']")
                            .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                            .on('keyup', function (e) {
                                $('#err_' + value.field).remove();
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
                        // Hide modal
                        if (modal !== null)
                            modal.hide();
                        if (ckeditor !== null)
                            CKEDITOR.instances[ckeditor].setData()
                        // Enable submit button after loading
                        submitButton.disabled = false;
                        form.reset(); // Reset form
                        if (select_fields !== null)
                            select_fields.forEach(select => {
                                $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                            })

                        if (table !== null && table.length) {
                            table.DataTable().ajax.reload();
                            return;
                        }

                        window.location = form.getAttribute("data-kt-redirect");
                    }
                });
            }
            submitButton.removeAttribute('data-kt-indicator');

            // Enable submit button after loading
            submitButton.disabled = false;

        },
        statusCode: {
            203: function () {
                modal.hide()//hide modal
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
                        //data.add('remarks', result.value);
                        // alert(result.value)
                        if (modal !== null)
                            modal.show()//show modal
                        // console.log(str)
                        // if (result.value)
                        str = str + "&remarks=" + result.value
                        submitFormData(str, form, modal, submitButton, table, select_fields, method, ckeditor);
                    } else {
                        form.reset(); // Reset form
                        if (ckeditor !== null)
                            CKEDITOR.instances[ckeditor].setData()
                        if (select_fields !== null)
                            select_fields.forEach(select => {
                                $(form.querySelector(`[name=${select}]`)).val(null).trigger('change');
                            })
                    }
                });
            }
        },
        error: function () {
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
 * product category
 * @param select_parent
 * @param preselect
 */
function handleCategoryAPISelect(select_parent, preselect = null) {
    const element = document.querySelector(select_parent + ' .select_cat');

    $(select_parent + ' .select_cat').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.name, preselect?.id, true, true);
        $(select_parent + ' .select_cat').append(option).trigger('change');
    }

    $(select_parent + ' .select_cat').select2({
        placeholder: 'Select Category',
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
                            text: item.name,
                            id: item.id,
                            'data-default-tax-id': item.default_tax_id,
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-default-tax-id': data["data-default-tax-id"], //dynamic value from data array
            }
        );
        $(select_parent + ' .tax_id').val($('.select_cat').find(':selected').data('default-tax-id')).trigger('change')
    })
}

/**
 * products select2
 * @param select_parent
 * @param preselect
 */
function handleProductsAPISelect(select_parent, preselect = null) {
    const element = document.querySelector(select_parent + ' .select_api');

    $(select_parent + ' .select_api').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.barcode + "|" + preselect?.name, preselect?.barcode, true, true);
        $(select_parent + ' .select_api').append(option).trigger('change');
    }

    $(select_parent + ' .select_api').select2({
        placeholder: 'Select Product',
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
                            text: item.barcode + ' | ' + item.name,
                            id: item.barcode,
                            cost: item.cost,
                            price: item.price,
                            tax_id: item.tax_id,
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-kt-cost': data["cost"],
                'data-kt-price': data["price"],
                'data-kt-tax': data["tax_id"],
            }
        );
        $(select_parent + ' [name="cost"]').val($(select_parent + ' .select_api').find(':selected').data('kt-cost'))
        $(select_parent + ' [name="price"]').val($(select_parent + ' .select_api').find(':selected').data('kt-price'))
    })
}

/**
 * handle CKEditor
 * @param input_id
 */
function createCKEditor(input_id) {
    CKEDITOR.replace(input_id);
}

/**
 * format select option for customer
 * @param item
 * @returns {*|jQuery|HTMLElement}
 */
const optionFormat = (item) => {
    if (!item.id) {
        return item.text;
    }
    /**
     * credit limit color initialization with no value
     */
    let credit_limit_color;

    /**
     * credit limit format with item currency
     */
    let credit_limit = new Intl.NumberFormat('ja-JP', {
        style: "currency",
        currency: item.currency
    }).format(item.credit_limit)

    /**
     * assign credit limit color
     */
    if (item.credit_limit >= 0)
        credit_limit_color = "text-success"
    else credit_limit_color = "text-primary"

    /**
     * set tax visible or not
     */
    let tax_view = item.tax_rate != 0 ? '| Tax: ' + item.tax_rate + '%' : ''

    /**
     * create new span element and define element
     */
    let span = document.createElement('span');
    let template = '';

    /**
     * add to template
     */
    template += '<div class="d-flex align-items-center">';
    template += '<div class="d-flex flex-column">'
    template += '<span class="">' + item.text + '</span>';
    template += '<span class="text-muted">' +
        item.currency + ' | Discount: ' + item.discount + '% | Credit Limit: <span class="' + credit_limit_color + '">' +
        credit_limit + '</span>' + tax_view + '</span>';
    template += '</div>';
    template += '</div>';

    /**
     * assign template to span
     */
    span.innerHTML = template;

    return $(span);
}

/**
 * handle customer select2
 * @param select_parent
 * @param preselect
 */
function handleCustomerAPISelect(select_parent, preselect = null) {
    const element = document.querySelector(select_parent + ' .select_customer');

    $(select_parent + ' .select_customer').html("").trigger('change');
    if (preselect) {
        const option = new Option(preselect?.short_name + "|" + preselect?.f_name + " " + preselect?.l_name, preselect?.id, true, true);
        $(select_parent + ' .select_customer').append(option).trigger('change');
    }

    $(select_parent + ' .select_customer').select2({
        placeholder: 'Select Customer',
        minimumInputLength: 0,
        escapeMarkup: function (markup) {
            return markup;
        },
        templateSelection: optionFormat,
        templateResult: optionFormat,
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
                            text: item.short_name + '|' + item.f_name + ' ' + item.l_name,
                            id: item.id,
                            phone: item.phone,
                            email: item.email,
                            address: item.address,
                            country: item.country,
                            currency: item.currency ?? item.customer_branch.currency,
                            discount: item?.customer?.discount ?? 0,
                            credit_limit: item.c_limit ?? 0,
                            tax_rate: item?.customer?.tax?.rate ?? 0,
                            tax_id: item?.customer?.tax_id,
                            tax_name: item?.customer?.tax?.name ?? 0,
                            pay_terms: item?.customer?.payment_terms,
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-kt-phone': data["phone"],
                'data-kt-email': data["email"],
                'data-kt-address': data["address"],
                'data-kt-country': data["country"],
                'data-kt-currency': data["currency"],
                'data-kt-discount': data["discount"],
                'data-kt-credit-limit': data["credit_limit"],
                'data-kt-tax-rate': data["tax_rate"],
                'data-kt-tax-name': data["tax_name"],
                'data-kt-tax-id': data["tax_id"],
                'data-kt-pay-terms': data["pay_terms"],
            }
        );
        $(select_parent + ' [name="phone"]').val($(select_parent + ' .select_customer').find(':selected').data('kt-phone')).trigger('change')
        $(select_parent + ' [name="email"]').val($(select_parent + ' .select_customer').find(':selected').data('kt-email')).trigger('change')
        $(select_parent + ' [name="address"]').val($(select_parent + ' .select_customer').find(':selected').data('kt-address')).trigger('change')
    })
}

/**
 * Customer select2 with alternative
 * @param select_parent
 */
function handleCustomerAPISelect2(select_parent) {
    const element = document.querySelector(select_parent + ' .select_customer');

    $(select_parent + ' .select_customer').select2({
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
                            text: item.short_name + '|' + item.f_name + ' ' + item.l_name,
                            id: item.id,
                            phone: item.phone,
                            email: item.email,
                            address: item.address,
                            country: item.country,
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-kt-phone': data["phone"],
                'data-kt-email': data["email"],
                'data-kt-address': data["address"],
                'data-kt-country': data["country"],
            }
        );
        $(select_parent + ' [name="phone"]').val($(select_parent + ' .select_customer').find(':selected').data('kt-phone')).trigger('change')
        $(select_parent + ' [name="email"]').val($(select_parent + ' .select_customer').find(':selected').data('kt-email')).trigger('change')
    })
}

/**
 * generate reference
 * @param url
 */
function refGen(url) {
    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            if (response.status === true) {
                $('[name="reference"]').val(response.data.ref_no)
            }
        },
        error: function () {
        },
    })
}

/**
 * round off float
 * @param num
 * @param dec
 * @returns {number}
 */
function roundFloat(num, dec) {
    let d = 1;
    for (let i = 0; i < dec; i++) {
        d += "0";
    }
    return Math.round(num * d) / d;
}

function handleBankAccountAPISelect(currency = null, preselect = null) {
    $('.select_bank').html("").trigger('change');
    const element = document.querySelector('.select_bank');

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
                    currency: currency
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.account_name + ' - ' + item.currency,
                            id: item.id,
                            'currency': item.currency
                        }
                    })
                }
            }
        }
    }).on('select2:select', function (e) {
        let data = e.params.data;
        $(this).children('[value="' + data['id'] + '"]').attr(
            {
                'data-kt-currency': data["currency"],
            }
        );
    })
}

/**
 * format currency
 */
function formatCurrency(currency, form, amount) {
    return new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
        currency: currency
    }).format(amount)
}

/**
 * format amount only
 */
function formatAmountOnly(form, amount) {
    return new Intl.NumberFormat('ja-JP', {
        maximumFractionDigits: form.attr('data-kt-decimals'),
        minimumFractionDigits: form.attr('data-kt-decimals'),
    }).format(amount)
}
