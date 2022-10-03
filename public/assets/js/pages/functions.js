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
            delete_url = d.getAttribute(delete_url) ?? parent.querySelector(delete_url).value;
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
                    handleDelete(delete_url, '', dt)

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

function handleDelete(delete_url, remarks, dt) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'DELETE',
        url: delete_url,
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
                        handleDelete(delete_url, result.value)
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

function handleSearchDatatable(input, dt) {
    const filterSearch = document.querySelector(input);
    filterSearch.addEventListener('keyup', function (e) {
        dt.search(e.target.value).draw();
    });
}
