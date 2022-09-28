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
    return '<div class="col-lg-8">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('.asset($default).')">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('.asset($value).'), url('.asset($default).')"></div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Label-->
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" name="'.$name.'" id="'.$id.'" accept=".png, .jpg, .jpeg">
                                        <!--end::Inputs-->
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Cancel-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" id="btn-remove-image" data-bs-original-title="Remove image">
																<i class="bi bi-x fs-2"></i>
															</span>
                                        <!--end::Cancel-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                    <!--end::Hint-->
                                </div>';
}
