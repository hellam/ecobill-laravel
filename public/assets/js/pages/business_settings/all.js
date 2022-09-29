"use strict";

// Class definition
const KTBusinessSettingsAll = function () {
    let base_url = "business-settings/view/";
    //handle form
    const handleShowResults = function () {
        $('#kt_update_setting_form').attr('data-kt-action', 'http://localhost/ecobill/public/u/setup/business-settings/view/general')
        getView("general")
    }

    function getView(tab) {
        $.ajax({
            type: 'GET',
            url: base_url + tab,
            success: function (json) {
                const response = JSON.parse(JSON.stringify(json));
                $('#loader_container').addClass('d-none')
                $('#loader_container').after(response)
            },
            error: function () {
                $('#loader_container').addClass('d-none')
                $('#loader_container').after('<div class="view_data">Something went wrong! Please try again!</div>')
            }
        });
    }

    function handleTabClick() {
        const tabButtons = document.querySelectorAll('[data-kt-tab-action="general"],[data-kt-tab-action="sms"],[data-kt-tab-action="email"]');
        tabButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                $('#loader_container').removeClass('d-none')
                $('.view_data').remove()
                getView($(this).attr("data-kt-tab-action"))
                $('#kt_update_setting_form').attr('data-kt-action', 'http://localhost/ecobill/public/u/setup/business-settings/view/' + $(this).attr("data-kt-tab-action"))
            })
        });
    }

    function handleSubmit() {
        $('#kt_update_setting_form').on('submit', function (e) {
            e.preventDefault()
            let submit_url = $(this).attr('data-kt-action');
            let str = $('#kt_update_setting_form').serialize()
            handleForm(str, submit_url)
        })
    }

    function handleForm(str, submit_url) {
        let submitButton = document.querySelector('#btn_save');
        submitButton.setAttribute('data-kt-indicator', 'on');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: submit_url,
            data: str,
            success: function (json) {
                var response = JSON.parse(JSON.stringify(json));
                if (response.status !== true) {
                    var errors = response.data;
                    for (const [key, value] of Object.entries(errors)) {
                        $('#err_' + value.field).remove();
                        if ($("input[name='" + value.field + "']").length) {
                            $("input[name='" + value.field + "']")
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('keyup', function (e) {
                                    $('#err_' + value.field).remove();
                                })
                        } else if ($("textarea[name='" + value.field + "']").length) {
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
                    })
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
                        if (result.isConfirmed) {
                            str = str + "&remarks=" + result.value
                            handleForm(str)
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

                submitButton.disabled = false;
            }
        });
    }

    // Public methods
    return {
        init: function () {
            handleShowResults();
            handleTabClick();
            handleSubmit();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBusinessSettingsAll.init();
});
