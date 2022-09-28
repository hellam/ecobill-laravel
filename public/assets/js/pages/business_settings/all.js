"use strict";

// Class definition
const KTBusinessSettingsAll = function () {
    //handle form
    const handleShowResults = function () {
        getView("business-settings/general_settings/view/general")
    }

    function getView(url) {
        $.ajax({
            type: 'GET',
            url: url,
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
