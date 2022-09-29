<?php
function input_field($name, $description, $value, $required = false): string
{
    return '<div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">' . $description . '</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <input type="text" name="' . $name . '" class="form-control form-control-lg form-control-solid" placeholder="' . $description . '" value="' . $value . '" ' . ($required ? "required" : " ") . '>
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

function image_view($name, $id, $default, $value)
{

    return '<style>
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
//                .avatar-upload button {
//                  position: absolute;
//                  z-index: 1;
//                  top: 100px;
//                  text-align: center;
//                  border-radius: 100%;
//                  border: 0;
//                  width: 34px;
//                  height: 34px;
//                  left: 120px;
//                }
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
                    <div class="avatar-preview">
                        <img id="imagePreview" src="' . asset($value) . '" onerror="this.src=\'' . asset($default) . '\'">
                </div>
            </div>
            <script>
                function previewFile() {
                      var preview = document.querySelector("#imagePreview");
                      var file = document.querySelector("#imageUpload").files[0];
                      var reader = new FileReader();

                      reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $("#actual_imageInput").val(reader.result)
                      }, false);

                      if (file) {
                        reader.readAsDataURL(file);
                      }
                 }
                 document.querySelector(".company_logo").src = "' . asset($value) . '"
            </script>';
}
