let parent_data_src = $('#kt_aside')
let default_currency = parent_data_src.attr('data-kt-default-currency'),
    current_currency,
    loader_image = parent_data_src.attr('data-kt-loader'),
    blockUI = new KTBlockUI(document.querySelector('#kt_block_ui_1_target'), {
        message: '<div class="blockui-message"><img src="' + loader_image + '" width="30" height="30" alt=""></div>',
    });

function addFxField() {
    $('.select_customer').on('select2:select', function () {
        current_currency = $(this).find(':selected').attr('data-kt-currency')
        console.log(current_currency, default_currency)
        if (default_currency !== current_currency) {
            blockUI.block()
        }else{

        }
    })
}
