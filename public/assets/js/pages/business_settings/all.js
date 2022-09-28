"use strict";

// Class definition
const KTBusinessSettingsAll = function () {
    //handle form
    const handleShowResults = function () {
        $.ajax({
            type: 'GET',
            url: "business-settings/general_settings",
            success: function (json) {
                const response = JSON.parse(JSON.stringify(json));
                $('#loader_container').addClass('d-none')
                $('#loader_container').after(response)

            },
            error: function () {
                $('#loader_container').addClass('d-none')
                $('#loader_container').after("Something went wrong! Please try again!")

            }
        });
    }

    // Public methods
    return {
        init: function () {
            handleShowResults();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTBusinessSettingsAll.init();
});
