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
function div_start($class){
    return '<div class="'.$class.'">';
}
function div_end(){
    return '</div>';
}
function submit_button($name,$id){
    return '<button type="submit" class="btn btn-primary" id="'.$id.'">
                                <span class="indicator-label">'.$name.'</span>
                                <span class="indicator-progress">Please wait...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>';
}
