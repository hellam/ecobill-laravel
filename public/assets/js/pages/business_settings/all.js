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
            let submit_url = $(this).attr('data-kt-action')
        })
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
