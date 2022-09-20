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
                    orderable: false
                }
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
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTMakerCheckerRulesServerSide.init();
});
