"use strict";

// Class definition
var MUBGroupsDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;
    var form;

    // Private functions
    var initDatatable = function () {
        let td = document.querySelector('#kt_categories_table')
        dt = $("#kt_categories_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[1, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: td.getAttribute('data-kt-dt_api'),
            },
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'description'},
                {data: 'tax.name'},
                {data: 'inactive'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        var response = JSON.parse(row.data);
                        return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="${response.id}" />
                                <input type="hidden" class="edit_url" value="${response.edit_url}" />
                                <input type="hidden" class="update_url" value="${response.update_url}" />
                                <input type="hidden" class="delete_url" value="${response.delete_url}" />
                            </div>`;
                    }
                }, {
                    targets: 2,
                    orderable: true,
                    render: function (data, row) {
                        return decodeHtml(data);
                    }
                }, {
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
                                    <a href="#" class="menu-link px-3" data-kt-category-table-filter="edit_row">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-category-table-filter="delete_row">
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
            initToggleToolbar();
            toggleToolbars();
            handleDeleteRows();
            handleUpdateRows();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-category-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        filterPayment = document.querySelectorAll('[data-kt-category-table-filter="payment_type"] [name="payment_type"]');
        const filterButton = document.querySelector('[data-kt-category-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Get filter values
            let paymentValue = '';

            // Get payment value
            filterPayment.forEach(r => {
                if (r.checked) {
                    paymentValue = r.value;
                }

                // Reset payment value if "All" is selected
                if (paymentValue === 'all') {
                    paymentValue = '';
                }
            });

            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            dt.search(paymentValue).draw();
        });
    }

    // Delete customer
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-category-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const categoryName = parent.querySelectorAll('td')[1].innerText;
                const delete_url = parent.querySelector("input[class='delete_url']").value;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + categoryName + "? This is not reversible!",
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
                            text: "Deleting " + categoryName,
                            icon: "info",
                            allowOutsideClick: false,
                            buttonsStyling: false,
                            showConfirmButton: false,
                        })
                        $.ajax({
                            type: 'DELETE',
                            url: delete_url,
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
                                    Swal.fire({
                                        text: "You have deleted " + categoryName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        // delete row data from server and re-draw datatable
                                        dt.draw();
                                    });
                                }

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
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: categoryName + " was not deleted.",
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

    // Reset Filter
    var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector('[data-kt-category-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Reset payment type
            filterPayment[0].checked = true;

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            dt.search('').draw();
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        const container = document.querySelector('#kt_categories_table');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');

        // Select elements
        const deleteSelected = document.querySelector('[data-kt-category-table-select="delete_selected"]');

        // Toggle delete selected toolbar
        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        deleteSelected.addEventListener('click', function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Are you sure you want to delete selected customers?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                },
            }).then(function (result) {
                if (result.value) {
                    // Simulate delete request -- for demo purpose only
                    Swal.fire({
                        text: "Deleting selected customers",
                        icon: "info",
                        buttonsStyling: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function () {
                        Swal.fire({
                            text: "You have deleted all selected customers!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // delete row data from server and re-draw datatable
                            dt.draw();
                        });

                        // Remove header checked box
                        const headerCheckbox = container.querySelectorAll('[type="checkbox"]')[0];
                        headerCheckbox.checked = false;
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Selected customers was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }

    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const container = document.querySelector('#kt_categories_table');
        const toolbarBase = document.querySelector('[data-kt-category-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-category-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-category-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    //Edit Button
    var handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-category-table-filter="edit_row"]');

        // Make the DIV element draggable:
        var element = document.querySelector('#kt_modal_update_category');
        dragElement(element);
        editButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_category_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_category").modal('show');//show modal
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

                            $('#kt_modal_update_category_form').show({backdrop: 'static', keyboard: false});//show form
                            var category = JSON.parse(response.data);

                            $("#kt_modal_update_category_form input[name='name']").val(category.name);
                            $("#kt_modal_update_category_form select[name='defaultTaxId']").val(category.defaultTaxId).trigger('change');
                            $("#kt_modal_update_category_form textarea[name='description']").val(category.description);
                            $("#kt_modal_update_category_form input[name='inactive']").val(category.inactive)

                            if (category.inactive !== 1) {
                                $("#kt_modal_update_category_form input[id='inactive']").attr("checked", "checked");
                            } else {
                                $("#kt_modal_update_category_form input[id='inactive']").removeAttr("checked")
                            }

                            //active/inactive
                            $("#kt_modal_update_category_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked')) {
                                    $("#kt_modal_update_category_form input[name='inactive']").val(0)
                                } else {
                                    $("#kt_modal_update_category_form input[name='inactive']").val(1)
                                }
                            })
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

                    }
                });


            })
        });
    }

    //Edit Button
    // const handleSelectContact = function () {
    //     const modal = document.querySelector('#kt_modal_add_category');
    //     dragElement(modal);
    //     const element = document.querySelector('.select_tax');
    //
    //     $('.select_tax').html("").trigger('change');
    //
    //
    //     $('.select_tax').select2({
    //         placeholder: 'Select a tax',
    //         // minimumInputLength: 1,
    //         multiple: true,
    //         escapeMarkup: function (markup) {
    //             return markup;
    //         },
    //         ajax: {
    //             url: element.getAttribute("data-kt-src"),
    //             dataType: 'json',
    //             type: 'GET',
    //             delay: 50,
    //             data: function (params) {
    //                 // Query parameters will be ?search=[term]&type=public
    //                 return {
    //                     search: params.term,
    //                     type: 'public'
    //                 };
    //             },
    //             processResults: function (data) {
    //
    //                 return {
    //                     results: $.map(data, function (item) {
    //                         return {
    //                             text: item.f_name + ' ' + item.l_name,
    //                             id: item.id,
    //                         }
    //                     })
    //                 }
    //             }
    //         }
    //     });
    // };


    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_category_form');

            if ($('#kt_categories_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable();
                initToggleToolbar();
                handleFilterDatatable();
                handleDeleteRows();
                handleUpdateRows();
                handleResetForm();
            }
            // handleSelectContact();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    MUBGroupsDatatablesServerSide.init();
});
