"use strict";

// Class definition
const KTMakerCheckerRulesServerSide = function () {
    // Shared variables
    let table;
    let dt;
    let submitButton;
    let cancelButton;
    let closeButton;
    let validator;
    let form;
    let modal;

    // Private functions
    const initDatatable = function () {
        let td = document.querySelector('#kt_maker_checker_rules_table')
        dt = $("#kt_maker_checker_rules_table").DataTable({
            // searchDelay: 500,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'maker_type'},
                {data: 'permission_code'},
                {data: 'created_by'},
                {data: 'created_at'},
                {data: 'actions'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        var response = row.id;
                        return `
                            <div>
                                <p>${response.id}</p>
                                <input type="hidden" class="edit_url" value="${response.edit_url}" />
                                <input type="hidden" class="update_url" value="${response.update_url}" />
                                <input type="hidden" class="delete_url" value="${response.delete_url}" />
                            </div>`;
                    }
                },{
                    targets: -1,
                    data: 'action',
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-rule-table-filter="edit_row">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-rule-table-filter="delete_row">
                                        Delete
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        `;
                    },
                },
            ],
            // Add data-filter attribute
            createdRow: function (row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            }
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            KTMenu.createInstances();
        });
    };
    //handle form
    const handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    action: {
                        validators: {
                            notEmpty: {
                                message: "Permission is required"
                            }
                        }
                    },
                    maker_type: {
                        validators: {
                            notEmpty: {
                                message: "Type is required"
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }
        );


        // Action buttons
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        var str = $('#kt_modal_add_rule_form').serialize();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: form.getAttribute("data-kt-action"),
                            data: str,
                            success: function (json) {
                                var response = JSON.parse(JSON.stringify(json));
                                if (response.status !== true) {
                                    var errors = response.data;
                                    for (const [key, value] of Object.entries(errors)) {
                                        // console.log(value)
                                        $('#err_' + value.field).remove();
                                        if ("select[name='" + value.field + "']") {
                                            $("#action")
                                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                                .on('change', function (e) {
                                                    $('#err_' + value.field).remove();
                                                })
                                        }
                                        if (value.field === 'maker_type') {
                                            $('#maker_type').after('<small style="color: red;" id="err_maker_type">' + value.error + '</small>')
                                                .on('keyup', function (e) {
                                                    $('#err_maker_type').remove();
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
                                            modal.hide();
                                            $("#permissions_select").val(null).trigger('change');
                                            $("#maker_type1").prop("checked", true);

                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                            if ($('#kt_maker_checker_rules_table').length) {
                                                $("#kt_maker_checker_rules_table").DataTable().ajax.reload();
                                                return;
                                            }
                                            // Redirect to Taxes list page
                                            window.location = form.getAttribute("data-kt-redirect");
                                        }
                                    });
                                }
                                submitButton.removeAttribute('data-kt-indicator');

                                // Enable submit button after loading
                                submitButton.disabled = false;

                            },
                            error: function (xhr, desc, err) {
                                console.log(xhr)
                                Swal.fire({
                                    text: 'A network error occured. Please consult your network administrator.',
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
                });
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
                    modal.hide(); // Hide modal
                }
            });
        })

    }

    //Edit Button
    const handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-rule-table-filter="edit_row"]');

        // Make the DIV element draggable:
        var element = document.querySelector('#kt_modal_update_rule');
        dragElement(element);
        editButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_rule_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_rule").modal('show');//show modal
                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const update_url = parent.querySelector("input[class='update_url']").value;
                const edit_url = parent.querySelector("input[class='edit_url']").value;
                form.setAttribute("data-kt-action", update_url);
                // console.log(form.getAttribute("data-kt-action"))
                $.ajax({
                    type: 'GET',
                    url: edit_url,
                    success: function (json) {
                        var response = JSON.parse(json);
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

                            $('#kt_modal_update_rule_form').show({backdrop: 'static', keyboard: false});//show form
                            var product = JSON.parse(response.data);

                            // $("#kt_modal_update_product_form input[name='barcode']").val(product.barcode);
                            // $("#kt_modal_update_product_form input[name='name']").val(product.name);
                            // $("#kt_modal_update_product_form input[name='cost']").val(product.cost);
                            // $("#kt_modal_update_product_form input[name='price']").val(product.price);
                            // $("#kt_modal_update_product_form select[name='order']").val(product.order).trigger('change');
                            // $("#kt_modal_update_product_form select[name='taxId']").val(product.taxId).trigger('change');
                            // $("#kt_modal_update_product_form textarea[name='description']").val(product.description);
                            // $("#kt_modal_update_product_form input[name='inactive']").val(product.inactive)
                            //
                            // $("#kt_modal_update_product_form select[name='categoryId']").html('<option value="' + product.categories.id + '">' + product.categories.name + '</option>')
                            // $("#kt_modal_update_product_form select[name='categoryId']").trigger('change')
                            //
                            // if (product.inactive !== 1) {
                            //     $("#kt_modal_update_product_form input[id='inactive']").attr("checked", "checked");
                            // } else {
                            //     $("#kt_modal_update_product_form input[id='inactive']").removeAttr("checked")
                            // }
                            //
                            // //active/inactive
                            // $("#kt_modal_update_product_form input[id='inactive']").on('change', function () {
                            //     if ($(this).is(':checked')) {
                            //         $("#kt_modal_update_product_form input[name='inactive']").val(0)
                            //     } else {
                            //         $("#kt_modal_update_product_form input[name='inactive']").val(1)
                            //     }
                            // })
                        }

                        $('.loader_container').hide();//hide loader

                    },
                    error: function (xhr, desc, err) {
                        Swal.fire({
                            text: 'A network error occured. Please consult your network administrator.',
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


            })
        });
    };

    // const start = moment();
    // const end = moment();
    //
    // function cb(start, end) {
    //     $("#kt_date_range_picker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    // }
    //
    // $("#kt_date_range_picker").daterangepicker({
    //     startDate: start,
    //     endDate: end,
    //     ranges: {
    //         "Today": [moment(), moment()],
    //         "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
    //         "Last 7 Days": [moment().subtract(6, "days"), moment()],
    //         "Last 30 Days": [moment().subtract(29, "days"), moment()],
    //         "This Month": [moment().startOf("month"), moment().endOf("month")],
    //         "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
    //     }
    // }, cb);

    // Filter Datatable
    // var handleFilterDatatable = () => {
    //     // Select filter options
    //     filterPayment = document.querySelectorAll('[data-kt-tax-table-filter="payment_type"] [name="payment_type"]');
    //     const filterButton = document.querySelector('[data-kt-tax-table-filter="filter"]');
    //
    //     // Filter datatable on submit
    //     filterButton.addEventListener('click', function () {
    //         // Get filter values
    //         let paymentValue = '';
    //
    //         // Get payment value
    //         filterPayment.forEach(r => {
    //             if (r.checked) {
    //                 paymentValue = r.value;
    //             }
    //
    //             // Reset payment value if "All" is selected
    //             if (paymentValue === 'all') {
    //                 paymentValue = '';
    //             }
    //         });
    //
    //         // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
    //         dt.search(paymentValue).draw();
    //     });
    // }

    // Public methods
    return {
        init: function () {

            if ($('#kt_maker_checker_rules_table').length) {
                initDatatable();
                dt.search('').draw();
                // handleSearchDatatable();
                // handleFilterDatatable();
            }

            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_rule'));

            form = document.querySelector('#kt_modal_add_rule_form');
            submitButton = form.querySelector('#kt_modal_add_rule_submit');
            cancelButton = form.querySelector('#kt_modal_add_rule_cancel');
            closeButton = document.querySelector('#kt_modal_add_rule_close');

            handleForm();
            handleUpdateRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTMakerCheckerRulesServerSide.init();
});
