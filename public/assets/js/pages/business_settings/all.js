"use strict";

// Class definition
const KTBusinessSettingsAll = function () {
    let base_url = "business-settings/view/";
    let tab = 'general';
    let loader_container = $('#loader_container');
    let action_url;
    //handle form
    const handleShowResults = function () {
        $('#kt_update_setting_form').attr('data-kt-action', location.href + '/view/' + tab)
        getView(tab)
    }

    function getView(tab) {
        $.ajax({
            type: 'GET',
            url: base_url + tab,
            success: function (json) {
                const response = JSON.parse(JSON.stringify(json));
                loader_container.addClass('d-none')
                loader_container.after(response)
                $('#kt_update_setting_form').find('select').select2({
                    // matcher: matchCustom
                })
                handleSubmit();
            },
            error: function () {
                loader_container.addClass('d-none')
                loader_container.after('<div class="view_data">Something went wrong! Please try again!</div>')
            }
        });
    }

    function handleTabClick() {
        const tabButtons = document.querySelectorAll('[data-kt-tab-action="general"],[data-kt-tab-action="sms"],[data-kt-tab-action="gl_setup"],[data-kt-tab-action="email"]');
        tabButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                loader_container.removeClass('d-none')
                $('.view_data').remove()
                tab = $(this).attr("data-kt-tab-action")
                getView(tab)
                $('#kt_update_setting_form').attr('data-kt-action', location.href + '/view/' + tab)
            })
        });
    }

    function handleSubmit() {
        document.getElementById('btn_save').addEventListener('click', function (e) {
            e.preventDefault()
            let submit_url = $('#kt_update_setting_form').attr('data-kt-action');
            let str = $('#kt_update_setting_form').serialize()
            handleForm(str, submit_url)
        })
    }

    function handleForm(str, submit_url) {
        let submitButton = document.querySelector('#btn_save');
        submitButton.setAttribute('data-kt-indicator', 'on');

        if ($('#actual_imageInput').length && $('#actual_imageInput').val() !== '') {
            // str = str + '&logo=' + $('#actual_imageInput').val()
            str = $("#kt_update_setting_form").find("input[name!=actual_imageInput]").serialize();
            str = str + '&logo=' + $('#actual_imageInput').val()
        }
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
                        let input = "input[name='" + value.field + "']",
                            textarea = "textarea[name='" + value.field + "']",
                            select = "select[name='" + value.field + "']";
                        if ($(input).length) {
                            $(input)
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('keyup', function (e) {
                                    $('#err_' + value.field).remove();
                                })
                        } else if ($(textarea).length) {
                            $(textarea)
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('keyup', function (e) {
                                    $('#err_' + value.field).remove();
                                })
                        } else if ($(select).length) {
                            $(select).closest('.fv-row')
                                .after('<small style="color: red;" id="err_' + value.field + '">' + value.error + '</small>')
                                .on('change', function (e) {
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
                    })

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
                            if (tab === 'general') {
                                loader_container.removeClass('d-none')
                                $('.view_data').remove()
                                getView(tab)
                            }
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

    // function matchCustom(params, data) {
    //     // If there are no search terms, return all of the data
    //     if ($.trim(params.term) === '') {
    //         return data;
    //     }
    //
    //     console.log(data)
    //
    //     // Do not display the item if there is no 'id' property
    //     if (typeof data.id === 'undefined') {
    //         return null;
    //     }
    //
    //     // `params.term` should be the term that is used for searching
    //     // `data.text` is the text that is displayed for the data object
    //     if (data.text.indexOf(params.term) > -1) {
    //         let modifiedData = $.extend({}, data, true);
    //         modifiedData.text;
    //
    //         // You can return modified objects from here
    //         // This includes matching the `children` how you want in nested data sets
    //         return modifiedData;
    //     }
    //
    //     // Return `null` if the term should not be displayed
    //     return null;
    // }

    // Public methods
    return {
        init: function () {
            handleShowResults();
            handleTabClick();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBusinessSettingsAll.init();
});
