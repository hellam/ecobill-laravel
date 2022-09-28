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
function image_view($name,$id,$default,$value,){
    return '<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('.asset($default).')">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-125px h-125px" style="background-image: url('.asset($default).')"></div>
                <!--end::Preview existing avatar-->
                <!--begin::Label-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <!--begin::Inputs-->
                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="avatar_remove">
                    <!--end::Inputs-->
                </label>
                <!--end::Label-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <!--end::Remove-->
            </div>';
}
