// "use strict";
//
// // Class definition
// const KTPackagesUpdate = function () {
//     // Shared variables
//     let submitButton, cancelButton, closeButton, form, modal, CKEditor;
//
//     function createCKEditor() {
//         ClassicEditor
//             .create(document.querySelector('#kt_modal_update_package #kt_docs_ckeditor_classic'))
//             .then(editor => {
//                 editor.setData(value);
//                 CKEditor = editor.getData();
//             })
//             .catch(error => {
//             });
//     }
//
//     return {
//         init: function () {
//             modal = new bootstrap.Modal(document.querySelector('#kt_modal_update_package'));
//             form = document.querySelector('#kt_modal_update_package_form');
//             cancelButton = form.querySelector('#kt_modal_update_package_cancel');
//             submitButton = form.querySelector('#kt_modal_update_package_submit');
//             closeButton = document.querySelector('#kt_modal_update_package_close');
//
//             handleFormSubmit(
//                 form,
//                 {
//                     name: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Name is required'
//                             }
//                         }
//                     },
//                     features: {
//                         validators: {}
//                     },
//                     description: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Description is required'
//                             }
//                         }
//                     },
//                     validity: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Validity is required'
//                             }
//                         }
//                     },
//                     product_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Product is required'
//                             }
//                         }
//                     },
//                     order: {
//                         validators: {}
//                     }
//                 },
//                 $('#kt_modal_update_package_form'),
//                 cancelButton,
//                 closeButton,
//                 submitButton,
//                 'PUT',
//                 modal,
//                 $('#kt_packages_table'),
//                 ["product_id", "order"],
//                 [true, CKEditor]
//             );
//             createCKEditor()
//         }
//     }
// }();
//
// // On document ready
// KTUtil.onDOMContentLoaded(function () {
//     KTPackagesUpdate.init();
// });
