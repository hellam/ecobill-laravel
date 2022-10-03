"use strict";

// Class definition
var MUBDatatablesServerSide = function () {
    // Shared variables
    var dt;
    var form;

    // Private functions
    var initDatatable = function () {
        let td = document.querySelector('#kt_taxes_table')
        dt = $("#kt_taxes_table").DataTable();
    }

    //Edit Button
    var handleUpdateRows = function () {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-tax-table-actions="edit_row"]');

        // Make the DIV element draggable:
        var element = document.querySelector('#kt_modal_update_tax');
        dragElement(element);
        editButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#kt_modal_update_tax_form').hide();//hide form
                $('.loader_container').show();//show loader
                $("#kt_modal_update_tax").modal('show');//show modal
                // Select parent row

                const update_url = d.getAttribute('data-kt-tax-update-url');
                const edit_url = d.getAttribute('data-kt-tax-edit-url');
                form.setAttribute("data-kt-action", update_url);
                // console.log(form.getAttribute("data-kt-action"))
                $.ajax({
                    type: 'GET',
                    url: edit_url,
                    success: function (json) {
                        var response = JSON.parse(JSON.stringify(json));
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
                            $('#kt_modal_update_tax_form').show({backdrop: 'static', keyboard: false});//show form
                            var tax = response.data;
                            $("#kt_modal_update_tax_form input[name='name']").val(tax.name);
                            $("#kt_modal_update_tax_form input[name='rate']").val(tax.rate);
                            $("#kt_modal_update_tax_form textarea[name='description']").val(tax.description);
                            $("#kt_modal_update_tax_form input[name='inactive']").val(tax.inactive)

                            if (tax.inactive !== 1) {
                                $("#kt_modal_update_tax_form input[id='inactive']").attr("checked", "checked");
                            } else {
                                $("#kt_modal_update_tax_form input[id='inactive']").removeAttr("checked")
                            }

                            //active/inactive
                            $("#kt_modal_update_tax_form input[id='inactive']").on('change', function () {
                                if ($(this).is(':checked'))
                                    $("#kt_modal_update_tax_form input[name='inactive']").val(0)
                                else {
                                    $("#kt_modal_update_tax_form input[name='inactive']").val(1)
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

                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable submit button after loading
                        submitButton.disabled = false;

                    }
                });


            })
        });
    }


    // Public methods
    return {
        init: function () {
            form = document.querySelector('#kt_modal_update_tax_form');
            // $('#kt_modal_update_tax').modal()

            if ($('#kt_taxes_table').length) {
                initDatatable();
                dt.search('').draw();
                handleSearchDatatable('[data-kt-tax-table-filter="search"]', dt);
                handleDeleteRows('[data-kt-tax-table-actions="delete_row"]', 'data-kt-tax-delete-url');
                handleUpdateRows();
            }
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    MUBDatatablesServerSide.init();
});
