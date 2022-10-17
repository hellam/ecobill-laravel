<?php
function input_field($name, $description, $value, $required = false, $type = 'text'): string
{
    return '<div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">' . $description . '</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <input type="' . $type . '" name="' . $name . '" class="form-control form-control-lg form-control-solid" placeholder="' . $description . '" value="' . $value . '" ' . ($required ? "required" : " ") . '>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>';
}

function view_field($description, $value): string
{
    return '<div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">' . $description . '</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <span type="text" class="form-control form-control-lg form-control-solid">' . $value . '</span>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>';
}

function div_start($class)
{
    return '<div class="' . $class . '">';
}

function div_end()
{
    return '</div>';
}

function submit_button($name, $id)
{
    return '<button type="submit" class="btn btn-primary" id="' . $id . '">
                                <span class="indicator-label">' . $name . '</span>
                                <span class="indicator-progress">Please wait...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>';
}

function image_view($name, $id, $default, $value, $is_profile = true)
{

    $output = '<style>
                .avatar-upload {
                  position: relative;
                  margin: 30px auto;
                }
                .avatar-upload .avatar-edit {
                  position: absolute;
                  left: 120px;
                  z-index: 1;
                  top: 10px;
                  text-align: center;
                }
                .avatar-upload button {
                  position: absolute;
                  z-index: 1;
                  top: 100px;
                  text-align: center;
                  border-radius: 100%;
                  border: 0;
                  width: 34px;
                  height: 34px;
                  left: 120px;
                }
                .avatar-upload .avatar-edit i {
                   margin-top: 8px;
                }
                .avatar-upload .avatar-edit input {
                  display: none;
                }
                .avatar-upload .avatar-edit input + label {
                  display: inline-block;
                  width: 34px;
                  height: 34px;
                  margin-bottom: 0;
                  border-radius: 100%;
                  background: #FFFFFF;
                  border: 1px solid transparent;
                  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                  cursor: pointer;
                  font-weight: normal;
                  transition: all 0.2s ease-in-out;
                }
                .avatar-upload .avatar-edit input + label:hover {
                  background: #f1f1f1;
                  border-color: #d6d6d6;
                }

                .avatar-upload .avatar-preview {
                  width: 150px;
                  height: 150px;
                  position: relative;
                  border-radius: 100%;
                  border: 6px solid #F8F8F8;
                  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
                }
                .avatar-upload .avatar-preview > img {
                  width: 100%;
                  height: 100%;
                  border-radius: 100%;
                  background-size: cover;
                  background-repeat: no-repeat;
                  background-position: center;
                }
            </style>
            <div class="avatar-upload">
                <div class="avatar-edit">
                        <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="previewFile()"/>
                        <input readonly type="hidden" id="' . $id . '" name="' . $name . '"/>
                        <label for="imageUpload"><i class="fa fa-pen"></i></label>
                    </div>
                        <button type="button" id="trash"><i class="fa fa-trash"></i></button>
                    <div class="avatar-preview">
                        <img id="imagePreview" src="' . ($value == '' ? '' : asset($value)) . '" onerror="this.src=\'' . asset($default) . '\'">
                </div>
            </div>
            <script>
                document.querySelector("#trash").addEventListener("click", function (){
                    if(document.querySelector("#imagePreview").src !== \'' . asset($default) . '\'){
                        document.querySelector("#imagePreview").src = \'' . asset($default) . '\';
                        $("#' . $id . '").val("")
                    }
                })
                function previewFile() {
                      var preview = document.querySelector("#imagePreview");
                      var file = document.querySelector("#imageUpload").files[0];
                      var reader = new FileReader();

                      reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $("#' . $id . '").val(reader.result)
                      }, false);

                      if (file) {
                        reader.readAsDataURL(file);
                      }
                 }';
    $output .= $is_profile ? 'document.querySelector(".company_logo").src = "' . asset($value) . '"' : '';
    $output .= '</script>';

    return $output;
}

function select($name, $description, $data, $error = '', $default = null): string
{
    $output = '
        <!--begin::Input-->
        <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label required fw-bold fs-6">' . $description . '</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <select name="' . $name . '"
                aria-label="' . $description . '"
                data-control="select2"
                data-kt-src="#"
                data-placeholder="' . $description . '"
                class="form-select form-select-solid fw-bolder">
                <option value=""></option>';
                    foreach ($data as $key => $value)
                        $output .= $default == $key ? '<option selected value="' . $key . '">' . $value . '</option>' : '<option value="' . $key . '">' . $value . '</option>';

                    $output .= '</select>
                <!--end::Input-->
            <div class="fv-plugins-message-container invalid-feedback">' . $error . '</div></div>
            <!--end::Col-->
        </div>
    ';

    return $output;
}

function group_select($name, $description, $data, $error = '', $default = null): string
{
    $output = '<style>
                .select2-container--bootstrap5 .select2-dropdown .select2-results__option.select2-results__option--disabled {
                    font-size: 10px !important;
//                    color: red !important;
                }
                </style>
        <!--begin::Input-->
        <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label required fw-bold fs-6">' . $description . '</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8 fv-plugins-icon-container">
                <div class="fv-row">
                    <select name="' . $name . '"
                    aria-label="' . $description . '"
                    data-control="select2"
                    data-kt-src="#"
                    data-placeholder="' . $description . '"
                    class="form-select form-select-solid fw-bolder">
                    <option value=""></option>';
    foreach ($data as $key => $value) {
        if (count($value->accounts) != 0) {
            $output .= '<option disabled class="disabled-group">'.$value->name;
            foreach ($value->accounts as $account) {
                $output .= '
                                <option value="' . $account->account_code . '">
                                    ' . $account->account_code . ' ' . $account->account_name . '
                                </option>
                                ';
            }
            $output .= '</option>';
        }
    }
    $output .= '</select>
                </div>
                <!--end::Input-->
            <div class="fv-plugins-message-container invalid-feedback">' . $error . '</div></div>
            <!--end::Col-->
        </div>
    ';

    return $output;
}
